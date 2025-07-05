<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuotationRequest;
use App\Models\User;
use App\Models\Customer;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\QuotationDetail;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
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
        $quotations = Quotation::with('adUser', 'upUser')->where('branch_id', $this->branchId);
        if (!empty($request->quotationId)) {
            $quotations->where('id', $request->quotationId);
        }
        if (!empty($request->customerId)) {
            $quotations->where('customer_id', $request->customerId);
        }
        if (!empty($request->userId)) {
            $quotations->where('created_by', $request->userId);
        }
        if (!empty($request->dateFrom) && !empty($request->dateTo)) {
            $quotations->whereBetween('date', [$request->dateFrom, $request->dateTo]);
        }
        if (!empty($request->search)) {
            $quotations = $quotations->where(function ($query) use ($request) {
                $query->where('invoice', 'like', '%' . $request->search . '%')
                    ->orWhere('customer_name', 'like', '%' . $request->search . '%');
            });
        }
        if (!empty($request->forSearch)) {
            $quotations = $quotations->limit(50);
        }
        $quotations = $quotations->latest()->get()->map(function ($quotation) {
            $quotation->details = DB::table('quotation_details as pd')
                ->select('p.name', 'p.code', 'u.name as unit_name', 'c.name as category_name', 'pd.*')
                ->leftJoin('products as p', 'p.id', '=', 'pd.product_id')
                ->leftJoin('units as u', 'u.id', '=', 'p.unit_id')
                ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
                ->where('quotation_id', $quotation->id)
                ->where('pd.status', 'a')
                ->where('pd.branch_id', $this->branchId)
                ->get();

            $customer = Customer::where('id', $quotation->customer_id)->where('branch_id', $this->branchId)->withTrashed()->first();
            $quotation->customer_code = $customer->code ?? 'WalkIn Customer';
            $quotation->customer_name = $customer->name ?? $quotation->customer_name;
            $quotation->customer_phone = $customer->phone ?? $quotation->customer_phone;
            $quotation->customer_address = $customer->address ?? $quotation->customer_address;

            $employee = User::where('id', $quotation->employee_id)->where('branch_id', $this->branchId)->withTrashed()->first();
            $quotation->employee_name = $employee->name ?? "NA";

            $quotation->display_name = $quotation->invoice . ' - ' . $quotation->customer_name;
            return $quotation;
        }, $quotations);
        return response()->json($quotations);
    }

    public function create($id = "")
    {
        $data['id'] = $id;
        $data['invoice'] = invoiceGenerate('Quotation', '', $this->branchId);
        return view('pages.quotation.create', $data);
    }


    public function store(QuotationRequest $request)
    {
        try {
            DB::beginTransaction();
            $quotation = (object) $request->quotation;
            $customer = (object) $request->customer;
            $customerId = $customer->id ?? NULL;

            $invoice = Quotation::where('invoice', $quotation->invoice)->first();
            if (empty($invoice)) {
                $invoice = invoiceGenerate('Quotation', '', $this->branchId);
            }
            
            $dataKey = $quotation;
            unset($dataKey->id);
            unset($dataKey->invoice);
            $data = new Quotation();
            $data->invoice = $invoice;
            $data->employee_id = $quotation->employee_id ?? NULL;
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
                $data->customer_type = 'regular';
                $data->customer_id = $customerId;
            }
            $data->save();

            foreach ($request->carts as $key => $cart) {
                $detail = new QuotationDetail();
                $detail->quotation_id = $data->id;
                $detail->product_id = $cart['id'];
                $detail->purchase_rate = $cart['purchase_rate'];
                $detail->quantity = $cart['quantity'];
                $detail->sale_rate = $cart['sale_rate'];
                $detail->discount = $cart['discount'] ?? 0;
                $detail->vat = $cart['vat'] ?? 0;
                $detail->total = $cart['total'];
                $detail->created_by = $this->userId;
                $detail->ipAddress = request()->ip();
                $detail->branch_id = $this->branchId;
                $detail->save();
            }
            
            DB::commit();
            $msg = "Quotation has created successfully";
            return response()->json(['status' => true, 'message' => $msg, 'quotationId' => $data->id, 'invoice' => invoiceGenerate('Quotation', '', $this->branchId)]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function update(QuotationRequest $request)
    {
        try {
            DB::beginTransaction();
            $quotation = (object) $request->quotation;
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
                    $cus->type       = $quotation->customer_type;
                    $cus->address    = $customer->address;
                    $cus->created_by = $this->userId;
                    $cus->ipAddress  = request()->ip();
                    $cus->branch_id  = $this->branchId;
                    $cus->save();
                    $customerId = $cus->id;
                }
            }
            $dataKey = $quotation;
            unset($dataKey->invoice);
            $data = Quotation::find($quotation->id);
            $data->employee_id = $quotation->employee_id ?? NULL;
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
                $data->customer_type = 'regular';
                $data->customer_id = $customerId;
            }
            $data->update();


            // old quotation_detail delete
            QuotationDetail::where('quotation_id', $quotation->id)->forceDelete();

            foreach ($request->carts as $key => $cart) {
                $detail = new QuotationDetail();
                $detail->quotation_id = $quotation->id;
                $detail->product_id = $cart['id'];
                $detail->purchase_rate = $cart['purchase_rate'];
                $detail->quantity = $cart['quantity'];
                $detail->sale_rate = $cart['sale_rate'];
                $detail->discount = $cart['discount'] ?? 0;
                $detail->vat = $cart['vat'] ?? 0;
                $detail->total = $cart['total'];
                $detail->created_by = $data->created_by;
                $detail->updated_by = $this->userId;
                $detail->updated_at = Carbon::now();
                $detail->ipAddress = request()->ip();
                $detail->branch_id = $this->branchId;
                $detail->save();
            }

            DB::commit();
            $msg = "Quotation has updated successfully";
            return response()->json(['status' => true, 'message' => $msg, 'quotationId' => $quotation->id, 'invoice' => invoiceGenerate('quotation', '', $this->branchId)]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = Quotation::find($request->id);
            $data->deleted_by = $this->userId;
            $data->status = 'd';
            $data->ipAddress = request()->ip();
            $data->update();

            QuotationDetail::where('quotation_id', $request->id)->update([
                'deleted_by' => $this->userId,
                'status' => 'd',
                'ipAddress' => request()->ip(),
                'deleted_at' => Carbon::now()
            ]);

            $data->delete();

            $msg = "Quotation has deleted successfully";
            return response()->json(['status' => true, 'message' => $msg]);
        } catch (\Throwable $th) {
            return send_error("Something went wrong", $th->getMessage());
        }
    }

    public function quotationRecord()
    {
        return view("pages.quotation.index");
    }
}
