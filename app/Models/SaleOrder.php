<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleOrder extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'sale_order_masters';

    protected $fillable = ['date','party_id','bilty_no','deliverd_to','saleman_id','transporter_id','business_id','f_year_id',
    'remarks','total_amount','freight','scheme','commission','created_by','updated_by'];


    public function party(){
        return $this->hasOne(CoaDetailAccount::class, 'account_code', 'party_id');
    }

    public function saleman(){
        return $this->hasOne(SaleMan::class, 'id', 'saleman_id');
    }

    public function transporter(){
        return $this->hasOne(Transporter::class, 'id', 'transporter_id');
    }
}
