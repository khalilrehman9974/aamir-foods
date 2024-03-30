<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnMaster extends Model
{
    protected $guarded = ['id'];
    protected $table = 'purchase_return_masters';

    protected $fillable = ['grn_no','date', 'type_id','party_id','bill_no','transporter_id','business_id','f_year_id',
    'remarks','total_amount','fair','carriage_inward'];

    public function type_id(){
        return $this->hasOne(SalePurchaseType::class);
    }

    public function party_id(){
        return $this->hasOne(CoaDetailAccount::class, 'id', 'party_id');
    }

}
