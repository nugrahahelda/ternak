<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedRecord extends Model
{
    /** @use HasFactory<\Database\Factories\FeedRecordFactory> */
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'date',
        'feed_type',
        'quantity',
        'unit',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'quantity' => 'decimal:2',
        ];
    }

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }
}
