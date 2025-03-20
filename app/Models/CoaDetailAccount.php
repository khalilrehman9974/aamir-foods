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

    protected $fillable = [
        'main_head', 'control_head', 'sub_head', 'sub_sub_head', 'account_code', 'account_name',
        'saleMan_id','commision', 'mode', 'status'
    ];

    public function getMainHead()
    {
        return $this->hasOne(CoaMainHead::class, 'id', 'main_head');
    }

    public function saleOrder(){
        return $this->belongsTo(SaleOrder::class);
    }

    public function getControlHead()
    {
        return $this->hasOne(CoaControlHead::class, 'id', 'control_head');
    }

    public function getSubHead()
    {
        return $this->hasOne(CoaSubHead::class, 'id', 'sub_head');
    }

    public function getSubSubHead()
    {
        return $this->hasOne(CoaSubSubHead::class, 'id', 'sub_sub_head');
    }



    public function SaleMan()
    {
        return $this->hasOne(SaleMan::class,'id', 'saleMan_id');
    }

}
