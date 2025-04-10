<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountLedger extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    use SoftDeletes;

    protected $table = 'account_ledgers';

    protected $fillable = ['date','invoice_id','party_id','document_number','rate','bilty_no',
    'transporter_id','total_quantity','measurementType','bags','description','debit','credit'];

    protected $hidden = ['created_at','updated_at'];


    public function party(){
        return $this->belongsTo(CoaDetailAccount::class, 'account_id', 'account_code');
    }


}
