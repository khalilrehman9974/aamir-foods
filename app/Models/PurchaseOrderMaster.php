<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderMaster extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'purchase_order_masters';

    protected $fillable = ['Name','company_name', 'date','address','remarks','grand_total','created_by','updated_by'];

    
}
