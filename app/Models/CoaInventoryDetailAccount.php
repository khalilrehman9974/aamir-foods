<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoaInventoryDetailAccount extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $fillable = ['main_head', 'sub_head','sub_sub_head', 'code', 'name', 'image', 'price_tag_id','measurement_type_id','packing_type_id','max_limit','min_limit'];

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
}
