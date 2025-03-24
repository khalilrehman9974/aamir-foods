<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BPVDetails extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'bank_payment_voucher_details';
    protected $fillable = ['voucher_master_id','account_id', 'bank_id','description', 'amount'];

    public function bpvMaster(){
        return $this->hasMany(BankPaymentVoucher::class,'id', 'voucher_master_id');
    }

    public function party(){
        return $this->hasOne(CoaDetailAccount::class,'id', 'account_id');
    }

    public function bank(){
        return $this->hasOne(CoaDetailAccount::class,'id', 'bank_id');
    }
}
