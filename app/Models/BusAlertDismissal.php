<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusAlertDismissal extends Model
{
    protected $fillable = ['bus_id', 'type', 'forever', 'session_started_at', 'dismissed_by'];

    protected function casts(): array
    {
        return [
            'forever' => 'boolean',
            'session_started_at' => 'datetime',
        ];
    }

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }
}
