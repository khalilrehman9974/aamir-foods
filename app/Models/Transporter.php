<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transporter extends Model
{
    use SoftDeletes;

    const PER_PAGE = 10;
    protected $fillable = ['name', 'mobile_no', 'whatsapp_no', 'mailing_address', 'address', 'reference', 'remarks', 'created_by', 'updated_by'];

}
