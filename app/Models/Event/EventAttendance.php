<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class EventAttendance extends Model
{
    protected $table = 'event_attendances';

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'party_size',
        'notes',
    ];

    protected $casts = [
        'party_size' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
