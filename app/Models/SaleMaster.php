<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleMaster extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'sale_masters';

    protected $fillable = ['dispatch_note_number','sale_order_number','date', 'party_id','sector','area','deliverd_to','saleman','vehicle_no','business_id','f_year_id',
    'driver_name','bilty_no','remarks','total_boray','total_carton','gross_bill','carriage','totaldiscount','commission','net_amount'];

    public function party()
    {
        return $this->hasOne(CoaDetailAccount::class, 'id', 'party_id');
    }

    public function SaleMan()
    {
        return $this->hasOne(SaleMan::class, 'id', 'saleman');
    }

}
