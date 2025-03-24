<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankReceiptVoucher extends Model
{
    protected  $guarded = ['id'];
    protected $table = 'bank_receipt_vouchers';

    protected $fillable = ['date', 'f_year_id', 'total_amount', 'business_id', 'created_by', 'updated_by'];

    public function voucherDetails()
    {
        return $this->hasMany(BRVDetails::class);
    }
}
