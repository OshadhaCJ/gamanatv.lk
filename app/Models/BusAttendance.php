<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusAttendance extends Model
{
    public const RUNNING = 'running';

    public const HOLIDAY = 'holiday';

    public const COULDNT_CONTACT = 'couldnt_contact';

    protected $fillable = ['bus_id', 'date', 'status', 'auto_marked', 'marked_by', 'note'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'auto_marked' => 'boolean',
        ];
    }

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function markedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}
