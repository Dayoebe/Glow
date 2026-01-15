<?php

namespace App\Models\Show;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

// ===== OAP Model (On-Air Personality/Broadcaster) =====
class OAP extends Model
{
    use HasFactory;

    protected $table = 'oaps';

    protected $fillable = [
        'name', 'slug', 'bio', 'profile_photo', 'gallery', 'voice_sample_url',
        'specializations', 'email', 'phone', 'social_media', 'employment_status',
        'is_active', 'available', 'joined_date', 'total_shows_hosted', 'average_rating'
    ];

    protected $casts = [
        'gallery' => 'array',
        'specializations' => 'array',
        'social_media' => 'array',
        'is_active' => 'boolean',
        'available' => 'boolean',
        'joined_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($oap) {
            if (empty($oap->slug)) {
                $oap->slug = Str::slug($oap->name);
            }
        });
    }

    public function shows()
    {
        return $this->hasMany(Show::class, 'primary_host_id');
    }

    public function scheduleSlots()
    {
        return $this->hasMany(ScheduleSlot::class, 'oap_id');
    }

    public function availability()
    {
        return $this->hasMany(OAPAvailability::class, 'oap_id');
    }

    public function isAvailable($date, $startTime, $endTime)
    {
        // Check if OAP is available for given date/time
        $unavailable = $this->availability()
            ->where('date', $date)
            ->where('is_available', false)
            ->where(function($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime]);
            })
            ->exists();

        return !$unavailable;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('available', true);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}




