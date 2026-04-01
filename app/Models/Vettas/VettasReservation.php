<?php

namespace App\Models\Vettas;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class VettasReservation extends Model
{
    protected $fillable = [
        'user_id',
        'reservation_code',
        'full_name',
        'email',
        'phone',
        'check_in_date',
        'check_out_date',
        'guest_count',
        'status',
        'special_requests',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'source',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'guest_count' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $reservation) {
            if (filled($reservation->reservation_code)) {
                return;
            }

            do {
                $code = 'VET-' . now()->format('ymd') . '-' . Str::upper(Str::random(5));
            } while (static::query()->where('reservation_code', $code)->exists());

            $reservation->reservation_code = $code;
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopeSearch($query, string $searchTerm)
    {
        return $query->where(function ($inner) use ($searchTerm) {
            $inner->where('full_name', 'like', "%{$searchTerm}%")
                ->orWhere('email', 'like', "%{$searchTerm}%")
                ->orWhere('phone', 'like', "%{$searchTerm}%")
                ->orWhere('reservation_code', 'like', "%{$searchTerm}%");
        });
    }

    public function getNightsAttribute(): ?int
    {
        if (!$this->check_in_date || !$this->check_out_date) {
            return null;
        }

        return max(1, $this->check_in_date->diffInDays($this->check_out_date));
    }
}
