<?php

namespace App\Models;

use App\Models\PurchaseOrderMaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderDetail extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'purchase_order_details';

    protected $fillable = ['purchase_order_master_id','product_id', 'size','packing_type','measurement_type','quantity','price','amount','detail_remarks'];

    public function purchase_order_master_id(){
        return $this->belongsTo(PurchaseOrderMaster::class);
    }

    public function product_id(){
        return $this->belongsTo(CoaInventoryDetailAccount::class);
    }
}
