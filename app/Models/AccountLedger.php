<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountLedger extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = ['date','invoice_id','account_id','description','transaction_type','debit','credit','voucher_number'];

    protected $hidden = ['created_at','updated_at'];

    public function customer(){
        return $this->belongsTo(Account::class, 'account_id', 'account_code');
    }

    public function party(){
        return $this->belongsTo(Account::class, 'account_id', 'account_code');
    }

    public function bank(){
        return $this->belongsTo(Bank::class, 'account_id', 'id');
    }

}
