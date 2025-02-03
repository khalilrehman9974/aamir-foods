<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoaDetailAccountSectors extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'coa_detail_account_sectors';

    protected $fillable = [
        'master_account_id', 'sector_id'
    ];
}
