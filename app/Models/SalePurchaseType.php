<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePurchaseType extends Model
{
    protected $table = 'sale_purchase_type';
    protected $fillable = ['name'];

}
