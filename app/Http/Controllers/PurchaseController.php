<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\PurchaseDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PurchaseRequest;
use App\Models\PurchaseReturn;

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
        $purchases = Purchase::with('adUser', 'upUser')->where('branch_id', $this->branchId);
        if (!empty($request->purchaseId)) {
            $purchases->where('id', $request->purchaseId);
        }
        if (!empty($request->supplierId)) {
            $purchases->where('supplier_id', $request->supplierId);
        }
        if (!empty($request->userId)) {
            $purchases->where('created_by', $request->userId);
        }
        if (!empty($request->dateFrom) && !empty($request->dateTo)) {
            $purchases->whereBetween('date', [$request->dateFrom, $request->dateTo]);
        }
        if (!empty($request->search)) {
            $purchases = $purchases->where(function ($query) use ($request) {
                $query->where('invoice', 'like', '%' . $request->search . '%')
                    ->orWhere('supplier_name', 'like', '%' . $request->search . '%');
            });
        }
        if (!empty($request->forSearch)) {
            $purchases = $purchases->limit(50);
        }
        $purchases = $purchases->latest()->get()->map(function ($purchase) {
            $purchase->details = DB::table('purchase_details as pd')
                ->select('p.name', 'p.code', 'u.name as unit_name', 'c.name as category_name', 'pd.*')
                ->leftJoin('products as p', 'p.id', '=', 'pd.product_id')
                ->leftJoin('units as u', 'u.id', '=', 'p.unit_id')
                ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
                ->where('purchase_id', $purchase->id)
                ->where('pd.status', 'a')
                ->where('pd.branch_id', $this->branchId)
                ->get();
            $supplier = Supplier::where('id', $purchase->supplier_id)->where('branch_id', $this->branchId)->withTrashed()->first();
            $purchase->supplier_code = $supplier->code ?? 'Walk-In Supplier';
            $purchase->supplier_name = $supplier->name ?? $purchase->supplier_name;
            $purchase->supplier_phone = $supplier->phone ?? $purchase->supplier_phone;
            $purchase->supplier_address = $supplier->address ?? $purchase->supplier_address;

            $employee = User::where('id', $purchase->employee_id)->where('branch_id', $this->branchId)->withTrashed()->first();
            $purchase->employee_name = $employee->name ?? "NA";

            $purchase->display_name = $purchase->invoice . ' - ' . $purchase->supplier_name;
            return $purchase;
        }, $purchases);
        return response()->json($purchases);
    }

    public function create($id = "")
    {
        $data['id'] = $id;
        $data['invoice'] = invoiceGenerate('Purchase', '', $this->branchId);
        return view('pages.purchase.create', $data);
    }


    public function store(PurchaseRequest $request)
    {
        try {
            DB::beginTransaction();
            $purchase = (object) $request->purchase;
            $supplier = (object) $request->supplier;
            $supplierId = $supplier->id ?? NULL;

            $invoice = Purchase::where('invoice', $purchase->invoice)->first();
            if (empty($invoice)) {
                $invoice = invoiceGenerate('Purchase', '', $this->branchId);
            }
            if (!empty($supplier) && $supplier->type == 'new') {
                $checkSupp = Supplier::where('phone', $supplier->phone)->where('branch_id', $this->branchId)->first();
                if (!empty($checkSupp)) {
                    $supplierId = $checkSupp->id;
                } else {
                    $supp = new Supplier();
                    $supp->code = generateCode('Supplier', 'SI');
                    $supp->name = $supplier->name;
                    $supp->owner = $supplier->name;
                    $supp->phone = $supplier->phone;
                    $supp->address = $supplier->address;
                    $supp->created_by = $this->userId;
                    $supp->ipAddress = request()->ip();
                    $supp->branch_id = $this->branchId;
                    $supp->save();
                    $supplierId = $supp->id;
                }
            }
            $dataKey = $purchase;
            unset($dataKey->id);
            unset($dataKey->invoice);
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
                $data->supplier_type = 'regular';
                $data->supplier_id = $supplierId;
            }
            $data->save();

            $cartDetails = [];
            foreach ($request->carts as $cart) {
                $cartDetails[] = [
                    'purchase_id'   => $data->id,
                    'product_id'    => $cart['id'],
                    'purchase_rate' => $cart['purchase_rate'],
                    'quantity'      => $cart['quantity'],
                    'sale_rate'     => $cart['sale_rate'],
                    'discount'      => $cart['discount'] ?? 0,
                    'vat'           => $cart['vat'] ?? 0,
                    'total'         => $cart['total'],
                    'created_by'    => $this->userId,
                    'ipAddress'     => request()->ip(),
                    'branch_id'     => $this->branchId,
                ];
            }
            PurchaseDetail::insert($cartDetails);

            DB::commit();
            $msg = "Purchase has created successfully";
            return response()->json(['status' => true, 'message' => $msg, 'purchaseId' => $data->id, 'invoice' => invoiceGenerate('Purchase', '', $this->branchId)]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function update(PurchaseRequest $request)
    {
        try {
            DB::beginTransaction();
            $purchase = (object) $request->purchase;
            $supplier = (object) $request->supplier;
            $supplierId = $supplier->id ?? NULL;

            if (!empty($supplier) && $supplier->type == 'new') {
                $checkSupp = Supplier::where('phone', $supplier->phone)->where('branch_id', $this->branchId)->first();
                if (!empty($checkSupp)) {
                    $supplierId = $checkSupp->id;
                } else {
                    $supp = new Supplier();
                    $supp->code = generateCode('Supplier', 'SI');
                    $supp->name = $supplier->name;
                    $supp->owner = $supplier->name;
                    $supp->phone = $supplier->phone;
                    $supp->address = $supplier->address;
                    $supp->created_by = $this->userId;
                    $supp->ipAddress = request()->ip();
                    $supp->branch_id = $this->branchId;
                    $supp->save();
                    $supplierId = $supp->id;
                }
            }
            $dataKey = $purchase;
            unset($dataKey->invoice);
            $data = Purchase::find($purchase->id);
            $data->employee_id = $purchase->employee_id ?? NULL;
            foreach ($dataKey as $key => $value) {
                $data[$key] = $value;
            }
            $data->updated_by = $this->userId;
            $data->updated_at = Carbon::now();
            $data->ipAddress = request()->ip();
            $data->branch_id = $this->branchId;
            if (!empty($supplier) && $supplier->type == 'general') {
                $data->supplier_name = $supplier->name;
                $data->supplier_phone = $supplier->phone;
                $data->supplier_address = $supplier->address;
            } else {
                $data->supplier_type = 'regular';
                $data->supplier_id = $supplierId;
            }
            $data->update();


            // old purchase_detail delete
            PurchaseDetail::where('purchase_id', $purchase->id)->forceDelete();
            $cartDetails = [];
            foreach ($request->carts as $cart) {
                $cartDetails[] = [
                    'purchase_id'   => $data->id,
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
            PurchaseDetail::insert($cartDetails);

            DB::commit();
            $msg = "Purchase has updated successfully";
            return response()->json(['status' => true, 'message' => $msg, 'purchaseId' => $purchase->id, 'invoice' => invoiceGenerate('Purchase', '', $this->branchId)]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $carts = DB::table('purchase_details as pd')
            ->select("pd.*", "p.code", "p.name")
            ->leftJoin('products as p', 'p.id', '=', 'pd.product_id')
            ->where('pd.purchase_id', $request->id)->get();
        //check stock
        foreach ($carts as $item) {
            $stock = Product::stock(['productId' => $item->product_id])[0]->stock;
            if ($item->quantity > $stock) {
                return send_error("Stock unavailable this product: {$item->name} - {$item->code}", null, 422);
            }
        }
        //check return
        $checkReturn = PurchaseReturn::where('purchase_id', $request->id)->first();
        if (!empty($checkReturn)) return send_error("Purchase return found. You can not delete purchase", null, 422);

        try {
            $data = Purchase::find($request->id);
            $data->deleted_by = $this->userId;
            $data->status = 'd';
            $data->ipAddress = request()->ip();
            $data->update();

            PurchaseDetail::where('purchase_id', $request->id)->update([
                'deleted_by' => $this->userId,
                'status' => 'd',
                'ipAddress' => request()->ip(),
                'deleted_at' => Carbon::now()
            ]);

            $data->delete();

            $msg = "Purchase has deleted successfully";
            return response()->json(['status' => true, 'message' => $msg]);
        } catch (\Throwable $th) {
            return send_error("Something went wrong", $th->getMessage());
        }
    }

    public function purchaseRecord()
    {
        return view("pages.purchase.index");
    }

    public function purchaseInvoice($id)
    {
        return view("pages.purchase.purchaseInvoice", compact('id'));
    }
}
