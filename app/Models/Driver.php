<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Driver extends Model
{
    protected $fillable = ['bus_id', 'name', 'phone'];

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }
}
