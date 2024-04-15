<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreIssueNote extends Model
{
    use SoftDeletes;
    protected $table = 'store_issue_notes';
    protected $fillable = ['product_id','issued_to','issued_by','remarks'];

    public function product(){
        return $this->hasOne(CoaInventoryDetailAccount::class, 'code', 'product_id');
    }

}
