<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoaInventoryDetailAccount extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $fillable = ['main_head', 'sub_head', 'code', 'name', 'image'];

    public function getMainHead()
    {
        return $this->hasOne(CoaInventoryMainHead::class, 'code', 'main_head');
    }

    public function getSubHead()
    {
        return $this->hasOne(CoaInventorySubHead::class, 'code', 'sub_head');
    }
}
