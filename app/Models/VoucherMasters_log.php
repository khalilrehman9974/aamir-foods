<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherMasterlog extends Model
{
    protected $fillable = ['date', 'f_year_id', 'total_amount', 'vr_type_id', 'business_id', 'created_by', 'updated_by'];
}
