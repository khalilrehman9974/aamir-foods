<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherDetailTemp extends Model
{
    protected $guarded =['id'];
    protected $fillable = ['voucher_master_id', 'account_id', 'description',
    'debit', 'credit', 'created_by', 'updated_by'];

    public function voucher_master()
    {
        return $this->hasMany(VoucherMaster::class, 'id', 'voucher_master_id');
    }
}
