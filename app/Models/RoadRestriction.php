<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoadRestriction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restriction_type',
        'address',
        'latitude',
        'longitude',
        'description',
        'severity',
        'verified',
        'verification_count',
        'reporter_count',
        'status',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'verified' => 'boolean',
        'verification_count' => 'integer',
        'reporter_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verify(): void
    {
        $this->increment('verification_count');
        $this->increment('reporter_count');

        if ($this->verification_count >= 3) {
            $this->update(['verified' => true]);
        }
    }
}
