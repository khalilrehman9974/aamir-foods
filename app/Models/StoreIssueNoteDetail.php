<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreIssueNoteDetail extends Model
{
    use SoftDeletes;
    protected $table = 'store_issue_notes_detail';
    protected $fillable = ['store_issue_notes_id','description','quantity','date', 'created_by','updated_by'];
    public function store_issue_notes(){
        return $this->belongsTo(StoreIssueNote::class);
    }
}
