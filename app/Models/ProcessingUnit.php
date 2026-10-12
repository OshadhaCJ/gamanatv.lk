<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProcessingUnit extends Model
{
    protected $fillable = ['device_id', 'telephone_number'];

    public function bus(): HasOne
    {
        return $this->hasOne(Bus::class);
    }
}
