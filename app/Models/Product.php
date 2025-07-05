<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Product extends Model
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

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id')->select('id', 'name')->withTrashed();
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')->select('id', 'name')->withTrashed();
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id')->select('id', 'name')->withTrashed();
    }


    //stock
    public static function stock($request)
    {
        $branchId = session('branch')->id;
        $request = (object)$request;
        $clauses = "";
        if (!empty($request->productId)) {
            $clauses .= " and p.id = '$request->productId'";
        }
        if (!empty($request->categoryId)) {
            $clauses .= " and p.category_id = '$request->categoryId'";
        }
        if (!empty($request->brandId)) {
            $clauses .= " and p.brand_id = '$request->brandId'";
        }
        file_put_contents(__DIR__ . "/log.text", json_encode($clauses));

        $query = DB::select("select p.id, p.code, p.name, p.purchase_rate, u.name as unit_name,
                            (select ifnull(sum(pd.quantity), 0) from purchase_details pd
                            where pd.product_id = p.id) as purchase_quantity,
                            (select ifnull(sum(sd.quantity), 0) from sale_details sd
                            where sd.product_id = p.id) as sale_quantity,
                            (select purchase_quantity - sale_quantity) as stock,
                            (select stock * p.purchase_rate) as stock_value
                            from products p
                            left join units u on u.id = p.unit_id
                            where p.status = 'a'
                            $clauses");


        return $query;
    }
}
