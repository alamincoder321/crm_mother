<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountHead extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $guarded = ['id'];

    public function adUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->select('id', 'name', 'username')->withTrashed();
    }
    public function upUser()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id')->select('id', 'name', 'username')->withTrashed();
    }
    public function deUser()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id')->select('id', 'name', 'username')->withTrashed();
    }


    // cash balance
    public static function getCashBalance($request, $date = null)
    {
        $request = (object)$request;
        $branchId = !empty($request->branchId) ? $request->branchId : session('branch')->id;

        $query = "select
                /* Received */
                (select ifnull(sum(sm.cashPaid), 0) from sales sm
                where sm.status = 'a'
                and sm.cashPaid > 0
                " . ($date == null ? "" : " and sm.date <= '$date'") . "
                " . ($branchId == null ? "" : " and sm.branch_id = '$branchId'") . ") as receive_sale,

                (select ifnull(sum(ccp.amount), 0) from receives ccp
                where ccp.status = 'a'
                and ccp.type = 'customer'
                and ccp.payment_method = 'cash'
                " . ($date == null ? "" : " and ccp.date <= '$date'") . "
                " . ($branchId == null ? "" : " and ccp.branch_id = '$branchId'") . ") as receive_customer,
                
                (select ifnull(sum(scp.amount), 0) from receives scp
                where scp.status = 'a'
                and scp.type = 'supplier'
                and scp.payment_method = 'cash'
                " . ($date == null ? "" : " and scp.date <= '$date'") . "
                " . ($branchId == null ? "" : " and scp.branch_id = '$branchId'") . ") as receive_supplier,
                
                (select ifnull(sum(ti.amount), 0) from transactions ti
                where ti.status = 'a'
                and ti.type = 'income'
                " . ($date == null ? "" : " and ti.date <= '$date'") . "
                " . ($branchId == null ? "" : " and ti.branch_id = '$branchId'") . ") as income,
                
                (select ifnull(sum(bt.amount), 0) from bank_transactions bt
                where bt.status = 'a'
                and bt.type = 'debit'
                " . ($date == null ? "" : " and bt.date <= '$date'") . "
                " . ($branchId == null ? "" : " and bt.branch_id = '$branchId'") . ") as bank_withdraw,
                
                (select ifnull(sum(pr.total), 0) from purchase_returns pr
                where pr.status = 'a'
                and pr.supplier_id is null
                " . ($date == null ? "" : " and pr.date <= '$date'") . "
                " . ($branchId == null ? "" : " and pr.branch_id = '$branchId'") . ") as purchase_return_amount,
                
                (select ifnull(sum(dm.total), 0) from damages dm
                where dm.status = 'a'
                and dm.supplier_id is null
                " . ($date == null ? "" : " and dm.date <= '$date'") . "
                " . ($branchId == null ? "" : " and dm.branch_id = '$branchId'") . ") as damage_amount,
                
                /* Payment */
                
                (select ifnull(sum(pm.paid), 0) from purchases pm
                where pm.status = 'a'
                " . ($date == null ? "" : " and pm.date <= '$date'") . "
                " . ($branchId == null ? "" : " and pm.branch_id = '$branchId'") . ") as purchase_paid,
                    
                (select ifnull(sum(ccp.amount), 0) from payments ccp
                where ccp.status = 'a'
                and ccp.type = 'customer'
                and ccp.payment_method = 'cash'
                " . ($date == null ? "" : " and ccp.date <= '$date'") . "
                " . ($branchId == null ? "" : " and ccp.branch_id = '$branchId'") . ") as payment_customer,
                
                (select ifnull(sum(scp.amount), 0) from payments scp
                where scp.status = 'a'
                and scp.type = 'supplier'
                and scp.payment_method = 'cash'
                " . ($date == null ? "" : " and scp.date <= '$date'") . "
                " . ($branchId == null ? "" : " and scp.branch_id = '$branchId'") . ") as payment_supplier,
                
                (select ifnull(sum(te.amount), 0) from transactions te
                where te.status = 'a'
                and te.type = 'expense'
                " . ($date == null ? "" : " and te.date <= '$date'") . "
                " . ($branchId == null ? "" : " and te.branch_id = '$branchId'") . ") as expense,
                
                (select ifnull(sum(bt.amount), 0) from bank_transactions bt
                where bt.status = 'a'
                and bt.type = 'credit'
                " . ($date == null ? "" : " and bt.date <= '$date'") . "
                " . ($branchId == null ? "" : " and bt.branch_id = '$branchId'") . ") as bank_deposit,
                
                (select ifnull(sum(emp.amount), 0) from salary_masters emp
                where emp.status = 'a'
                " . ($date == null ? "" : " and emp.date <= '$date'") . "
                " . ($branchId == null ? "" : " and emp.branch_id = '$branchId'") . ") as salary_payment,

                (select ifnull(sum(sr.total), 0) from sale_returns sr
                where sr.status = 'a'
                and sr.customer_id is null
                " . ($date == null ? "" : " and sr.date <= '$date'") . "
                " . ($branchId == null ? "" : " and sr.branch_id = '$branchId'") . ") as sale_return_amount,
                
                (select receive_sale + purchase_return_amount + damage_amount + receive_customer + receive_supplier + income + bank_withdraw) as total_in_amount,
                (select purchase_paid + sale_return_amount + payment_customer + payment_supplier + expense + bank_deposit + salary_payment) as total_out_amount,
                (select total_in_amount - total_out_amount) as cashbalance";

        return DB::select($query)[0];
    }

    // get other expense income
    public static function getOtherExpenseIncome($request)
    {
        $request = (object)$request;
        $branchId = !empty($request->branchId) ? $request->branchId : session('branch')->id;

        $query = "select
                (select ifnull(sum(ti.amount), 0) from transactions ti
                 where ti.status = 'a'
                 and ti.type = 'income'
                " . (empty($request->dateFrom) && empty($request->dateTo) ? "" : " and ti.date between '$request->dateFrom' and '$request->dateTo'") . "
                " . ($branchId == null ? "" : " and ti.branch_id = '$branchId'") . ") as income,
                
                (select ifnull(sum(te.amount), 0) from transactions te
                 where te.status = 'a'
                 and te.type = 'expense'
                " . (empty($request->dateFrom) && empty($request->dateTo) ? "" : " and te.date between '$request->dateFrom' and '$request->dateTo'") . "
                " . ($branchId == null ? "" : " and te.branch_id = '$branchId'") . ") as expense,
                
                (select ifnull(sum(pm.vat), 0) from purchases pm
                 where pm.status = 'a'
                " . (empty($request->dateFrom) && empty($request->dateTo) ? "" : " and pm.date between '$request->dateFrom' and '$request->dateTo'") . "
                " . ($branchId == null ? "" : " and pm.branch_id = '$branchId'") . ") as purchase_vat,

                (select ifnull(sum(pm.discount), 0) from purchases pm
                 where pm.status = 'a'
                " . (empty($request->dateFrom) && empty($request->dateTo) ? "" : " and pm.date between '$request->dateFrom' and '$request->dateTo'") . "
                " . ($branchId == null ? "" : " and pm.branch_id = '$branchId'") . ") as purchase_discount,

                (select ifnull(sum(pm.transport_cost), 0) from purchases pm
                 where pm.status = 'a'
                " . (empty($request->dateFrom) && empty($request->dateTo) ? "" : " and pm.date between '$request->dateFrom' and '$request->dateTo'") . "
                " . ($branchId == null ? "" : " and pm.branch_id = '$branchId'") . ") as purchase_transport_cost,
                
                (select ifnull(sum(sr.total), 0) from sale_returns sr
                 where sr.status = 'a'
                " . (empty($request->dateFrom) && empty($request->dateTo) ? "" : " and sr.date between '$request->dateFrom' and '$request->dateTo'") . "
                " . ($branchId == null ? "" : " and sr.branch_id = '$branchId'") . ") as sale_return_amount,
                
                (select ifnull(sum(sme.amount), 0) from salary_masters sme
                 where sme.status = 'a'
                " . (empty($request->dateFrom) && empty($request->dateTo) ? "" : " and sme.date between '$request->dateFrom' and '$request->dateTo'") . "
                " . ($branchId == null ? "" : " and sme.branch_id = '$branchId'") . ") as salary_payment
                ";

        return DB::select($query)[0];
    }
}
