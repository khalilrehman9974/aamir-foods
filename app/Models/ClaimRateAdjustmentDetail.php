<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClaimRateAdjustmentDetail extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'claim_and_rate_adjustment_detail';
    protected $fillable = ['master_id','product_id', 'packing_type','measurement_type', 'quantity','dzn','total_dzn','rate','amount','created_by','updated_by'];

    public function sale_order_master(){
        return $this->hasMany(ClaimRateAdjustment::class,'id', 'master_id');
    }
}
