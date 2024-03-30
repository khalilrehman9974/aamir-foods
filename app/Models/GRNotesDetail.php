<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GRNotesDetail extends Model
{
    protected $guarded = ['id'];
    protected $table = 'goods_recevied_note_details';

    protected $fillable = ['grn_master_id','product_id', 'quantity','remarks'];

    public function grn_master_id(){
        return $this->belongsTo(GoodsReceivedNote::class);
    }
}
