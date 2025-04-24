<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\PurchaseDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
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
        $transactions = Purchase::with('adUser', 'upUser', 'bank', 'supplier', 'customer')->where('branch_id', $this->branchId);
        if (!empty($request->transactionId)) {
            $transactions->where('id', $request->transactionId);
        }
        if (!empty($request->customerId)) {
            $transactions->where('customer_id', $request->customerId);
        }
        if (!empty($request->supplierId)) {
            $transactions->where('supplier_id', $request->supplierId);
        }
        if (!empty($request->bankId)) {
            $transactions->where('bank_id', $request->bankId);
        }
        if (!empty($request->type)) {
            $transactions->where('type');
        }
        if (!empty($request->dateFrom) && !empty($request->dateTo)) {
            $transactions->whereBetween('date', [$request->dateFrom, $request->dateTo]);
        }
        $transactions = $transactions->latest()->get();
        return response()->json($transactions);
    }

    public function create($id = "")
    {
        $data['id'] = $id;
        $data['invoice'] = invoiceGenerate('Purchase', '', $this->branchId);
        return view('pages.purchase.create', $data);
    }


    public function store(Request $request)
    {
        // if (!$request->validated()) return send_error("Validation Error", $request->validated(), 422);
        try {
            DB::beginTransaction();
            $purchase = (object) json_decode($request->purchase, true);
            $supplier = (object) json_decode($request->supplier, true);

            $invoice = Purchase::where('invoice', $purchase->invoice)->first();
            if (empty($invoice)) {
                $invoice = invoiceGenerate('Purchase', '', $this->branchId);
            }
            if(!empty($supplier) && $supplier->type == 'New'){
                $supplier = new Supplier();
            }
            $dataKey = $purchase;
            unset($dataKey->id);
            unset($dataKey->invoice);
            unset($dataKey->employee_id);
            unset($dataKey->supplier_id);
            $data = new Purchase();
            $data->invoice = $invoice;
            $data->employee_id = $purchase->employee_id ?? NULL;
            foreach ($dataKey as $key => $value) {
                $data[$key] = $value;
            }
            $data->created_by = $this->userId;
            $data->ipAddress = request()->ip();
            $data->branch_id = $this->branchId;
            if (!empty($supplier) && $supplier->type == 'general') {
                $data->supplier_name = $supplier->name;
                $data->supplier_phone = $supplier->phone;
                $data->supplier_address = $supplier->address;
            } else {
                $data->supplier_type = $supplier->type;
                $data->supplier_id = $supplier->id;
            }
            $data->save();

            $carts = json_decode($request->carts, true);
            foreach ($carts as $key => $cart) {
                $detail = new PurchaseDetail();
                $detail->purchase_id = $data->id;
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
            $msg = "Purchase has created successfully";
            return response()->json(['status' => true, 'message' => $msg, 'invoice' => invoiceGenerate('Purchase', '', $this->branchId)]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function update(PurchaseRequest $request)
    {
        if (!$request->validated()) return send_error("Validation Error", $request->validated(), 422);
        try {
            $data = Purchase::find($request->id);
            $dataKey = $request->except('id');
            foreach ($dataKey as $key => $value) {
                $data[$key] = $value;
            }
            if ($request->Purchase_method == 'cash') {
                $data->bank_id = NULL;
            }
            $data->updated_by = $this->userId;
            $data->updated_at = Carbon::now();
            $data->ipAddress = request()->ip();
            $data->branch_id = $this->branchId;
            $data->update();

            $msg = "Purchase has update successfully";
            return response()->json(['status' => true, 'message' => $msg, 'invoice' => invoiceGenerate('Purchase', '', $this->branchId)]);
        } catch (\Throwable $th) {
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = Purchase::find($request->id);
            $data->deleted_by = $this->userId;
            $data->status = 'd';
            $data->ipAddress = request()->ip();
            $data->update();

            $data->delete();
            if ($request->type == 'customer') {
                $msg = "Customer Purchase has deleted successfully";
            } else {
                $msg = "Supplier Purchase has deleted successfully";
            }
            return response()->json(['status' => true, 'message' => $msg]);
        } catch (\Throwable $th) {
            return send_error("Something went wrong", $th->getMessage());
        }
    }
}
