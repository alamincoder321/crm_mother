<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

    public function create()
    {
        return view('pages.purchase.create');
    }


    public function store(PurchaseRequest $request)
    {
        if (!$request->validated()) return send_error("Validation Error", $request->validated(), 422);
        try {
            $invoice = Purchase::where('invoice', $request->invoice)->first();
            if (empty($invoice)) {
                $invoice = invoiceGenerate('Purchase', 'P', $this->branchId);
            }
            $data = new Purchase();
            $data->invoice = $invoice;
            $dataKey = $request->except('id');
            foreach ($dataKey as $key => $value) {
                $data[$key] = $value;
            }
            $data->created_by = $this->userId;
            $data->ipAddress = request()->ip();
            $data->branch_id = $this->branchId;
            $data->save();

            $msg = "Purchase has created successfully";
            return response()->json(['status' => true, 'message' => $msg, 'invoice' => invoiceGenerate('Purchase', 'P', $this->branchId)]);
        } catch (\Throwable $th) {
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
            return response()->json(['status' => true, 'message' => $msg, 'invoice' => invoiceGenerate('Purchase', 'P', $this->branchId)]);
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
