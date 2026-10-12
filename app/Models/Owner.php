<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Owner extends Model
{
    protected $fillable = ['name', 'phone'];

    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class);
    }
}
