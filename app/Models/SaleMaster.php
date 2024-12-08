<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleMaster extends Model
{
    protected $guarded = ['id'];
    protected $table = 'sale_masters';

    protected $fillable = ['dispatch_note_number','sale_order_number','date', 'party_id','sector','area','deliverd_to','saleman','vehicle_no','business_id','f_year_id',
    'driver_name','bilty_no','remarks','total_boray','total_carton','gross_bill','carriage','discount','commission','net_amount','updated_by','created_by'];


}
