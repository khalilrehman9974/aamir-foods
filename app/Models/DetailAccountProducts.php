<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailAccountProducts extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'coa_detail_account_products';

    protected $fillable = [
        'detail_account_id', 'product_id','master_third_level', 'master_price_tag','price','discount','scheme'
    ];

    public function detailAccountCode(){
        return $this->hasOne(CoaDetailAccount::class, 'id', 'detail_account_id');
    }

    public function getProducts()
    {
        return $this->hasOne(CoaInventoryDetailAccount::class, 'id', 'product_id');
    }

    public function getCoaFourthLevel()
    {
        return $this->hasOne(CoaSubSubHead::class, 'id', 'master_third_level');
    }

    public function priceTag()
    {
        return $this->hasOne(PriceTag::class, 'id', 'master_price_tag');
    }
}
