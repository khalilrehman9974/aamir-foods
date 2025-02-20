<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnMaster extends Model
{
    protected $guarded = ['id'];
    protected $table = 'purchase_return_masters';

    protected $fillable = ['purchase_invoice_no','purchase_order_no','date', 'party_id','supplier_bill_no','transporter_id','business_id','f_year_id',
    'remarks','total_quantity','tax','net_amount','gross_bill','carriage','unloaded_by'];


    public function party(){
        return $this->hasOne(CoaDetailAccount::class, 'account_code', 'party_id');
    }

    public function transporter(){
        return $this->hasOne(Transporter::class, 'id', 'transporter_id');
    }
}
