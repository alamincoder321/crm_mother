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
    public static function getCashBalance($request, $date = null)
    {
        $request = (object)$request;
        $branchId = !empty($request->branchId) ? $request->branchId : session('branch')->id;

        $query = "select ba.id, ba.name, ba.number, ba.type, ba.bank_name, ba.balance,
                    (select ifnull(sum(sb.amount), 0) from sale_banks sb
                    join sales sm on sm.id = sb.id
                    where sb.status = 'a'
                    and sb.bank_id = ba.id
                    and sm.branch_id = 1) as receive_sale,

                    (select ba.balance + receive_sale) as currentbalance
                    from banks ba
                    where ba.status = 'a'";

        return DB::select($query);
    }
}
