<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleMan extends Model
{
    use SoftDeletes;
    protected $table = 'sale_mans';
    protected $fillable = ['name', 'email', 'country_id','business_id','f_year_id', 'designation', 'mobile_no', 'whatsapp_no',
    'mailing_address', 'address', 'reference','remarks', 'created_by', 'updated_by'];

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

}
