<?php

namespace App\Http\Controllers;

use App\Http\Requests\DamageRequest;
use App\Models\Damage;
use App\Models\Product;
use App\Models\DamageDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DamageController extends Controller
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
        $damages = Damage::with('adUser', 'upUser')->where('branch_id', $this->branchId);
        if (!empty($request->damageId)) {
            $damages->where('id', $request->damageId);
        }
        if (!empty($request->supplierId)) {
            $damages->where('supplier_id', $request->supplierId);
        }
        if (!empty($request->userId)) {
            $damages->where('created_by', $request->userId);
        }
        if (!empty($request->dateFrom) && !empty($request->dateTo)) {
            $damages->whereBetween('date', [$request->dateFrom, $request->dateTo]);
        }
        if (!empty($request->search)) {
            $damages = $damages->where(function ($query) use ($request) {
                $query->where('invoice', 'like', '%' . $request->search . '%')
                    ->orWhere('customer_name', 'like', '%' . $request->search . '%');
            });
        }
        if (!empty($request->forSearch)) {
            $damages = $damages->limit(50);
        }
        $damages = $damages->latest()->get()->map(function ($damage) {
            $damage->details = DB::table('damage_details as dd')
                ->select(
                    'p.name',
                    'p.code',
                    'u.name as unit_name',
                    'c.name as category_name',
                    'dd.*'
                )
                ->leftJoin('products as p', 'p.id', '=', 'dd.product_id')
                ->leftJoin('units as u', 'u.id', '=', 'p.unit_id')
                ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
                ->where('damage_id', $damage->id)
                ->where('dd.status', 'a')
                ->where('dd.branch_id', $this->branchId)
                ->get();

            $supplier                 = Supplier::where('id', $damage->supplier_id)->where('branch_id', $this->branchId)->withTrashed()->first();
            $damage->supplier_code    = $supplier->code ?? 'WalkIn Supplier';
            $damage->supplier_name    = $supplier->name ?? $damage->supplier_name;
            $damage->supplier_phone   = $supplier->phone ?? $damage->supplier_phone;
            $damage->supplier_address = $supplier->address ?? $damage->supplier_address;
            return $damage;
        }, $damages);
        return response()->json($damages);
    }

    public function create($id = "")
    {
        $data['id'] = $id;
        $data['invoice'] = invoiceGenerate('Damage', '', $this->branchId);
        return view('pages.damage.create', $data);
    }


    public function store(DamageRequest $request)
    {
        //check stock
        foreach ($request->carts as $key => $item) {
            $stock = Product::stock(['productId' => $item['id']])[0]->stock;
            if ($item['quantity'] > $stock) {
                return send_error("Stock unavailable this product: {$item['name']}", null, 422);
            }
        }
        try {
            DB::beginTransaction();
            $damage = (object) $request->damage;
            $supplier = (object) $request->supplier;
            $supplierId = $supplier->id ?? NULL;

            $invoice = Damage::where('invoice', $damage->invoice)->first();
            if (empty($invoice)) {
                $invoice = invoiceGenerate('Damage');
            }
            $dataKey = $damage;
            unset($dataKey->id);
            unset($dataKey->invoice);
            $data = new Damage();
            $data->invoice = $invoice;
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
                $data->supplier_type = 'retail';
                $data->supplier_id = $supplierId;
            }
            $data->save();

            $cartDetails = [];
            foreach ($request->carts as $cart) {
                $cartDetails[] = [
                    'damage_id'     => $data->id,
                    'product_id'    => $cart['id'],
                    'purchase_rate' => $cart['purchase_rate'],
                    'quantity'      => $cart['quantity'],
                    'discount'      => $cart['discount'] ?? 0,
                    'total'         => $cart['total'],
                    'created_by'    => $data->created_by,
                    'ipAddress'     => request()->ip(),
                    'branch_id'     => $this->branchId,
                ];
            }
            DamageDetail::insert($cartDetails);

            DB::commit();
            $msg = "Damage has created successfully";
            return response()->json(['status' => true, 'message' => $msg, 'damageId' => $data->id, 'invoice' => invoiceGenerate('Damage')]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function update(DamageRequest $request)
    {
        try {
            DB::beginTransaction();
            $damage = (object) $request->damage;
            $supplier = (object) $request->supplier;
            $supplierId = $supplier->id ?? NULL;

            $dataKey = $damage;
            unset($dataKey->invoice);
            $data = Damage::find($damage->id);
            $data->employee_id = $damage->employee_id ?? NULL;
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
                $data->supplier_type = 'retail';
                $data->supplier_id = $supplierId;
            }
            $data->update();


            // old damage_detail delete
            DamageDetail::where('damage_id', $damage->id)->forceDelete();
            //check stock
            foreach ($request->carts as $key => $item) {
                $stock = Product::stock(['productId' => $item['id']])[0]->stock;
                if ($item['quantity'] > $stock) {
                    return send_error("Stock unavailable this product: {$item['name']}", null, 422);
                }
            }
            $cartDetails = [];
            foreach ($request->carts as $cart) {
                $cartDetails[] = [
                    'damage_id'     => $data->id,
                    'product_id'    => $cart['id'],
                    'purchase_rate' => $cart['purchase_rate'],
                    'quantity'      => $cart['quantity'],
                    'discount'      => $cart['discount'] ?? 0,
                    'total'         => $cart['total'],
                    'created_by'    => $data->created_by,
                    'updated_by'    => $this->userId,
                    'ipAddress'     => request()->ip(),
                    'branch_id'     => $this->branchId,
                ];
            }
            DamageDetail::insert($cartDetails);

            DB::commit();
            $msg = "Damage has updated successfully";
            return response()->json(['status' => true, 'message' => $msg, 'damageId' => $damage->id, 'invoice' => invoiceGenerate('Damage')]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        //check return
        try {
            $data = Damage::find($request->id);
            $data->deleted_by = $this->userId;
            $data->status = 'd';
            $data->ipAddress = request()->ip();
            $data->update();

            DamageDetail::where('damge_id', $request->id)->update([
                'deleted_by' => $this->userId,
                'status' => 'd',
                'ipAddress' => request()->ip(),
                'deleted_at' => Carbon::now()
            ]);

            $data->delete();

            $msg = "Damage has deleted successfully";
            return response()->json(['status' => true, 'message' => $msg]);
        } catch (\Throwable $th) {
            return send_error("Something went wrong", $th->getMessage());
        }
    }

    public function damageRecord()
    {
        return view("pages.damage.index");
    }
}
