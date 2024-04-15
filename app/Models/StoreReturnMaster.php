<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreReturnMaster extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'store_return_masters';
    protected $fillable = ['product_id','business_id','f_year_id', 'return_to','return_by','remarks'];

    public function product(){
        return $this->hasOne(CoaInventoryDetailAccount::class, 'code', 'product_id');
    }
}
