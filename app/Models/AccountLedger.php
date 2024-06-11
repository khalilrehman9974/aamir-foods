<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountLedger extends Model
{

    protected $guarded = ['id'];

    protected $table = 'account_ledgers';
    protected $fillable = ['account_id','description','debit','credit'];

    protected $hidden = ['created_at','updated_at'];


    public function party(){
        return $this->belongsTo(CoaDetailAccount::class, 'account_id', 'account_code');
    }

    // public function bank(){
    //     return $this->belongsTo(Bank::class, 'account_id', 'id');
    // }

}
