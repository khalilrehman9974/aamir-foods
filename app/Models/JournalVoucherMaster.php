<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalVoucherMaster extends Model
{
    protected  $guarded = ['id'];
    protected $table = 'journal_voucher_masters';

    protected $fillable = ['date', 'f_year_id', 'debit_amount','credit_amount', 'business_id', 'created_by', 'updated_by'];

    public function voucherDetails()
    {
        return $this->hasMany(JournalVoucherDetail::class);
    }
}
