<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoaInventorySubHead extends Model
{
    use HasFactory;

    protected $fillable = ['main_head', 'code', 'name'];

    public function getMainAccountHead() {
        return $this->hasOne(CoaInventoryMainHead::class, 'code', 'main_head');
    }
}
