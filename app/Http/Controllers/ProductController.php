<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
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
        $products = Product::with('adUser', 'upUser', 'category', 'brand', 'unit')->where('branch_id', $this->branchId);
        if (!empty($request->productId)) {
            $products = $products->where('id', $request->productId);
        }
        if (!empty($request->categoryId)) {
            $products = $products->where('category_id', $request->categoryId);
        }
        if (!empty($request->brandId)) {
            $products = $products->where('brand_id', $request->brandId);
        }

        if (!empty($request->search)) {
            $products = $products->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%')
                    ->orWhereHas('category', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }
        if (!empty($request->forSearch)) {
            $products = $products->limit(50);
        }

        $products = $products->latest()->get()->map(function ($item) {
            $item->display_name = $item->name . ' - ' . $item->category->name . ' - ' . $item->code;
            return $item;
        });
        return response()->json($products);
    }

    public function create()
    {
        if (!checkAccess('product')) {
            return view('error.403');
        }
        return view('pages.control.product.create');
    }

    public function productList()
    {
        if (!checkAccess('productList')) {
            return view('error.403');
        }
        return view('pages.control.product.index');
    }


    public function store(Request $request)
    {
        $branchId = $this->branchId;
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'unit_id' => 'required',
            'name' => [
                'required',
                Rule::unique('products')
                    ->where(function ($query) use ($branchId) {
                        $query->where('branch_id', $branchId);
                    })
                    ->whereNull('deleted_at'),
            ],
        ]);
        if ($validator->fails()) return send_error("Validation Error", $validator->errors(), 422);
        try {
            $check = Product::where('name', $request->name)->withTrashed()->first();
            if (!empty($check) && $check->deleted_at != NULL) {
                $check->status = 'a';
                $check->deleted_at = NULL;
                $check->update();
            } else {
                $data = new Product();
                $data->code = generateCode('Supplier', 'PI');
                $dataKey = $request->except('id', 'image');
                foreach ($dataKey as $key => $value) {
                    $data[$key] = $value;
                }
                if ($request->hasFile('image')) {
                    $data->image = imageUpload($request, 'image', 'uploads/product', $data->code . '_' . $this->branchId);
                }
                $data->created_by = $this->userId;
                $data->ipAddress = request()->ip();
                $data->branch_id = $this->branchId;
                $data->save();
            }

            return response()->json(['status' => true, 'message' => "Product has created successfully", 'code' => generateCode('Product', 'PI')]);
        } catch (\Throwable $th) {
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function update(Request $request)
    {
        $branchId = $this->branchId;
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'unit_id' => 'required',
            'name' => [
                'required',
                Rule::unique('products')
                    ->ignore($request->id)
                    ->where(function ($query) use ($branchId) {
                        $query->where('branch_id', $branchId);
                    })
                    ->whereNull('deleted_at'),
            ],
        ]);
        if ($validator->fails()) return send_error("Validation Error", $validator->errors(), 422);
        try {
            $data = Product::find($request->id);
            $dataKey = $request->except('id', 'brand_id', 'image');
            foreach ($dataKey as $key => $value) {
                $data[$key] = $value;
            }
            if ($request->hasFile('image')) {
                if (File::exists($data->image)) {
                    File::delete($data->image);
                }
                $data->image = imageUpload($request, 'image', 'uploads/product', $data->code . '_' . $this->branchId);
            }
            if (!empty($request->brand_id)) {
                $data->brand_id = $request->brand_id;
            }
            $data->updated_by = $this->userId;
            $data->updated_at = Carbon::now();
            $data->ipAddress = request()->ip();
            $data->branch_id = $this->branchId;
            $data->update();

            return response()->json(['status' => true, 'message' => "Product has updated successfully", 'code' => generateCode('Product', 'PI')]);
        } catch (\Throwable $th) {
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = Product::find($request->id);
            if (File::exists($data->image)) {
                File::delete($data->image);
            }
            $data->status = 'd';
            $data->ipAddress = request()->ip();
            $data->update();

            $data->delete();
            return response()->json(['status' => true, 'message' => "Product has deleted successfully"]);
        } catch (\Throwable $th) {
            return send_error("Something went wrong", $th->getMessage());
        }
    }

    public function stock()
    {
        return view('pages.stock.current_stock');
    }

    public function getProductStock(Request $request)
    {
        try {
            $date = null;
            if (!empty($request->date)) {
                $date = $request->date;
            }
            $stock = Product::stock($request, $date);
            return response()->json($stock, 200);
        } catch (\Throwable $th) {
            return send_error("Something went wrong", $th->getMessage());
        }
    }

    public function productLedger()
    {
        return view('pages.report.productLedger');
    }

    public function getProductLedger(Request $request)
    {
        $branchId = $this->branchId;
        $query = "select
                'a' as sequence,
                pd.id,
                pm.date,
                concat_ws(' - ', 'Purchase Invoice', pm.invoice) as description,
                pd.quantity as in_stock,
                0 as out_stock,
                0 as stock
                from purchase_details pd
                left join purchases pm on pm.id = pd.purchase_id
                where pd.status = 'a'
                " . (empty($request->productId) ? "" : " and pd.product_id = '$request->productId'") . "
                " . ($branchId == null ? "" : " and pd.branch_id = '$branchId'") . "

                UNION
                select
                'b' as sequence,
                srd.id,
                sr.date,
                concat_ws(' - ', 'Sale Return Invoice', sr.invoice) as description,
                srd.quantity as in_stock,
                0 as out_stock,
                0 as stock
                from sale_return_details srd
                left join sale_returns sr on sr.id = srd.sale_return_id
                where srd.status = 'a'
                " . (empty($request->productId) ? "" : " and srd.product_id = '$request->productId'") . "
                " . ($branchId == null ? "" : " and srd.branch_id = '$branchId'") . "
                
                UNION
                select
                'c' as sequence,
                sd.id,
                sm.date,
                concat_ws(' - ', 'Sale Invoice', ifnull(sm.invoice, '')) as description,
                0 as in_stock,
                sd.quantity as out_stock,
                0 as stock
                from sale_details sd
                left join sales sm on sm.id = sd.sale_id
                where sd.status = 'a'
                " . (empty($request->productId) ? "" : " and sd.product_id = '$request->productId'") . "
                " . ($branchId == null ? "" : " and sd.branch_id = '$branchId'") . "
                
                UNION
                select
                'd' as sequence,
                prd.id,
                pr.date,
                concat_ws(' - ', 'Purchase Return Invoice', pr.invoice) as description,
                0 as in_stock,
                prd.quantity as out_stock,
                0 as stock
                from purchase_return_details prd
                left join purchase_returns pr on pr.id = prd.purchase_return_id
                where prd.status = 'a'
                " . (empty($request->productId) ? "" : " and prd.product_id = '$request->productId'") . "
                " . ($branchId == null ? "" : " and prd.branch_id = '$branchId'") . "
                
                UNION
                select
                'e' as sequence,
                dd.id,
                d.date,
                concat_ws(' - ', 'Damage Invoice', d.invoice) as description,
                0 as in_stock,
                dd.quantity as out_stock,
                0 as stock
                from damage_details dd
                left join damages d on d.id = dd.damage_id
                where dd.status = 'a'
                " . (empty($request->productId) ? "" : " and dd.product_id = '$request->productId'") . "
                " . ($branchId == null ? "" : " and dd.branch_id = '$branchId'") . "
                
                order by date, sequence asc";

        $ledgers = DB::select($query);
        $ledgers = collect($ledgers)->map(function ($ledger, $key) use ($ledgers) {
            $lastStock = $key == 0 ? 0 : $ledgers[$key - 1]->stock;
            $ledger->stock = ($lastStock + $ledger->in_stock) - $ledger->out_stock;
            return $ledger;
        });

        $previousLedger = collect($ledgers)->filter(function ($ledger) use ($request) {
            return $ledger->date < $request->dateFrom;
        });
        $previousStock = count($previousLedger) > 0 ? $previousLedger[count($previousLedger) - 1]->stock : 0;

        if (!empty($request->dateFrom) && !empty($request->dateTo)) {
            $ledgers = $ledgers->filter(function ($ledger) use ($request) {
                return $ledger->date >= $request->dateFrom && $ledger->date <= $request->dateTo;
            })->values();
        }

        return response()->json(['previousStock' => $previousStock, 'ledgers' => $ledgers]);
    }
}
