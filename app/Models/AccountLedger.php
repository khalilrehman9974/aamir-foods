<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountLedger extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = ['invoice_id','account_id','description','debit','credit'];

    protected $hidden = ['created_at','updated_at'];

    public function customer(){
        return $this->belongsTo(CoaDetailAccount::class, 'account_id', 'account_code');
    }

    public function party(){
        return $this->belongsTo(CoaDetailAccount::class, 'account_id', 'account_code');
    }

    public function bank(){
        return $this->belongsTo(Bank::class, 'account_id', 'id');
    }

}
