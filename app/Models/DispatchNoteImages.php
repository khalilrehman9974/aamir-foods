<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DispatchNoteImages extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'dispatch_note_images';

    protected $fillable = ['dispatch_note_id', 'images'];
}
