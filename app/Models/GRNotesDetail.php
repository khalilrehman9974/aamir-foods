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

    protected $fillable = ['goods_received_note_master_id','product_id', 'quantity','remarks'];

    public function goods_received_note_master(){
        return $this->belongsTo(GoodsReceivedNote::class);
    }
}
