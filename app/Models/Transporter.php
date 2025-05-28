<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transporter extends Model
{
    use SoftDeletes;

    const PER_PAGE = 10;
    protected $fillable = ['name', 'city', 'contact_number' ,'contact_person',  'address', 'remarks', 'created_by', 'updated_by'];

}
