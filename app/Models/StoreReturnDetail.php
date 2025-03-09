<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreReturnDetail extends Model
{
    use SoftDeletes;
    protected $table = 'store_return_details';

    protected $fillable = ['store_return_master_id','product_id','packing_type','measurement_type','remarks','total_qty','avg_weight','bags','size', 'created_by','updated_by'];

    public function storeReturnMaster(){
        return $this->hasMany(StoreReturnMaster::class, 'id', 'store_return_master_id');
    }

}
