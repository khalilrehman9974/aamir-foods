<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsReceivedNote extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'goods_received_note_masters';

    protected $fillable = ['purchase_order_no','date', 'supplier_name','fare','supplier_bill_no','transporter_id','business_id','f_year_id',
    'remarks'];

    public function transporter(){
        return $this->hasOne(Transporter::class, 'id' ,'transporter_id');
    }

}
