<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StorageUnit extends Model
{
    protected $fillable = ['pen_id', 'last_processing_unit_id', 'last_paired_at'];

    protected function casts(): array
    {
        return [
            'last_paired_at' => 'datetime',
        ];
    }

    public function bus(): HasOne
    {
        return $this->hasOne(Bus::class);
    }

    public function lastProcessingUnit(): BelongsTo
    {
        return $this->belongsTo(ProcessingUnit::class, 'last_processing_unit_id');
    }
}
