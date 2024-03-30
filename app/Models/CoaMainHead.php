<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoaMainHead extends Model
{
    use SoftDeletes;
    const PER_PAGE = 10;
    protected  $guarded = ['id'];
    protected $fillable = ['account_code', 'account_name', 'created_by', 'updated_by'];
}
