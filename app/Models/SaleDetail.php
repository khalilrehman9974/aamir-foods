<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'sale_details';

    protected $fillable = ['sale_master_id','packing_type','measurement_type','product_id', 'quantity','dzns','total_dzns','soQuantity','dispQuantity','discount','rate','amount'];

    public function sale_master_id(){
        return $this->belongsTo(SaleMaster::class);
    }

}
