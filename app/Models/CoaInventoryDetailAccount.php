<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoaInventoryDetailAccount extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $fillable = ['main_head', 'sub_head','sub_sub_head', 'code', 'priceTag_id', 'name',
    'remarks','danger_level','opening_stock','stock_rate','use_in', 'image','measurement_type_id','packing_type_id','size','max_limit','min_limit'];

    public function getMainHead()
    {
        return $this->hasOne(CoaInventoryMainHead::class, 'id', 'main_head');
    }

    public function getSubHead()
    {
        return $this->hasOne(CoaInventorySubHead::class, 'id', 'sub_head');
    }

    public function getSubSubHead()
    {
        return $this->hasOne(CoaInventorySubSubHead::class, 'id', 'sub_sub_head');
    }

    public function measurementType()
    {
        return $this->hasOne(MeasurementType::class, 'id', 'measurement_type_id');
    }

    public function packingType()
    {
        return $this->hasOne(PackingType::class, 'id', 'packing_type_id');
    }

    public function priceTag()
    {
        return $this->hasOne(PriceTag::class, 'id', 'priceTag_id');
    }
}
