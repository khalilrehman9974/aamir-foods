<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherDetailTemp extends Model
{
    protected $guarded =['id'];
    protected $table = 'voucher_detail_temp';
    protected $fillable = ['voucher_master_id', 'account_id','bank_id', 'description',
    'amount', 'created_by', 'updated_by'];

    public function voucher_master()
    {
        return $this->hasMany(VoucherMaster::class, 'id', 'voucher_master_id');
    }
}
