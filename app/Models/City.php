<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = ['district_id', 'province_id', 'name', 'coordinator_id', 'note'];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class);
    }
}
