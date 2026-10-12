<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevicePing extends Model
{
    public const STATUS_ON = 'on';

    public const STATUS_PLAY = 'play';

    public const STATUS_PAUSE = 'pause';

    public const STATUS_OFF = 'off';

    protected $fillable = [
        'bus_id',
        'processing_unit_id',
        'storage_unit_id',
        'device_id',
        'pen_id',
        'status',
        'duration_seconds',
        'is_flagged',
        'flag_reason',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
            'is_flagged' => 'boolean',
        ];
    }

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function processingUnit(): BelongsTo
    {
        return $this->belongsTo(ProcessingUnit::class);
    }

    public function storageUnit(): BelongsTo
    {
        return $this->belongsTo(StorageUnit::class);
    }
}
