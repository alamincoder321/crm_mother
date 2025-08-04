<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use Illuminate\Support\Carbon;
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
            $item->details = DB::table('purchase_return_details as prd')
                ->select('p.name', 'p.code', 'u.name as unit_name', 'c.name as category_name', 'prd.*')
                ->leftJoin('products as p', 'p.id', '=', 'prd.product_id')
                ->leftJoin('units as u', 'u.id', '=', 'p.unit_id')
                ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
                ->where('purchase_return_id', $item->id)
                ->where('prd.status', 'a')
                ->where('prd.branch_id', $this->branchId)
                ->get();
            $supplier = Supplier::where('id', $item->supplier_id)->where('branch_id', $this->branchId)->withTrashed()->first();
            $purchase = Purchase::where('id', $item->purchase_id)->first();
            $item->supplier_code = $supplier->code ?? 'WalkIn Supplier';
            $item->supplier_name = $supplier->name ?? $purchase->supplier_name;
            $item->supplier_phone = $supplier->phone ?? $purchase->supplier_phone;
            $item->supplier_address = $supplier->address ?? $purchase->supplier_address;
            return $item;
        }, $returns);

        return response()->json($returns);
    }

    public function getDetailForReturns(Request $request)
    {
        $query = DB::select("select
                            pd.*,
                            p.code,
                            p.name,
                            (select sum(prd.quantity) from purchase_return_details prd
                            where prd.status = 'a'
                            and prd.purchase_detail_id = pd.id
                            and prd.branch_id = pd.branch_id
                            and prd.product_id = pd.product_id) as already_return_quantity,

                            (select sum(prd.total) from purchase_return_details prd
                            where prd.status = 'a'
                            and prd.purchase_detail_id = pd.id
                            and prd.branch_id = pd.branch_id
                            and prd.product_id = pd.product_id) as already_return_amount

                            from purchase_details pd
                            left join products p on p.id = pd.product_id
                            where pd.status = 'a'
                            and pd.branch_id = ?
                            and pd.purchase_id = ?", [$this->branchId, $request->purchaseId]);

        return response()->json($query);
    }

    public function create()
    {
        return view('pages.purchase.purchaseReturn');
    }

    public function store(PurchaseReturnRequest $request)
    {
        //check stock
        foreach ($request->carts as $key => $item) {
            $stock = Product::stock(['productId' => $item['product_id']])[0]->stock;
            if ($item['return_quantity'] > $stock) {
                return send_error("Stock unavailable this product: {$item['name']} - {$item['code']}", null, 422);
            }
        }
        try {
            DB::beginTransaction();
            $purchaseReturn = (object) $request->purchaseReturn;

            $data = array(
                'invoice' => invoiceGenerate('Purchase_Return', '', $this->branchId),
                'purchase_id' => $purchaseReturn->purchase_id,
                'supplier_id' => $purchaseReturn->supplier_id,
                'date' => $purchaseReturn->date,
                'total' => $purchaseReturn->total,
                'created_by' => $this->userId,
                'ipAddress' => request()->ip(),
                'branch_id' => $this->branchId
            );
            $purchaseReturn = PurchaseReturn::create($data);

            $cartDetails = array();
            foreach ($request->carts as $cart) {
                $cartDetails[] = [
                    'purchase_return_id' => $purchaseReturn->id,
                    'purchase_detail_id' => $cart['purchase_detail_id'],
                    'product_id'         => $cart['product_id'],
                    'purchase_rate'      => $cart['purchase_rate'],
                    'quantity'           => $cart['return_quantity'],
                    'discount'           => $cart['discount'] ?? 0,
                    'total'              => $cart['returnTotal'],
                    'created_by'         => $this->userId,
                    'ipAddress'          => request()->ip(),
                    'branch_id'          => $this->branchId,
                ];
            }
            PurchaseReturnDetail::insert($cartDetails);

            DB::commit();
            $msg = "Purchase Return has create successfully";
            return response()->json(['status' => true, 'message' => $msg]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = PurchaseReturn::find($request->id);
            $data->deleted_by = $this->userId;
            $data->status = 'd';
            $data->ipAddress = request()->ip();
            $data->update();

            PurchaseReturnDetail::where('purchase_return_id', $request->id)->update([
                'deleted_by' => $this->userId,
                'status' => 'd',
                'ipAddress' => request()->ip(),
                'deleted_at' => Carbon::now()
            ]);

            $data->delete();

            $msg = "Purchase Return has deleted successfully";
            return response()->json(['status' => true, 'message' => $msg]);
        } catch (\Throwable $th) {
            return send_error("Something went wrong", $th->getMessage());
        }
    }

    public function purchaseReturnRecord()
    {
        return view("pages.purchase.purchaseReturnRecord");
    }
}
