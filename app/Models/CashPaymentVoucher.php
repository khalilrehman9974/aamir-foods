<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashPaymentVoucher extends Model
{
    protected  $guarded = ['id'];
    protected $table = 'cash_payment_vouchers';

    protected $fillable = ['date', 'f_year_id', 'total_amount', 'business_id', 'created_by', 'updated_by'];

    public function voucherDetails()
    {
        return $this->hasMany(CPVDetails::class);
    }
}
