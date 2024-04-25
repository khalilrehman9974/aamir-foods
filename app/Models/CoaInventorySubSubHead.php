<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoaInventorySubSubHead extends Model
{

    protected $fillable = ['main_head_id','sub_head_id', 'code', 'name'];

    public function getMainHead() {
        return $this->hasOne(CoaInventoryMainHead::class, 'code', 'main_head_id');
    }

    public function getSubHead() {
        return $this->hasOne(CoaInventorySubHead::class, 'id', 'sub_head_id');
    }
}
