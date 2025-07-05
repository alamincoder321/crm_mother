<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Customer;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Requests\SaleRequest;
use App\Models\SaleBank;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    protected $userId;
    protected $branchId;
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->branchId = $request->session()->get('branch')->id;
            $this->userId = auth()->user()->id;
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $sales = Sale::with('adUser', 'upUser')->where('branch_id', $this->branchId);
        if (!empty($request->saleId)) {
            $sales->where('id', $request->saleId);
        }
        if (!empty($request->customerId)) {
            $sales->where('customer_id', $request->customerId);
        }
        if (!empty($request->userId)) {
            $sales->where('created_by', $request->userId);
        }
        if (!empty($request->dateFrom) && !empty($request->dateTo)) {
            $sales->whereBetween('date', [$request->dateFrom, $request->dateTo]);
        }
        if (!empty($request->search)) {
            $sales = $sales->where(function ($query) use ($request) {
                $query->where('invoice', 'like', '%' . $request->search . '%')
                    ->orWhere('customer_name', 'like', '%' . $request->search . '%');
            });
        }
        if (!empty($request->forSearch)) {
            $sales = $sales->limit(50);
        }
        $sales = $sales->latest()->get()->map(function ($sale) {
            $sale->details = DB::table('sale_details as pd')
                ->select('p.name', 'p.code', 'u.name as unit_name', 'c.name as category_name', 'pd.*')
                ->leftJoin('products as p', 'p.id', '=', 'pd.product_id')
                ->leftJoin('units as u', 'u.id', '=', 'p.unit_id')
                ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
                ->where('sale_id', $sale->id)
                ->where('pd.status', 'a')
                ->where('pd.branch_id', $this->branchId)
                ->get();
            $sale->bank_details = DB::table("sale_banks as sb")
                ->select('b.bank_name', 'b.number', 'sb.*')
                ->leftJoin('banks as b', 'b.id', '=', 'sb.bank_id')
                ->where('sb.sale_id', $sale->id)
                ->where('sb.status', 'a')
                ->where('sb.branch_id', $this->branchId)
                ->get();

            $customer = Customer::where('id', $sale->customer_id)->where('branch_id', $this->branchId)->withTrashed()->first();
            $sale->customer_code = $customer->code ?? 'WalkIn Customer';
            $sale->customer_name = $customer->name ?? $sale->customer_name;
            $sale->customer_phone = $customer->phone ?? $sale->customer_phone;
            $sale->customer_address = $customer->address ?? $sale->customer_address;

            $employee = User::where('id', $sale->employee_id)->where('branch_id', $this->branchId)->withTrashed()->first();
            $sale->employee_name = $employee->name ?? "NA";

            $sale->display_name = $sale->invoice . ' - ' . $sale->customer_name;
            return $sale;
        }, $sales);
        return response()->json($sales);
    }

    public function create($id = "")
    {
        $data['id'] = $id;
        $data['invoice'] = invoiceGenerate('Sale', '', $this->branchId);
        return view('pages.sale.create', $data);
    }


    public function store(SaleRequest $request)
    {
        try {
            DB::beginTransaction();
            $sale = (object) $request->sale;
            $customer = (object) $request->customer;
            $customerId = $customer->id ?? NULL;

            $invoice = Sale::where('invoice', $sale->invoice)->first();
            if (empty($invoice)) {
                $invoice = invoiceGenerate('Sale', '', $this->branchId);
            }
            if (!empty($customer) && $customer->type == 'new') {
                $checkSupp = Customer::where('phone', $customer->phone)->where('branch_id', $this->branchId)->first();
                if (!empty($checkSupp)) {
                    $customerId = $checkSupp->id;
                } else {
                    $cus             = new Customer();
                    $cus->code       = generateCode('Customer', 'C', $this->branchId);
                    $cus->name       = $customer->name;
                    $cus->owner      = $customer->name;
                    $cus->phone      = $customer->phone;
                    $cus->address    = $customer->address;
                    $cus->type       = $sale->sale_type;
                    $cus->created_by = $this->userId;
                    $cus->ipAddress  = request()->ip();
                    $cus->branch_id  = $this->branchId;
                    $cus->save();
                    $customerId = $cus->id;
                }
            }
            $dataKey = $sale;
            unset($dataKey->id);
            unset($dataKey->invoice);
            $data = new Sale();
            $data->invoice = $invoice;
            $data->employee_id = $sale->employee_id ?? NULL;
            foreach ($dataKey as $key => $value) {
                $data[$key] = $value;
            }
            $data->created_by = $this->userId;
            $data->ipAddress = request()->ip();
            $data->branch_id = $this->branchId;
            if (!empty($customer) && $customer->type == 'general') {
                $data->customer_name = $customer->name;
                $data->customer_phone = $customer->phone;
                $data->customer_address = $customer->address;
            } else {
                $data->customer_type = $sale->sale_type;
                $data->customer_id = $customerId;
            }
            $data->save();

            $cartDetails = [];
            foreach ($request->carts as $cart) {
                $cartDetails[] = [
                    'sale_id'       => $data->id,
                    'product_id'    => $cart['id'],
                    'purchase_rate' => $cart['purchase_rate'],
                    'quantity'      => $cart['quantity'],
                    'sale_rate'     => $cart['sale_rate'],
                    'discount'      => $cart['discount'] ?? 0,
                    'vat'           => $cart['vat'] ?? 0,
                    'total'         => $cart['total'],
                    'created_by'    => $data->created_by,
                    'ipAddress'     => request()->ip(),
                    'branch_id'     => $this->branchId,
                ];
            }
            SaleDetail::insert($cartDetails);

            // bank transaction
            if (!empty($sale->bankPaid) && $sale->bankPaid > 0) {
                $bankDetails = array();
                foreach ($request->bankCart as $key => $bank) {
                    $bankDetails[] = [
                        'sale_id' => $data->id,
                        'bank_id' => $bank['id'],
                        'last_digit' => $bank['last_digit'],
                        'amount' => $bank['amount'],
                        'created_by' => $this->userId,
                        'ipAddress' => request()->ip(),
                        'branch_id' => $this->branchId
                    ];
                }
                SaleBank::insert($bankDetails);
            }

            DB::commit();
            $msg = "Sale has created successfully";
            return response()->json(['status' => true, 'message' => $msg, 'saleId' => $data->id, 'invoice' => invoiceGenerate('Sale', '', $this->branchId)]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function update(SaleRequest $request)
    {
        try {
            DB::beginTransaction();
            $sale = (object) $request->sale;
            $customer = (object) $request->customer;
            $customerId = $customer->id ?? NULL;

            if (!empty($customer) && $customer->type == 'new') {
                $checkSupp = Customer::where('phone', $customer->phone)->where('branch_id', $this->branchId)->first();
                if (!empty($checkSupp)) {
                    $customerId = $checkSupp->id;
                } else {
                    $cus             = new Customer();
                    $cus->code       = generateCode('Customer', 'C', $this->branchId);
                    $cus->name       = $customer->name;
                    $cus->owner      = $customer->name;
                    $cus->phone      = $customer->phone;
                    $cus->type       = $sale->customer_type;
                    $cus->address    = $customer->address;
                    $cus->created_by = $this->userId;
                    $cus->ipAddress  = request()->ip();
                    $cus->branch_id  = $this->branchId;
                    $cus->save();
                    $customerId = $cus->id;
                }
            }
            $dataKey = $sale;
            unset($dataKey->invoice);
            $data = Sale::find($sale->id);
            $data->employee_id = $sale->employee_id ?? NULL;
            foreach ($dataKey as $key => $value) {
                $data[$key] = $value;
            }
            $data->updated_by = $this->userId;
            $data->updated_at = Carbon::now();
            $data->ipAddress = request()->ip();
            $data->branch_id = $this->branchId;
            if (!empty($customer) && $customer->type == 'general') {
                $data->customer_name = $customer->name;
                $data->customer_phone = $customer->phone;
                $data->customer_address = $customer->address;
            } else {
                $data->customer_type = $sale->sale_type;
                $data->customer_id = $customerId;
            }
            $data->update();


            // old sale_detail delete
            SaleDetail::where('sale_id', $sale->id)->forceDelete();
            $cartDetails = [];
            foreach ($request->carts as $cart) {
                $cartDetails[] = [
                    'sale_id'       => $data->id,
                    'product_id'    => $cart['id'],
                    'purchase_rate' => $cart['purchase_rate'],
                    'quantity'      => $cart['quantity'],
                    'sale_rate'     => $cart['sale_rate'],
                    'discount'      => $cart['discount'] ?? 0,
                    'vat'           => $cart['vat'] ?? 0,
                    'total'         => $cart['total'],
                    'created_by'    => $data->created_by,
                    'updated_by'    => $this->userId,
                    'ipAddress'     => request()->ip(),
                    'branch_id'     => $this->branchId,
                ];
            }
            SaleDetail::insert($cartDetails);

            // Delete Bank Transaction
            SaleBank::where('sale_id', $sale->id)->forceDelete();
            // bank transaction
            if (!empty($sale->bankPaid) && $sale->bankPaid > 0) {
                $bankDetails = array();
                foreach ($request->bankCart as $key => $bank) {
                    $bankDetails[] = [
                        'sale_id'    => $data->id,
                        'bank_id'    => $bank['id'],
                        'last_digit' => $bank['last_digit'],
                        'amount'     => $bank['amount'],
                        'created_by' => $data->created_by,
                        'updated_by' => $this->userId,
                        'ipAddress'  => request()->ip(),
                        'branch_id'  => $this->branchId
                    ];
                }
                SaleBank::insert($bankDetails);
            }

            DB::commit();
            $msg = "Sale has updated successfully";
            return response()->json(['status' => true, 'message' => $msg, 'saleId' => $sale->id, 'invoice' => invoiceGenerate('Sale', '', $this->branchId)]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = Sale::find($request->id);
            $data->deleted_by = $this->userId;
            $data->status = 'd';
            $data->ipAddress = request()->ip();
            $data->update();

            SaleDetail::where('sale_id', $request->id)->update([
                'deleted_by' => $this->userId,
                'status' => 'd',
                'ipAddress' => request()->ip(),
                'deleted_at' => Carbon::now()
            ]);

            SaleBank::where('sale_id', $request->id)->update([
                'deleted_by' => $this->userId,
                'status' => 'd',
                'ipAddress' => request()->ip(),
                'deleted_at' => Carbon::now()
            ]);

            $data->delete();

            $msg = "Sale has deleted successfully";
            return response()->json(['status' => true, 'message' => $msg]);
        } catch (\Throwable $th) {
            return send_error("Something went wrong", $th->getMessage());
        }
    }

    public function saleRecord()
    {
        return view("pages.sale.index");
    }
}
