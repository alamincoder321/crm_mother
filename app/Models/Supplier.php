<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
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

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id')->select('id', 'name')->withTrashed();
    }


    // supplier due
    public static function supplierDue($request, $date = null)
    {
        $branchId = session('branch')->id;
        $request = (object)$request;
        $clauses = "";
        if (!empty($request->supplierId)) {
            $clauses .= " and s.id = '$request->supplierId'";
        }
        if (!empty($request->areaId)) {
            $clauses .= " and s.area_id = '$request->areaId'";
        }

        $query = "select
                    s.id, s.code, s.name, s.owner, s.phone, s.address,
                    (select ifnull(sum(pm.total), 0) from purchases pm
                    where pm.status = 'a'
                    " . ($date == null ? "" : " and pm.date <= '$date'") . "
                    " . ($branchId == null ? "" : " and pm.branch_id = '$branchId'") . "
                    and pm.supplier_id = s.id) as purchase_total,
                    
                    (select ifnull(sum(pm.paid), 0) from purchases pm
                    where pm.status = 'a'
                    " . ($date == null ? "" : " and pm.date <= '$date'") . "
                    " . ($branchId == null ? "" : " and pm.branch_id = '$branchId'") . "
                    and pm.supplier_id = s.id) as purchase_paid,
                    
                    (select ifnull(sum(cr.amount), 0) from receives cr
                    where cr.status = 'a'
                    and cr.type = 'supplier'
                    " . ($date == null ? "" : " and cr.date <= '$date'") . "
                    " . ($branchId == null ? "" : " and cr.branch_id = '$branchId'") . "
                    and cr.supplier_id = s.id) as received_amount,
                    
                    (select ifnull(sum(cp.amount), 0) from payments cp
                    where cp.status = 'a'
                    and cp.type = 'supplier'
                    " . ($date == null ? "" : " and cp.date <= '$date'") . "
                    " . ($branchId == null ? "" : " and cp.branch_id = '$branchId'") . "
                    and cp.supplier_id = s.id) as payment_amount,
                    
                    (select ifnull(sum(pr.total), 0) from purchase_returns pr
                    where pr.status = 'a'
                    " . ($date == null ? "" : " and pr.date <= '$date'") . "
                    " . ($branchId == null ? "" : " and pr.branch_id = '$branchId'") . "
                    and pr.supplier_id = s.id) as return_amount,
                    
                    (select ifnull(sum(dm.total), 0) from damages dm
                    where dm.status = 'a'
                    " . ($date == null ? "" : " and dm.date <= '$date'") . "
                    " . ($branchId == null ? "" : " and dm.branch_id = '$branchId'") . "
                    and dm.supplier_id = s.id) as damage_amount,
                    
                    (select (s.previous_due + purchase_total + received_amount + return_amount + damage_amount) - (purchase_paid + payment_amount)) as due

                    from suppliers s
                    where s.status = 'a'
                    $clauses
                    " . ($branchId == null ? "" : " and s.branch_id = '$branchId'") . "";

        return DB::select($query);
    }
}
