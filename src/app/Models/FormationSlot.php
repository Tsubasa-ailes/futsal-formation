<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormationSlot extends Model
{
    protected $fillable = [
        'formation_template_id',
        'slot',
        'default_x',
        'default_y',
        'role_label',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(FormationTemplate::class, 'formation_template_id');
    }
}