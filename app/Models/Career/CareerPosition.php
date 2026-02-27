<?php

namespace App\Models\Career;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CareerPosition extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'responsibilities',
        'requirements',
        'benefits',
        'department',
        'employment_type',
        'workplace_type',
        'experience_level',
        'location',
        'city',
        'state',
        'country',
        'min_salary',
        'max_salary',
        'salary_currency',
        'salary_period',
        'application_deadline',
        'start_date',
        'positions_available',
        'is_featured',
        'is_published',
        'allow_applications',
        'status',
        'published_at',
        'meta',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'min_salary' => 'decimal:2',
        'max_salary' => 'decimal:2',
        'application_deadline' => 'date',
        'start_date' => 'date',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'allow_applications' => 'boolean',
        'published_at' => 'datetime',
        'positions_available' => 'integer',
        'meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $position) {
            if (empty($position->slug)) {
                $position->slug = Str::slug($position->title);
            }
        });

        static::updating(function (self $position) {
            if ($position->isDirty('title') && empty($position->slug)) {
                $position->slug = Str::slug($position->title);
            }
        });
    }

    public function applications(): HasMany
    {
        return $this->hasMany(CareerApplication::class)->latest();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($inner) {
                $inner->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeAcceptingApplications($query)
    {
        return $query->where('allow_applications', true)
            ->where('status', 'open')
            ->where(function ($inner) {
                $inner->whereNull('application_deadline')
                    ->orWhereDate('application_deadline', '>=', now()->toDateString());
            });
    }

    public function scopeSearch($query, string $searchTerm)
    {
        return $query->where(function ($inner) use ($searchTerm) {
            $inner->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('excerpt', 'like', "%{$searchTerm}%")
                ->orWhere('description', 'like', "%{$searchTerm}%")
                ->orWhere('department', 'like', "%{$searchTerm}%")
                ->orWhere('location', 'like', "%{$searchTerm}%");
        });
    }

    public function isAcceptingApplications(): bool
    {
        if (!$this->allow_applications || $this->status !== 'open') {
            return false;
        }

        if ($this->application_deadline && $this->application_deadline->lt(now()->startOfDay())) {
            return false;
        }

        return true;
    }

    public function getSalaryRangeLabelAttribute(): string
    {
        if ($this->min_salary === null && $this->max_salary === null) {
            return 'Salary not disclosed';
        }

        $currency = strtoupper((string) ($this->salary_currency ?: 'NGN'));
        $period = $this->salary_period ?: 'monthly';

        $format = static fn ($value) => number_format((float) $value, 0);

        if ($this->min_salary !== null && $this->max_salary !== null) {
            return $currency . ' ' . $format($this->min_salary) . ' - ' . $format($this->max_salary) . ' / ' . $period;
        }

        if ($this->min_salary !== null) {
            return 'From ' . $currency . ' ' . $format($this->min_salary) . ' / ' . $period;
        }

        return 'Up to ' . $currency . ' ' . $format($this->max_salary) . ' / ' . $period;
    }

    public function getLocationLabelAttribute(): string
    {
        if (!empty($this->location)) {
            return $this->location;
        }

        $parts = array_filter([$this->city, $this->state, $this->country]);
        if (!empty($parts)) {
            return implode(', ', $parts);
        }

        return 'Location not specified';
    }
}
