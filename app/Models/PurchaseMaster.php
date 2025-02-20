<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseMaster extends Model
{
    protected $guarded = ['id'];
    protected $table = 'purchase_masters';

    protected $fillable = ['grn_no','date','party_id','supplier_bill_no','transporter_id','business_id','f_year_id',
    'remarks','total_quantity','unloaded_by','carriage','gross_bill','tax','net_amount'];

    public function type(){
        return $this->hasOne(SalePurchaseType::class);
    }

    public function party(){
        return $this->hasOne(CoaDetailAccount::class, 'account_code', 'party_id');
    }

    public function transporter(){
        return $this->hasOne(Transporter::class, 'id', 'transporter_id');
    }

}
