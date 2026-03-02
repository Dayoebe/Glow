<?php

namespace App\Models\Career;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CareerApplication extends Model
{
    protected $fillable = [
        'career_position_id',
        'application_code',
        'full_name',
        'email',
        'phone',
        'location',
        'linkedin_url',
        'portfolio_url',
        'years_experience',
        'current_company',
        'current_role',
        'expected_salary',
        'available_from',
        'cover_letter',
        'resume_path',
        'resume_original_name',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'years_experience' => 'integer',
        'expected_salary' => 'decimal:2',
        'available_from' => 'date',
        'reviewed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::deleting(function (self $application) {
            $application->deleteResumeFile();
        });
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(CareerPosition::class, 'career_position_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function deleteResumeFile(): void
    {
        $path = trim((string) $this->resume_path);
        if ($path === '' || Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return;
        }

        $urlPath = ltrim((string) parse_url($path, PHP_URL_PATH), '/');
        if (Str::startsWith($urlPath, 'storage/')) {
            $publicPath = Str::after($urlPath, 'storage/');
            if (Storage::disk('public')->exists($publicPath)) {
                Storage::disk('public')->delete($publicPath);
            }
        }
    }
}
