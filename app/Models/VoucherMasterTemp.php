<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherMasterTemp extends Model
{
    protected $table = 'voucher_master_temp';
    protected $fillable = ['date', 'f_year_id', 'total_amount', 'vr_type_id', 'business_id', 'created_by', 'updated_by'];

    public function f_year_id()
    {
        return $this->hasOne(FinancialYear::class, 'id', 'f_year_id');
    }

    public function business_id()
    {
        return $this->hasOne(Business::class, 'id', 'business_id');
    }
}
