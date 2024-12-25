<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleManZone extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'sale_man_zones';

    protected $fillable = ['master_id','zone_id'];

    public function country_master(){
        return $this->hasMany(SaleMan::class,'id', 'master_id');
    }

    public function zones(){
        return $this->hasMany(Zone::class,'id', 'zone_id');
    }


}
