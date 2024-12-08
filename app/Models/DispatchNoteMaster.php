<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DispatchNoteMaster extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'dispatch_note_masters';
    protected $fillable = ['sale_order_number','date','party_id','saleman','sector','area','delivered_to','vehicle_no',
    'bility_no','driver_name','carriage','total_boray','total_carton','created_by','updated_by'];


    public function party()
    {
        return $this->hasOne(CoaDetailAccount::class, 'account_code', 'party_id');
    }


}
