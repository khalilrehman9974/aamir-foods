<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VoucherMaster extends Model
{
    protected $fillable = ['date', 'f_year_id', 'total_amount', 'vr_type_id', 'business_id', 'created_by', 'updated_by'];

    public function voucherDetails()
    {
        return $this->hasMany(VoucherDetail::class);
    }

    public function f_year_id()
    {
        return $this->hasOne(FinancialYear::class, 'id', 'f_year_id');
    }

    public function business_id()
    {
        return $this->hasOne(Business::class, 'id', 'business_id');
    }


}
