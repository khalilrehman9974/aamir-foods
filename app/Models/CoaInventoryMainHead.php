<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoaInventoryMainHead extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $fillable = ['code', 'name'];
}
