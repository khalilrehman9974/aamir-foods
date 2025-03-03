<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleReturnMaster extends Model
{
    protected $guarded = ['id'];
    protected $table = 'sale_return_masters';

    protected $fillable = ['sale_return_number','date','grn_no','sale_invoice_number','party_id','saleman','sector','area','deliverd_to','driver_name','bilty_no',
    'transporter','business_id','f_year_id','remarks','gross_amount','boray_amount','carton_amount','scheme','commission','created_by','updated_by'];


    public function party(){
        return $this->hasOne(CoaDetailAccount::class, 'id', 'party_id');
    }

    public function SaleMan(){
        return $this->hasOne(SaleMan::class, 'id', 'saleman');
    }

}
