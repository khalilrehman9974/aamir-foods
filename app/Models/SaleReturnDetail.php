<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturnDetail extends Model
{
    protected $guarded = ['id'];
    protected $table = 'sale_return_details';

    protected $fillable = ['sale_master_id','product_id', 'quantity','unit','total_unit','rate','amount','created_by','updated_by'];

    public function sale_master_id(){
        return $this->belongsTo(SaleMaster::class);
    }
}
