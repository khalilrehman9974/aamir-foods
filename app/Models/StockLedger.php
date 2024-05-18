<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLedger extends Model
{
    protected $guarded = ['id'];

    protected $fillable = ['invoice_id', 'product_id', 'debit', 'credit', 'transaction_type'];
}
