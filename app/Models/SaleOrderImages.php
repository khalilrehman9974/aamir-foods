<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleOrderImages extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'sale_order_images';

    protected $fillable = ['sale_order_id', 'images'];
}
