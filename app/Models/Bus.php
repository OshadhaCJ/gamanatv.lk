<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    protected $fillable = [
        'city_id',
        'number',
        'name',
        'owner_id',
        'processing_unit_id',
        'storage_unit_id',
        'schedule_type',
        'fixed_time',
        'rotation_description',
        'front_image',
        'side_image',
        'tv_front_image',
        'tv_back_image',
        'routes_per_day',
        'route_duration_minutes',
        'starting_place_fixed',
        'route_id',
        'tv_brand',
        'tv_size',
        'input_type',
        'holiday_note',
    ];

    protected function casts(): array
    {
        return [
            'starting_place_fixed' => 'boolean',
        ];
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    public function processingUnit(): BelongsTo
    {
        return $this->belongsTo(ProcessingUnit::class);
    }

    public function storageUnit(): BelongsTo
    {
        return $this->belongsTo(StorageUnit::class);
    }

    public function devicePings(): HasMany
    {
        return $this->hasMany(DevicePing::class);
    }

    public function alertDismissals(): HasMany
    {
        return $this->hasMany(BusAlertDismissal::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }

    public function conductors(): HasMany
    {
        return $this->hasMany(Conductor::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(BusAttendance::class);
    }

    public function swapRequests(): HasMany
    {
        return $this->hasMany(StorageSwapRequest::class);
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(BusIncident::class);
    }
}
