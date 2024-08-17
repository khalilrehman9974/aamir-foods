<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleMan extends Model
{
    use SoftDeletes;
    protected $table = 'sale_Mans';
    const PER_PAGE = 2;
    protected $fillable = ['name', 'city', 'contact_number', 'address', 'contact_person', 'country_id', 'zone_id',
    'sector_id', 'area_id', 'designation','remarks', 'created_by', 'updated_by'];

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function zone()
    {
        return $this->hasOne(Zone::class, 'id', 'zone_id');
    }

    public function sectors()
    {
        return $this->hasOne(Sector::class, 'id', 'sector_id');
    }

    public function area()
    {
        return $this->hasOne(Area::class, 'id', 'area_id');
    }
}
