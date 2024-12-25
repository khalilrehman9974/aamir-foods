<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturnDetail extends Model
{
    protected $guarded = ['id'];
    protected $table = 'sale_return_details';

    protected $fillable = ['sale_return_master_id','product_id','packing_type','measurement_type', 'quantity','dzns','total_dzns','rate','amount','created_by','updated_by'];

    public function SaleMasters(){
        return $this->belongsTo(SaleReturnMaster::class);
    }
}
