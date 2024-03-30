<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
    use SoftDeletes;

    use HasFactory;
    const PER_PAGE = 10;

    protected  $guarded = ['id'];
    protected $fillable = ['name','sector_id'];
    // public function sector(){
    //     return $this->belongsTo(Sector::class, 'id', 'sector_id');
    // }

    public function sector()
    {
        return $this->BelongsTo(Sector::class);
    }

}
