<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoaInventorySubSubHead extends Model
{

    protected $fillable = ['main_head','sub_head', 'code', 'name'];

    public function getMainHead() {
        return $this->hasOne(CoaInventoryMainHead::class, 'code', 'main_head');
    }

    public function getSubHead() {
        return $this->hasOne(CoaInventorySubHead::class, 'code', 'sub_head');
    }
}
