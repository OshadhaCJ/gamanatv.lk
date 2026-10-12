<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusIncident extends Model
{
    public const SEEN_ON_ROUTE = 'seen_on_route';

    public const SEEN_PLAYING = 'seen_playing';

    public const SEEN_NOT_PLAYING = 'seen_not_playing';

    public const SEEN_TV_NOT_VISIBLE = 'seen_tv_not_visible';

    public const LABELS = [
        self::SEEN_ON_ROUTE => 'Saw the bus on its route',
        self::SEEN_PLAYING => 'Saw the bus — TV was playing',
        self::SEEN_NOT_PLAYING => 'Saw the bus — TV was not playing',
        self::SEEN_TV_NOT_VISIBLE => 'Saw the bus — could not see the TV',
    ];

    protected $fillable = ['bus_id', 'type', 'note', 'reported_by', 'occurred_at'];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
        ];
    }

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
