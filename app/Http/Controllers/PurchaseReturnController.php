<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseReturnDetail;
use App\Http\Requests\PurchaseReturnRequest;

class PurchaseReturnController extends Controller
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
        $returns = PurchaseReturn::where('branch_id', $this->branchId);
        if (!empty($request->returnId)) {
            $returns = $returns->where('id', $request->returnId);
        }
        if (!empty($request->dateFrom) && !empty($request->dateTo)) {
            $returns = $returns->whereBetween('date', [$request->dateFrom, $request->dateTo]);
        }
        if (!empty($request->supplierId)) {
            $returns = $returns->where('supplier_id', $request->supplierId);
        }
        $returns = $returns->latest()->get()->map(function ($item) {
            $item->return_detail = PurchaseReturnDetail::where('purchase_return_id', $item->id)->where('status', 'a')->withTrashed()->get();
            return $item;
        }, $returns);

        return response()->json($returns);
    }

    public function create()
    {
        return view('pages.purchase.purchaseReturn');
    }

    public function store(PurchaseReturnRequest $request)
    {
        try {
            DB::beginTransaction();
            $purchaseReturn = (object) $request->purchaseReturn;
            
            $data = array(
                'invoice' => invoiceGenerate('Purchase_Return', '', $this->branchId),
                'supplier_id' => $purchaseReturn->supplierId,
                'purchase_id' => $purchaseReturn->purchaseId,
                'date' => $purchaseReturn->date,
                'total' => $purchaseReturn->total
            );
            $purchaseReturn = PurchaseReturn::create($data);

            DB::commit();
            $msg = "Purchase Return has updated successfully";
            return response()->json(['status' => true, 'message' => $msg]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return send_error('Something went worng', $th->getMessage());
        }
    }
}
