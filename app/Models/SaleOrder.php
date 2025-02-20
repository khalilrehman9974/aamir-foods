<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleOrder extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'sale_order_masters';

    protected $fillable = ['date','party_id','business_id','f_year_id','saleman','belt','area','delivered_to','status','total_boray','total_carton',
    'remarks','total_amount','created_by','updated_by'];


    public function party(){
        return $this->hasOne(CoaDetailAccount::class, 'account_code', 'party_id');
    }

    public function details()
    {
        return $this->hasMany(SaleOrderDetail::class, 'sale_order_master_id', 'id');
    }


    public function parties(): BelongsTo
    {
        return $this->belongsTo(CoaDetailAccount::class);
    }
}
