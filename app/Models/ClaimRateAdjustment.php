<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimRateAdjustment extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'claim_and_rate_adjustment_master';
    protected $fillable = ['date','party_id','business_id','f_year_id','saleman','sector','area','delivered_to','driver_name','transporter','bilty_no','total_boray','total_carton',
    'remarks','gross_amount','created_by','updated_by'];


    public function party(){
        return $this->hasOne(CoaDetailAccount::class, 'account_code', 'party_id');
    }


    public function SaleMan(){
        return $this->hasOne(SaleMan::class, 'id', 'saleman');
    }
}
