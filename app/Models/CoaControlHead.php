<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoaControlHead extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $fillable = ['main_head','account_code','account_name', 'created_by', 'updated_by'];

    public function getMainAccountHead() {
        return $this->hasOne(CoaMainHead::class, 'account_code', 'main_head');
    }
}
