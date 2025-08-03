<?php

namespace App\Http\Controllers;

use App\Models\AccountHead;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountHeadController extends Controller
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
        $accounthead = AccountHead::with('adUser', 'upUser', 'deUser')->where('branch_id', $this->branchId);
        if (!empty($request->type)) {
            $accounthead->where('type', $request->type);
        }
        $accounthead = $accounthead->latest()->get();
        return response()->json($accounthead);
    }

    public function create()
    {
        return view('pages.account.accounthead');
    }

    public function store(Request $request)
    {
        $branchId = $this->branchId;
        $validator = Validator::make($request->all(), [
            'name'     => [
                'required',
                Rule::unique('account_heads')
                    ->where(function ($query) use ($branchId) {
                        $query->where('branch_id', $branchId);
                    })
                    ->whereNull('deleted_at'),
            ],
        ]);
        if ($validator->fails()) return send_error("Validation Error", $validator->errors(), 422);
        try {
            $check = AccountHead::where('name', $request->name)->withTrashed()->first();
            if (!empty($check) && $check->deleted_at != NULL) {
                $check->status = 'a';
                $check->deleted_by = NULL;
                $check->deleted_at = NULL;
                $check->update();
            } else {
                $data = new AccountHead();
                $dataKey = $request->except('id');
                foreach ($dataKey as $key => $value) {
                    $data[$key] = $value;
                }
                $data->created_by = $this->userId;
                $data->branch_id  = $this->branchId;
                $data->ipAddress  = request()->ip();
                $data->save();
            }

            return response()->json(['status' => true, 'message' => "Account Head has created successfully"]);
        } catch (\Throwable $th) {
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function update(Request $request)
    {
        $branchId = $this->branchId;
        $validator = Validator::make($request->all(), [
            'name'     => [
                'required',
                Rule::unique('account_heads')
                    ->ignore($request->id)
                    ->where(function ($query) use ($branchId) {
                        $query->where('branch_id', $branchId);
                    })
                    ->whereNull('deleted_at'),
            ],
        ]);
        if ($validator->fails()) return send_error("Validation Error", $validator->errors(), 422);
        try {
            $data = AccountHead::find($request->id);
            $dataKey = $request->except('id');
            foreach ($dataKey as $key => $value) {
                $data[$key] = $value;
            }
            $data->updated_at = Carbon::now();
            $data->updated_by = $this->userId;
            $data->ipAddress = request()->ip();
            $data->branch_id = $this->branchId;
            $data->update();

            return response()->json(['status' => true, 'message' => "Account Head has updated successfully"]);
        } catch (\Throwable $th) {
            return send_error('Something went worng', $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = AccountHead::find($request->id);
            $data->deleted_by = $this->userId;
            $data->status = 'd';
            $data->ipAddress = request()->ip();
            $data->update();

            $data->delete();
            return response()->json(['status' => true, 'message' => "Account Head has deleted successfully"]);
        } catch (\Throwable $th) {
            return send_error("Something went wrong", $th->getMessage());
        }
    }

    public function cashLedger()
    {
        return view('pages.report.cashLedger');
    }

    public function getCashLedger(Request $request)
    {
        $branchId = $this->branchId;
        $query = "
                /*================= In Amount =======================*/
                select
                'a' as sequence,
                sm.id,
                sm.date,
                concat('Sale Invoice - ', sm.invoice) as description,
                sm.cashPaid as in_amount,
                0 as out_amount,
                0 as balance
                from sales sm
                where sm.status = 'a'
                " . ($branchId == null ? "" : " and sm.branch_id = '$branchId'") . "

                UNION
                select
                'b' as sequence,
                cpr.id,
                cpr.date,
                concat('Customer Payment - ', cpr.invoice) as description,
                cpr.amount as in_amount,
                0 as out_amount,
                0 as balance
                from receives cpr
                where cpr.status = 'a'
                and cpr.type = 'customer'
                and cpr.payment_method = 'cash'
                " . ($branchId == null ? "" : " and cpr.branch_id = '$branchId'") . "

                UNION
                select
                'c' as sequence,
                bt.id,
                bt.date,
                concat('Bank Withdraw - ', bt.invoice) as description,
                bt.amount as in_amount,
                0 as out_amount,
                0 as balance
                from bank_transactions bt
                where bt.status = 'a'
                and bt.type = 'debit'
                " . ($branchId == null ? "" : " and bt.branch_id = '$branchId'") . "

                UNION
                select
                'd' as sequence,
                spr.id,
                spr.date,
                concat('Supplier Receive - ', spr.invoice) as description,
                spr.amount as in_amount,
                0 as out_amount,
                0 as balance
                from receives spr
                where spr.status = 'a'
                and spr.type = 'supplier'
                and spr.payment_method = 'cash'
                " . ($branchId == null ? "" : " and spr.branch_id = '$branchId'") . "
                
                UNION
                select
                'e' as sequence,
                pr.id,
                pr.date,
                concat('Purchase Return Invoice - ', pr.invoice) as description,
                pr.total as in_amount,
                0 as out_amount,
                0 as balance
                from purchase_returns pr
                where pr.status = 'a'
                " . ($branchId == null ? "" : " and pr.branch_id = '$branchId'") . "
                
                UNION
                select
                'f' as sequence,
                inc.id,
                inc.date,
                concat('Income Invoice - ', inc.invoice) as description,
                inc.amount as in_amount,
                0 as out_amount,
                0 as balance
                from transactions inc
                where inc.status = 'a'
                and inc.type = 'income'
                " . ($branchId == null ? "" : " and inc.branch_id = '$branchId'") . "

                /*================= Out Amount =======================*/
                UNION
                select
                'g' as sequence,
                pm.id,
                pm.date,
                concat('Purchase Invoice - ', pm.invoice) as description,
                0 as in_amount,
                pm.paid as out_amount,
                0 as balance
                from purchases pm
                where pm.status = 'a'
                " . ($branchId == null ? "" : " and pm.branch_id = '$branchId'") . "

                UNION
                select
                'h' as sequence,
                spp.id,
                spp.date,
                concat('Supplier Payment - ', spp.invoice) as description,
                0 as in_amount,
                spp.amount as out_amount,
                0 as balance
                from payments spp
                where spp.status = 'a'
                and spp.type = 'supplier'
                and spp.payment_method = 'cash'
                " . ($branchId == null ? "" : " and spp.branch_id = '$branchId'") . "
                
                UNION
                select
                'h' as sequence,
                bt.id,
                bt.date,
                concat('Bank Deposit - ', bt.invoice) as description,
                0 as in_amount,
                bt.amount as out_amount,
                0 as balance
                from bank_transactions bt
                where bt.status = 'a'
                and bt.type = 'credit'
                " . ($branchId == null ? "" : " and bt.branch_id = '$branchId'") . "

                UNION
                select
                'i' as sequence,
                cpp.id,
                cpp.date,
                concat('Customer Payment - ', cpp.invoice) as description,
                cpp.amount as in_amount,
                0 as out_amount,
                0 as balance
                from payments cpp
                where cpp.status = 'a'
                and cpp.type = 'customer'
                " . ($branchId == null ? "" : " and cpp.branch_id = '$branchId'") . "

                UNION
                select
                'j' as sequence,
                exp.id,
                exp.date,
                concat('Income Invoice - ', exp.invoice) as description,
                0 as in_amount,
                exp.amount as out_amount,
                0 as balance
                from transactions exp
                where exp.status = 'a'
                and exp.type = 'expense'
                " . ($branchId == null ? "" : " and exp.branch_id = '$branchId'") . "
                
                UNION
                select
                'k' as sequence,
                emp.id,
                emp.date,
                concat('Employee Payment - ', emp.invoice) as description,
                0 as in_amount,
                emp.amount as out_amount,
                0 as balance
                from salary_masters emp
                where emp.status = 'a'
                " . ($branchId == null ? "" : " and emp.branch_id = '$branchId'") . "
                
                order by date, sequence asc";

        $ledgers = DB::select($query);

        $ledgers = collect($ledgers)->map(function ($ledger, $key) use ($ledgers) {
            $lastBalance = $key == 0 ? 0 : $ledgers[$key - 1]->balance;
            $ledger->balance = ($lastBalance + $ledger->in_amount) - $ledger->out_amount;
            return $ledger;
        });

        $previousLedger = collect($ledgers)->filter(function ($ledger) use ($request) {
            return $ledger->date < $request->dateFrom;
        });
        $previousBalance = count($previousLedger) > 0 ? $previousLedger[count($previousLedger) - 1]->balance : 0;

        if (!empty($request->dateFrom) && !empty($request->dateTo)) {
            $ledgers = $ledgers->filter(function ($ledger) use ($request) {
                return $ledger->date >= $request->dateFrom && $ledger->date <= $request->dateTo;
            })->values();
        }


        return response()->json(['previousBalance' => $previousBalance, 'ledgers' => $ledgers]);
    }
}
