<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoaDetailHead extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $fillable = ['main_head','account_code','account_name'];
}
