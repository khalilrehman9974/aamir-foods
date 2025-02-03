<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveredToPartiesAreas extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'delivered_to_parties_areas';

    protected $fillable = [
        'delivered_to_party_id', 'sector_id','area_id'
    ];
}
