<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignSector extends Model
{
    use SoftDeletes;
    const PER_PAGE = 10;
    protected $table = 'assign_sector_to_sale_mans';

    protected  $guarded = ['id'];
    protected $fillable = ['sale_mans_id','sector_id'];

    public function sale_man_id()
    {
        return $this->hasOne(SaleMan::class, 'id', 'sale_mans_id');
    }

    public function sector()
    {
        return $this->hasOne(Sector::class, 'id', 'sector_id');
    }
}
