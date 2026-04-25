<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lineup extends Model
{
    protected $fillable = [
        'title',
        'formation_code',
        'note',
    ];

    public function players(): HasMany
    {
        return $this->hasMany(LineupPlayer::class);
    }
}
