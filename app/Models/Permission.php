<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    const PER_PAGE = 10;

    protected $guarded = ['id'];
}
