<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class LineupPlayer extends Model
{

    use SoftDeletes;
    
    protected $fillable = [
        'lineup_id',
        'slot',
        'display_name',
        'number',
        'x',
        'y',
    ];

    public function lineup(): BelongsTo
    {
        return $this->belongsTo(Lineup::class);
    }
}
