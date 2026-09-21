<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'origin_address',
        'origin_lat',
        'origin_lng',
        'destination_address',
        'destination_lat',
        'destination_lng',
        'waypoints',
        'vehicle_type',
        'vehicle_weight_kg',
        'vehicle_height_m',
        'vehicle_width_m',
        'vehicle_length_m',
        'total_distance_km',
        'total_duration_minutes',
        'route_geometry',
        'notes',
    ];

    protected $casts = [
        'waypoints' => 'array',
        'route_geometry' => 'array',
        'origin_lat' => 'float',
        'origin_lng' => 'float',
        'destination_lat' => 'float',
        'destination_lng' => 'float',
        'total_distance_km' => 'float',
        'total_duration_minutes' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
