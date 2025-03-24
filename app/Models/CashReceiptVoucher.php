<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashReceiptVoucher extends Model
{
    protected  $guarded = ['id'];
    protected $table = 'cash_receipt_vouchers';

    protected $fillable = ['date', 'f_year_id', 'total_amount', 'business_id', 'created_by', 'updated_by'];

    public function voucherDetails()
    {
        return $this->hasMany(CRVDetails::class);
    }
}
