<?php

namespace App\Models\Show;

use App\Models\User;
use App\Models\Staff\StaffMember;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

// ===== OAP Model (On-Air Personality/Broadcaster) =====
class OAP extends Model
{
    use HasFactory;

    protected $table = 'oaps';

    protected $fillable = [
        'staff_member_id', 'name', 'slug', 'bio', 'profile_photo', 'gallery', 'voice_sample_url',
        'specializations', 'email', 'department_id', 'team_role_id', 'phone', 'social_media', 'employment_status',
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

    public function staffMember()
    {
        return $this->belongsTo(StaffMember::class, 'staff_member_id');
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\Team\Department::class, 'department_id');
    }

    public function teamRole()
    {
        return $this->belongsTo(\App\Models\Team\Role::class, 'team_role_id');
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
        return $query->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('staff_member_id')
                    ->orWhereHas('staffMember', function ($staffQuery) {
                        $staffQuery->where('is_active', true);
                    });
            });
    }

    public function scopeAvailable($query)
    {
        return $query->where('available', true);
    }

    public function getPublicSocialLinksAttribute(): array
    {
        $oapLinks = $this->normalizeSocialLinks($this->social_media);
        if (!empty(array_filter($oapLinks))) {
            return $oapLinks;
        }

        $staffLinks = $this->normalizeSocialLinks($this->staffMember?->social_links);
        if (!empty(array_filter($staffLinks))) {
            return $staffLinks;
        }

        return $oapLinks;
    }

    private function normalizeSocialLinks($links): array
    {
        if (!is_array($links)) {
            $links = [];
        }

        return [
            'facebook' => $this->normalizeSocialValue($links['facebook'] ?? $links['facebook_url'] ?? ''),
            'twitter' => $this->normalizeSocialValue($links['twitter'] ?? $links['twitter_url'] ?? $links['x'] ?? ''),
            'instagram' => $this->normalizeSocialValue($links['instagram'] ?? $links['instagram_url'] ?? ''),
            'tiktok' => $this->normalizeSocialValue($links['tiktok'] ?? $links['tiktok_url'] ?? ''),
            'linkedin' => $this->normalizeSocialValue($links['linkedin'] ?? $links['linkedin_url'] ?? ''),
            'youtube' => $this->normalizeSocialValue($links['youtube'] ?? $links['youtube_url'] ?? ''),
        ];
    }

    private function normalizeSocialValue($value): string
    {
        return is_string($value) ? trim($value) : '';
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
