<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderMaster extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'purchase_order_masters';

    protected $fillable = ['date','party_id', 'contact_person','status','remarks','gross_total','tax_amount','shipping_amount',
    'other_amount','total_amount','business_id','f_year_id','created_by','updated_by'];


    public function party(){
        return $this->hasOne(CoaDetailAccount::class, 'account_code', 'party_id');
    }

}
