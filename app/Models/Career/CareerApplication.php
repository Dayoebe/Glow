<?php

namespace App\Models\Career;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function position(): BelongsTo
    {
        return $this->belongsTo(CareerPosition::class, 'career_position_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
