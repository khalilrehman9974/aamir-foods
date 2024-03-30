<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleOrderDetail extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'sale_order_details';

    protected $fillable = ['sale_order_master_id','product_id', 'quantity','unit','total_unit','rate','amount','created_by','updated_by'];

    public function sale_order_master_id(){
        return $this->belongsTo(SaleOrder::class);
    }
}
