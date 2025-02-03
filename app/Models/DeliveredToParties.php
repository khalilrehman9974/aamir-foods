<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveredToParties extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'delivered_to_parties';

    protected $fillable = [
        'detail_account_id', 'party_name','credit_limit','business_id','f_year_id',
        'saleMan_id','commision', 'mode', 'status','address','email','cnic','contact_no_1','contact_no_2','opening_balance'
    ];


    public function Party()
    {
        return $this->hasOne(CoaDetailAccount::class,'account_code', 'detail_account_id');
    }

    public function SaleMan()
    {
        return $this->hasOne(SaleMan::class,'id', 'saleMan_id');
    }
}
