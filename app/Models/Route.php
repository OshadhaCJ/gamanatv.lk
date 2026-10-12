<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Route extends Model
{
    protected $fillable = ['start_place', 'route', 'end_place', 'note'];

    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class);
    }

    protected function startPlace(): Attribute
    {
        return Attribute::make(set: fn (?string $value) => $value !== null ? Str::upper($value) : null);
    }

    protected function route(): Attribute
    {
        return Attribute::make(set: fn (?string $value) => $value !== null ? Str::upper($value) : null);
    }

    protected function endPlace(): Attribute
    {
        return Attribute::make(set: fn (?string $value) => $value !== null ? Str::upper($value) : null);
    }
}
