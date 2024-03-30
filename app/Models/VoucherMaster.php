<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherMaster extends Model
{
    protected $fillable = ['date', 'f_year_id', 'total_amount', 'vr_type_id', 'business_id', 'created_by', 'updated_by'];

    public function f_year_id()
    {
        return $this->belongsTo(FinancialYear::class, 'id', 'f_year_id');
    }

    public function vr_type_id()
    {
        return $this->belongsTo(VoucherType::class, 'id', 'vr_type_id');
    }
    
    public function business_id()
    {
        return $this->belongsTo(Business::class, 'id', 'business_id');
    }
}
