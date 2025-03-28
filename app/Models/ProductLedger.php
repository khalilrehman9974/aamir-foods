<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductLedger extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'product_account_ledgers';

    protected $fillable = ['date','invoice_id','product_id','document_number','rate','bilty_no',
    'transporter_id','total_quantity','measurementType','bags','description','debit','credit'];

    protected $hidden = ['created_at','updated_at'];


    public function product(){
        return $this->belongsTo(CoaInventoryDetailAccount::class, 'id', 'product_id');
    }
}
