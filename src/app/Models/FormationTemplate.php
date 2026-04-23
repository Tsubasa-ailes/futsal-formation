<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormationTemplate extends Model
{
    protected $fillable = [
        'formation_code',
        'name',
    ];

    public function slots(): HasMany
    {
        return $this->hasMany(FormationSlot::class)->orderBy('slot');
    }
}