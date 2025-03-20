<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockLedger extends Model
{
    protected $guarded = ['id'];
    use SoftDeletes;

    protected $fillable = ['invoice_id','document_no','product_id','party_title', 'date','stock_in_bags', 'stock_in_weight','stock_in_quantity'
    ,'stock_out_bags','stock_out_weight','stock_out_quantity','rate','created_by','updated_by'];
}

