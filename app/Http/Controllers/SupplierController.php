<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
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
        $suppliers = Supplier::with('adUser', 'upUser', 'area')->where('branch_id', $this->branchId);
        if (!empty($request->supplierId)) {
            $suppliers = $suppliers->where('id', $request->supplierId);
        }
        if (!empty($request->areaId)) {
            $suppliers = $suppliers->where('area_id', $request->areaId);
        }
        if (!empty($request->search)) {
            $suppliers = $suppliers->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }
        if (!empty($request->forSearch)) {
            $suppliers = $suppliers->limit(50);
        }
        $suppliers = $suppliers->latest()->get()->map(function ($item) {
            $item->type = 'regular';
            $item->display_name = $item->name . ' - ' . $item->phone . ' - ' . $item->code;
            return $item;
        });
        return response()->json($suppliers);
    }

    public function create()
    {
        return view('pages.control.supplier.create');
    }

    public function supplierList()
    {
        return view('pages.control.supplier.index');
    }


    public function store(Request $request)
    {
        $branchId = $this->branchId;
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'phone' => [
                'required',
                Rule::unique('suppliers')
                    ->where(function ($query) use ($branchId) {
                        $query->where('branch_id', $branchId);
                    })
                    ->whereNull('deleted_at'),
            ],
        ]);
        if ($validator->fails()) return send_error("Validation Error", $validator->errors(), 422);
        try {
            $check = Supplier::where('phone', $request->phone)->withTrashed()->first();
            if (!empty($check) && $check->deleted_at != NULL) {
                $check->status = 'a';
                $check->deleted_at = NULL;
                $check->update();
            } else {
                $data = new Supplier();
                $data->code = generateCode('Supplier', 'S');
                $dataKey = $request->except('id', 'image');
                foreach ($dataKey as $key => $value) {
                    $data[$key] = $value;
                }
                if ($request->hasFile('image')) {
                    $data->image = imageUpload($request, 'image', 'uploads/supplier', $data->code . '_' . $this->branchId);
                }
                $data->created_by = $this->userId;
                $data->ipAddress = request()->ip();
                $data->branch_id = $this->branchId;
                $data->save();
            }

            return response()->json(['status' => true, 'message' => "Supplier has created successfully"]);
        } catch (\Throwable $th) {
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function update(Request $request)
    {
        $branchId = $this->branchId;
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'phone' => [
                'required',
                Rule::unique('suppliers')
                    ->ignore($request->id)
                    ->where(function ($query) use ($branchId) {
                        $query->where('branch_id', $branchId);
                    })
                    ->whereNull('deleted_at'),
            ],
        ]);
        if ($validator->fails()) return send_error("Validation Error", $validator->errors(), 422);
        try {
            $data = Supplier::find($request->id);
            $dataKey = $request->except('id', 'image');
            foreach ($dataKey as $key => $value) {
                $data[$key] = $value;
            }
            if ($request->hasFile('image')) {
                if (File::exists($data->image)) {
                    File::delete($data->image);
                }
                $data->image = imageUpload($request, 'image', 'uploads/supplier', $data->code . '_' . $this->branchId);
            }
            $data->updated_by = $this->userId;
            $data->updated_at = Carbon::now();
            $data->ipAddress = request()->ip();
            $data->branch_id = $this->branchId;
            $data->update();

            return response()->json(['status' => true, 'message' => "Supplier has updated successfully"]);
        } catch (\Throwable $th) {
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = Supplier::find($request->id);
            if (File::exists($data->image)) {
                File::delete($data->image);
            }
            $data->status = 'd';
            $data->ipAddress = request()->ip();
            $data->update();

            $data->delete();
            return response()->json(['status' => true, 'message' => "Supplier has deleted successfully"]);
        } catch (\Throwable $th) {
            return send_error("Something went wrong", $th->getMessage());
        }
    }

    // supplier due
    public function supplierDue()
    {
        return view('pages.report.supplierDue');
    }

    public function getSupplierDue(Request $request)
    {
        $date = $request->date ? $request->date : null;
        $dues = Supplier::supplierDue($request, $date);
        return response()->json($dues);
    }

    public function supplierLedger()
    {
        return view('pages.report.supplierLedger');
    }

    public function getSupplierLedger(Request $request)
    {
        $branchId = $this->branchId;
        $query = "select
                'a' as sequence,
                pm.id,
                pm.date,
                concat('Purchase Invoice - ', pm.invoice, '(Supplier: ', ifnull(s.name, pm.supplier_name), ')') as description,
                pm.total as bill,
                pm.paid as paid,
                (pm.total - pm.paid) as due,
                0 as cash_payment,
                0 as cash_receive,
                0 as return_amount,
                0 as balance
                from purchases pm
                left join suppliers s on s.id = pm.supplier_id
                where pm.status = 'a'
                " . (empty($request->supplierId) ? "" : " and pm.supplier_id = '$request->supplierId'") . "
                " . ($branchId == null ? "" : " and pm.branch_id = '$branchId'") . "

                UNION
                select
                'b' as sequence,
                pr.id,
                pr.date,
                concat('Purchase Return Invoice - ', pr.invoice) as description,
                0 as bill,
                0 as paid,
                0 as due,
                0 as cash_payment,
                0 as cash_receive,
                pr.total as return_amount,
                0 as balance
                from purchase_returns pr
                left join suppliers s on s.id = pr.supplier_id
                where pr.status = 'a'
                " . (empty($request->supplierId) ? "" : " and pr.supplier_id = '$request->supplierId'") . "
                " . ($branchId == null ? "" : " and pr.branch_id = '$branchId'") . "

                UNION
                select
                'c' as sequence,
                sp.id,
                sp.date,
                concat('Supplier Payment - ', sp.invoice) as description,
                0 as bill,
                0 as paid,
                0 as due,
                sp.amount as cash_payment,
                0 as cash_receive,
                0 as return_amount,
                0 as balance
                from payments sp
                left join suppliers s on s.id = sp.supplier_id
                where sp.status = 'a'
                and sp.type = 'supplier'
                " . (empty($request->supplierId) ? "" : " and sp.supplier_id = '$request->supplierId'") . "
                " . ($branchId == null ? "" : " and sp.branch_id = '$branchId'") . "

                UNION
                select
                'd' as sequence,
                sp.id,
                sp.date,
                concat('Supplier Receive - ', sp.invoice) as description,
                0 as bill,
                0 as paid,
                0 as due,
                0 as cash_payment,
                sp.amount as cash_receive,
                0 as return_amount,
                0 as balance
                from receives sp
                left join suppliers s on s.id = sp.supplier_id
                where sp.status = 'a'
                and sp.type = 'supplier'
                " . (empty($request->supplierId) ? "" : " and sp.supplier_id = '$request->supplierId'") . "
                " . ($branchId == null ? "" : " and sp.branch_id = '$branchId'") . "
                
                order by date, sequence asc";

        $ledgers = DB::select($query);

        $supplier = Supplier::select('previous_due')->where('id', $request->supplierId)
            ->where('branch_id', $branchId)
            ->first();
        $previousBalance = empty($supplier) ? 0 : $supplier->previous_due;

        $ledgers = collect($ledgers)->map(function ($ledger, $key) use ($previousBalance, $ledgers) {
            $lastBalance = $key == 0 ? $previousBalance : $ledgers[$key - 1]->balance;
            $ledger->balance = ($lastBalance + $ledger->bill + $ledger->cash_receive + $ledger->return_amount) - ($ledger->paid + $ledger->cash_payment);
            return $ledger;
        });

        $previousLedger = collect($ledgers)->filter(function ($ledger) use ($request) {
            return $ledger->date < $request->dateFrom;
        });
        $previousBalance = count($previousLedger) > 0 ? $previousLedger[count($previousLedger) - 1]->balance : $previousBalance;

        if (!empty($request->dateFrom) && !empty($request->dateTo)) {
            $ledgers = $ledgers->filter(function ($ledger) use ($request) {
                return $ledger->date >= $request->dateFrom && $ledger->date <= $request->dateTo;
            })->values();
        }


        return response()->json(['previousBalance' => $previousBalance, 'ledgers' => $ledgers]);
    }
}
