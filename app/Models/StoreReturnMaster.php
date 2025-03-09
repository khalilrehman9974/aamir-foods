<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreReturnMaster extends Model
{

    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'store_return_masters';
    protected $fillable = ['date','receiver_name','from_department', 'to_department','created_by','updated_by'];

    public function fromDepartment(){
        return $this->hasOne(Department::class, 'id', 'from_department');
    }

    public function toDepartment(){
        return $this->hasOne(Department::class, 'id', 'to_department');
    }
}
