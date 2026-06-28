<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lineup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'formation_code',
        'note',
    ];

    public function players(): HasMany
    {
        return $this->hasMany(LineupPlayer::class);
    }
}
