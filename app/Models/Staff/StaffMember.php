<?php

namespace App\Models\Staff;

use App\Models\Show\ScheduleSlot;
use App\Models\Show\Show;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Show\OAP;

class StaffMember extends Model
{
    use HasFactory;

    protected $table = 'staff_members';

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'role',
        'department',
        'department_id',
        'team_role_id',
        'bio',
        'photo_url',
        'email',
        'phone',
        'employment_status',
        'is_active',
        'joined_date',
        'date_of_birth',
        'birth_month',
        'birth_day',
        'birth_year',
        'social_links',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'joined_date' => 'date',
        'date_of_birth' => 'date',
        'birth_month' => 'integer',
        'birth_day' => 'integer',
        'birth_year' => 'integer',
        'social_links' => 'array',
    ];

    public function departmentRelation()
    {
        return $this->belongsTo(\App\Models\Team\Department::class, 'department_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function teamRole()
    {
        return $this->belongsTo(\App\Models\Team\Role::class, 'team_role_id');
    }

    public function oap()
    {
        return $this->hasOne(OAP::class, 'staff_member_id');
    }

    public function deactivateForOffboarding(): void
    {
        DB::transaction(function () {
            $this->forceFill(['is_active' => false])->save();
            $this->loadMissing(['user', 'oap']);

            if ($this->user) {
                $this->user->forceFill([
                    'is_active' => false,
                    'api_token' => null,
                    'api_token_created_at' => null,
                ])->save();
            }

            if (!$this->oap) {
                return;
            }

            $oap = $this->oap;
            $oap->forceFill([
                'is_active' => false,
                'available' => false,
            ])->save();

            Show::where('primary_host_id', $oap->id)->update(['primary_host_id' => null]);

            Show::query()
                ->whereNotNull('co_hosts')
                ->get(['id', 'co_hosts'])
                ->each(function (Show $show) use ($oap) {
                    $originalHosts = collect($show->co_hosts ?: [])
                        ->map(fn ($hostId) => (int) $hostId)
                        ->filter()
                        ->values();

                    $remainingHosts = $originalHosts
                        ->reject(fn ($hostId) => $hostId === (int) $oap->id)
                        ->values();

                    if ($remainingHosts->count() === $originalHosts->count()) {
                        return;
                    }

                    $show->forceFill([
                        'co_hosts' => $remainingHosts->isEmpty() ? null : $remainingHosts->all(),
                    ])->save();
                });

            ScheduleSlot::where('oap_id', $oap->id)->update(['oap_id' => null]);
        });

        $this->refresh();
    }

    public function reactivateForStaff(): void
    {
        DB::transaction(function () {
            $this->forceFill(['is_active' => true])->save();
            $this->loadMissing('user');

            if ($this->user) {
                $this->user->forceFill(['is_active' => true])->save();
            }
        });

        $this->refresh();
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($staff) {
            if (empty($staff->slug)) {
                $staff->slug = Str::slug($staff->name);
            }
        });
    }
}
