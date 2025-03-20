<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefectiveItemsMaster extends Model
{
    protected $guarded = ['id'];
    protected $table = 'defective_items';

    protected $fillable = ['date','entered_by','business_id','f_year_id','created_by','updated_by'];
}
