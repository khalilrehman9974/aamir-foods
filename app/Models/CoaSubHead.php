<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoaSubHead extends Model
{
    use HasFactory;

    protected $fillable = ['main_head','control_head','account_code','account_name'];

    public function getMainHead()
    {
        return $this->hasOne(CoaMainHead::class, 'account_code', 'main_head');
    }

    public function getControlHead()
    {
        return $this->hasOne(CoaControlHead::class, 'account_code', 'control_head');
    }
}
