<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DispatchNoteDetail extends Model
{
    use SoftDeletes;
    protected $table = 'dispatch_note_details';
    protected $fillable = ['dispatch_note_master_id','product_id','quantity','unit','remarks','created_by','updated_by'];

    public function dispatch_note_master_id(){
        return $this->hasMany(DispatchNoteMaster::class, 'id', 'dispatch_note_master_id');
    }

}
