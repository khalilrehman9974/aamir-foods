<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sector extends Model
{
    use SoftDeletes;
    protected  $guarded = ['id'];
    protected $fillable = ['name', 'zone_id'];

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }
}
