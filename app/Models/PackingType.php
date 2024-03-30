<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackingType extends Model
{
    use SoftDeletes;
    protected  $guarded = ['id'];
    protected $fillable = ['name'];
}
