<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoaDetAccountDetail extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $fillable = ['det_account_code','address','email','cnic','contact_no_1','contact_no_2','opening_balance','credit_limit'];
}
