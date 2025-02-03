<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailAccountPrices extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'coa_detail_account_prices';

    protected $fillable = [
        'coa_detail_account_code', 'inventory_third_level','price_tag_id'
    ];

    public function detailAccountCode(){
        return $this->hasMany(CoaDetailAccount::class,'account_code', 'coa_detail_account_code');
    }

    public function pricetags(){
        return $this->hasOne(PriceTag::class, 'id', 'price_tag_id');
    }

    public function getProducts()
    {
        return $this->hasOne(CoaInventorySubSubHead::class, 'code', 'inventory_third_level');
    }
}
