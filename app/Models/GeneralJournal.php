<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralJournal extends Model
{
    use SoftDeletes;
    protected  $guarded = ['id'];
    protected $table = 'general_journals';

    protected $fillable = [
        'date',
        'f_year_id',
        'invoice_id',
        'narration',
        'business_id',
        'description',
        'document_number',
        'debit',
        'credit'
    ];
}
