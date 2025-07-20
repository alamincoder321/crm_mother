<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bank extends Model
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
    public static function getBankBalance($request, $date = null)
    {
        $request = (object)$request;
        $branchId = !empty($request->branchId) ? $request->branchId : session('branch')->id;
        $clauses = "";
        if(!empty($request->bankId)){
            $clauses .= " and ba.id = '$request->bankId'";
        }

        $query = "select ba.id, ba.name, ba.number, ba.type, ba.bank_name,
                    (select ifnull(sum(sb.amount), 0) from sale_banks sb
                    join sales sm on sm.id = sb.id
                    where sb.status = 'a'
                    and sb.bank_id = ba.id
                    " . ($date == null ? "" : " and sm.date <= '$date'") . "
                    " . ($branchId == null ? "" : " and sm.branch_id = '$branchId'") . ") as receive_sale,
                    
                    (select ifnull(sum(bt.amount), 0) from bank_transactions bt
                    where bt.status = 'a'
                    and bt.bank_id = ba.id
                    and bt.type = 'debit'
                    " . ($date == null ? "" : " and bt.date <= '$date'") . "
                    " . ($branchId == null ? "" : " and bt.branch_id = '$branchId'") . ") as total_debit,
                    
                    (select ifnull(sum(bt.amount), 0) from bank_transactions bt
                    where bt.status = 'a'
                    and bt.bank_id = ba.id
                    and bt.type = 'credit'
                    " . ($date == null ? "" : " and bt.date <= '$date'") . "
                    " . ($branchId == null ? "" : " and bt.branch_id = '$branchId'") . ") as total_credit,
                    
                    (select ifnull(sum(cpp.amount), 0) from payments cpp
                    where cpp.status = 'a'
                    and cpp.type = 'customer'
                    and cpp.bank_id = ba.id
                    " . ($date == null ? "" : " and cpp.date <= '$date'") . "
                    " . ($branchId == null ? "" : " and cpp.branch_id = '$branchId'") . ") as total_paid_customer,

                    (select ifnull(sum(cpr.amount), 0) from receives cpr
                    where cpr.status = 'a'
                    and cpr.type = 'customer'
                    and cpr.bank_id = ba.id
                    " . ($date == null ? "" : " and cpr.date <= '$date'") . "
                    " . ($branchId == null ? "" : " and cpr.branch_id = '$branchId'") . ") as total_receive_customer,

                    (select ifnull(sum(spp.amount), 0) from payments spp
                    where spp.status = 'a'
                    and spp.type = 'supplier'
                    and spp.bank_id = ba.id
                    " . ($date == null ? "" : " and spp.date <= '$date'") . "
                    " . ($branchId == null ? "" : " and spp.branch_id = '$branchId'") . ") as total_paid_supplier,
                    
                    (select ifnull(sum(spr.amount), 0) from receives spr
                    where spr.status = 'a'
                    and spr.type = 'customer'
                    and spr.bank_id = ba.id
                    " . ($date == null ? "" : " and spr.date <= '$date'") . "
                    " . ($branchId == null ? "" : " and spr.branch_id = '$branchId'") . ") as total_receive_supplier,

                    (select ba.balance + receive_sale + total_credit + total_receive_customer + total_receive_supplier) as total_in_amount,
                    (select total_debit + total_paid_supplier + total_paid_customer) as total_out_amount,

                    (select total_in_amount - total_out_amount) as currentbalance
                    from banks ba
                    where ba.status = 'a'
                    " . ($branchId == null ? "" : " and ba.branch_id = '$branchId'") . "
                    $clauses";

        return DB::select($query);
    }
}
