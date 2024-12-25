<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleManSector extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'sale_man_sectors';

    protected $fillable = ['master_id', 'sector_id'];

    public function country_master(){
        return $this->hasMany(SaleMan::class,'id', 'master_id');
    }

    public function sectors(){
        return $this->hasMany(Sector::class,'id', 'sector_id');
    }

}
