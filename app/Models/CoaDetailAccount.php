<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoaDetailAccount extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'detail_accounts';

    protected $fillable = ['main_head','control_head', 'sub_head', 'sub_sub_head','account_code','account_name'];

    public function getMainHead()
    {
        return $this->hasOne(CoaMainHead::class, 'account_code', 'main_head');
    }

    public function getControlHead()
    {
        return $this->hasOne(CoaControlHead::class, 'account_code', 'control_head');
    }

    public function getSubHead()
    {
        return $this->hasOne(CoaSubHead::class, 'account_code', 'sub_head');
    }

    public function getSubSubHead()
    {
        return $this->hasOne(CoaSubSubHead::class, 'account_code', 'sub_sub_head');
    }
}
