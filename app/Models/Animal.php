<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Animal extends Model
{
    /** @use HasFactory<\Database\Factories\AnimalFactory> */
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'cage_id',
        'type',
        'quantity',
        'date_acquired',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_acquired' => 'date',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function eggProductions(): HasMany
    {
        return $this->hasMany(EggProduction::class);
    }

    public function feedRecords(): HasMany
    {
        return $this->hasMany(FeedRecord::class);
    }

    public function cage(): BelongsTo
    {
        return $this->belongsTo(Cage::class);
    }

    public function healthRecords(): HasMany
    {
        return $this->hasMany(HealthRecord::class);
    }
}
