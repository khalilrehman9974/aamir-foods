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
    protected $fillable = ['po_no','date','sale_man_id','party_id','transporter_id','bilty_no','contact_no','fare','created_by','updated_by'];

    public function party()
    {
        return $this->hasOne(CoaDetailAccount::class, 'account_code', 'party_id');
    }

    public function transporter()
    {
        return $this->hasOne(Transporter::class, 'id', 'transporter_id');
    }

    public function saleMan()
    {
        return $this->hasOne(SaleMan::class, 'id', 'sale_man_id');
    }
}
