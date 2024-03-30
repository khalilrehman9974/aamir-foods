<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Distributer extends Model
{
    use SoftDeletes;
    const PER_PAGE = 10;
    protected $fillable = ['name', 'city', 'contact_number', 'address', 'contact_person', 'remarks', 'created_by', 'updated_by'];
}
