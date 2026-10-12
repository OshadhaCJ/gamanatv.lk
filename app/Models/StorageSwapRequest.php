<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorageSwapRequest extends Model
{
    public const PENDING = 'pending';

    public const CONFIRMED = 'confirmed';

    public const FAILED = 'failed';

    protected $fillable = [
        'bus_id',
        'old_storage_unit_id',
        'new_storage_unit_id',
        'status',
        'fault_reason',
        'acknowledged',
        'requested_by',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'acknowledged' => 'boolean',
            'confirmed_at' => 'datetime',
        ];
    }

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function oldStorageUnit(): BelongsTo
    {
        return $this->belongsTo(StorageUnit::class, 'old_storage_unit_id');
    }

    public function newStorageUnit(): BelongsTo
    {
        return $this->belongsTo(StorageUnit::class, 'new_storage_unit_id');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
