<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoaSubSubHead extends Model
{
    protected $fillable = ['main_head','control_head', 'sub_head','account_code','account_name'];

    public function getMainHead()
    {
        return $this->hasOne(CoaMainHead::class, 'id', 'main_head');
    }

    public function getControlHead()
    {
        return $this->hasOne(CoaControlHead::class, 'id', 'control_head');
    }

    public function getSubHead()
    {
        return $this->hasOne(CoaSubHead::class, 'id', 'sub_head');
    }
}
