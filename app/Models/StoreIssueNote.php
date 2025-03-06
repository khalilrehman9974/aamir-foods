<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreIssueNote extends Model
{
    use SoftDeletes;
    protected $table = 'store_issue_notes';
    protected $fillable = ['date','receiver_name','from_department','to_department','created_by','updated_by'];

    public function product(){
        return $this->hasOne(CoaInventoryDetailAccount::class, 'code', 'product_id');
    }

    public function fromDepartment(){
        return $this->hasOne(Department::class, 'id', 'from_department');
    }

    public function toDepartment(){
        return $this->hasOne(Department::class, 'id', 'to_department');
    }
}
