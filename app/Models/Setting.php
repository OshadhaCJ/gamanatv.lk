<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'is_secret',
    ];

    protected function casts(): array
    {
        return [
            'is_secret' => 'boolean',
        ];
    }

    /**
     * Read a setting value by key, with an optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $values = Cache::rememberForever('settings.all', function () {
            return static::query()->pluck('value', 'key')->all();
        });

        return $values[$key] ?? $default;
    }

    /**
     * Create or update a setting value.
     */
    public static function set(string $key, ?string $value, string $group = 'general', bool $isSecret = false): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'is_secret' => $isSecret]
        );

        Cache::forget('settings.all');
    }

    /**
     * Fetch every setting that belongs to a given group, keyed by setting key.
     */
    public static function group(string $group): array
    {
        return static::query()->where('group', $group)->pluck('value', 'key')->all();
    }
}
