<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CRVDetails extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'cash_receipt_voucher_details';
    protected $fillable = ['voucher_master_id','account_id', 'cash_account_id','description', 'amount'];

    public function bpvMaster(){
        return $this->hasMany(BankReceiptVoucher::class,'id', 'voucher_master_id');
    }

    public function party(){
        return $this->hasOne(CoaDetailAccount::class,'id', 'account_id');
    }

    public function CashAccount(){
        return $this->hasOne(CoaDetailAccount::class,'id', 'cash_account_id');
    }
}
