<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GRNotesDetail extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'goods_received_note_details';

    protected $fillable = ['master_id','product_id', 'packing_type','measurement_type',
    'size','po_quantity','received_qty','balance','detail_remarks'];

    public function goods_received_note_master(){
        return $this->belongsTo(GoodsReceivedNote::class);
    }
}
