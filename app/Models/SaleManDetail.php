<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleManDetail extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'sale_man_details';

    protected $fillable = ['master_id','zone_id', 'sector_id','area_id'];

    public function country_master(){
        return $this->hasMany(SaleMan::class,'id', 'master_id');
    }

    public function zones(){
        return $this->hasMany(Zone::class,'id', 'zone_id');
    }

    public function sectors(){
        return $this->hasMany(Sector::class,'id', 'sector_id');
    }


    public function areas(){
        return $this->hasMany(Area::class,'id', 'area_id');
    }



}
