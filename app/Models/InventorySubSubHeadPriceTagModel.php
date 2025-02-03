<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventorySubSubHeadPriceTagModel extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'coa_inventory_sub_sub_head_price_tag';

    protected $fillable = [
        'sub_sub_head_id', 'priceTag'
    ];

    public function detailAccountCode(){
        return $this->hasMany(CoaInventorySubSubHead::class,'id', 'sub_sub_head_id');
    }

    // public function pricetags(){
    //     return $this->hasMany(PriceTag::class, 'id', 'priceTag');
    // }

    public function options()
    {
        return $this->belongsToMany(PriceTag::class);
    }

}
