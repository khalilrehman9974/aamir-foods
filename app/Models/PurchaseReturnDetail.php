<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnDetail extends Model
{
    protected $guarded = ['id'];
    protected $table = 'purchase_return_details';

    protected $fillable = ['purchase_return_master_id','product_id','packing_type','measurement_type', 'quantity','size','price','amount'];

    public function purchase_return_master_id(){
        return $this->belongsTo(PurchaseReturnMaster::class);
    }
}



