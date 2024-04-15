<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnMaster extends Model
{
    protected $guarded = ['id'];
    protected $table = 'purchase_return_masters';

    protected $fillable = ['grn_no','date', 'type','party_id','bill_no','transporter_id','business_id','f_year_id',
    'remarks','total_amount','fair','carriage_inward'];


    public function party(){
        return $this->hasOne(CoaDetailAccount::class, 'id', 'party_id');
    }

}
