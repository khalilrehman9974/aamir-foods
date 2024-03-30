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

    protected $fillable = ['date', 'type_id','party_id','bilty_no','deliverd_to','saleman_id','transporter_id','business_id','f_year_id',
    'remarks','total_amount','freight','scheme','commission'];

    public function type_id(){
        return $this->hasOne(SalePurchaseType::class);
    }

    public function party_id(){
        return $this->hasOne(CoaDetailAccount::class, 'id', 'party_id');
    }

    public function saleman_id(){
        return $this->hasOne(SaleMan::class, 'id', 'saleman_id');
    }

    public function transporter_id(){
        return $this->hasOne(Transporter::class, 'id', 'transporter_id');
    }
}
