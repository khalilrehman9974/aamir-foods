<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $guarded = ['id'];
    protected $table = 'purchase_details';

    protected $fillable = ['purchase_master_id','product_id','packing_type','measurement_type','size','measurementType','bags', 'quantity','price','amount'];

    public function purchase_master_id(){
        return $this->belongsTo(PurchaseMaster::class);
    }
}


