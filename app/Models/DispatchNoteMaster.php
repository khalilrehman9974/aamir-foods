<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DispatchNoteMaster extends Model
{
    use HasFactory;

    public function items(): HasMany
    {
        return  $this->hasMany(DispatchNoteDetail::class);
    }
}