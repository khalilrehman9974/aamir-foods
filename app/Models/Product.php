<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';

    protected $fillable = [ 'name', 'price', 'sale_price', 'remarks', 'created_by', 'updated_by'];



    protected $guarded = ['id'];
}
