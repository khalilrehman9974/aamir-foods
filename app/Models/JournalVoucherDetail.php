<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalVoucherDetail extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'journal_voucher_details';
    protected $fillable = ['voucher_master_id','debit_account', 'credit_account','description', 'debit','credit'];

    public function JvMaster(){
        return $this->hasMany(JournalVoucherMaster::class,'id', 'voucher_master_id');
    }

    public function DebitParty(){
        return $this->hasOne(CoaDetailAccount::class,'id', 'debit_account');
    }

    public function CreditParty(){
        return $this->hasOne(CoaDetailAccount::class,'id', 'credit_account');
    }
}
