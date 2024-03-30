<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreIssueNote extends Model
{
    protected $fillable = ['product_id','issued_to','issued_by','remarks'];
    
    public function party_id(){
        return $this->hasOne(CoaDetailAccount::class, 'id', 'party_id');
    }

}
