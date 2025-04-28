<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseReturnDetail extends Model
{
    use HasFactory, SoftDeletes;
    
    public $timestamps = false;

    protected $guarded = ['id'];
}
