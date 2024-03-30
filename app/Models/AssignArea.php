<?php

namespace App\Models;

use App\Models\Area;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignArea extends Model
{
    use SoftDeletes;

    protected $table = 'assign_area_to_sale_mans';

    protected  $guarded = ['id'];
    protected $fillable = ['sale_mans_id','area_id'];

    public function sale_man()
    {
        return $this->hasOne(SaleMan::class, 'id', 'sale_mans_id');
    }

    public function area()
    {
        return $this->hasOne(Area::class, 'id', 'area_id');
    }
}
