<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cage extends Model
{
    /** @use HasFactory<\Database\Factories\CageFactory> */
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'name',
        'location',
        'capacity',
    ];

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function animals(): HasMany
    {
        return $this->hasMany(Animal::class);
    }
}
