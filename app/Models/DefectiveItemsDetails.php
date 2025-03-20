<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefectiveItemsDetails extends Model
{
    protected $guarded = ['id'];
    protected $table = 'defective_items_details';

    protected $fillable = ['master_id','from_department','product_id','packing_type','measurement_type','size','bags',
    'avg_weight','total_quantity','remarks'];


}
