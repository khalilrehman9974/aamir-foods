<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleMan extends Model
{
    use SoftDeletes;
    protected $table = 'sale_Mans';
    const PER_PAGE = 2;
    protected $fillable = ['name', 'city', 'contact_number', 'address', 'contact_person', 'remarks', 'created_by', 'updated_by'];

}
