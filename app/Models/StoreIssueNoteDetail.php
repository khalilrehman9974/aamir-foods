<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreIssueNoteDetail extends Model
{
    protected $fillable = ['description','quantity','date'];
    public function store_issue_notes_id(){
        return $this->belongsTo(StoreIssueNote::class);
    }
}
