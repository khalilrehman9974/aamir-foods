<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoaDetailAccountArea extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'coa_detail_account_areas';

    protected $fillable = [
        'master_account_id', 'sector_id','area_id'
    ];
}
