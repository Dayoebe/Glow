<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles {
        hasRole as protected spatieHasRole;
        hasAnyRole as protected spatieHasAnyRole;
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'team_role_id',
        'avatar',
        'bio',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public const STAFF_LIKE_ROLES = ['staff', 'corp_member', 'intern'];

    public function isAccessDisabled(): bool
    {
        if (isset($this->is_active) && !$this->is_active) {
            return true;
        }

        if (!$this->exists) {
            return false;
        }

        $staffMember = $this->relationLoaded('staffMember')
            ? $this->staffMember
            : $this->staffMember()->first();

        return $staffMember !== null && !$staffMember->is_active;
    }

    // Role helper methods
    public function isAdmin(): bool
    {
        if ($this->isAccessDisabled()) {
            return false;
        }

        return $this->role === 'admin' || $this->spatieHasRole('admin');
    }

    public function isStaff(): bool
    {
        if ($this->isAccessDisabled()) {
            return false;
        }

        if (in_array($this->role, self::STAFF_LIKE_ROLES, true)) {
            return true;
        }

        return $this->spatieHasAnyRole(self::STAFF_LIKE_ROLES);
    }

    public function isDj(): bool
    {
        return $this->isStaff();
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function canApproveNews(): bool
    {
        if ($this->isAccessDisabled()) {
            return false;
        }

        $staffMember = $this->staffMember;

        if (!$staffMember || !$staffMember->is_active) {
            return false;
        }

        $approverIds = collect(Setting::get('content_approvers.ids', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->all();

        return in_array((int) $staffMember->id, $approverIds, true);
    }

    public function hasRole($role, ?string $guard = null): bool
    {
        if ($this->isAccessDisabled()) {
            return false;
        }

        if (is_string($role) && $role === 'staff') {
            return $this->isStaff();
        }

        if (is_string($role) && $this->role === $role) {
            return true;
        }

        return $this->spatieHasRole($role, $guard);
    }

    public function hasAnyRole(...$roles): bool
    {
        return $this->hasRole($roles);
    }

    public function getRoleLabelAttribute(): string
    {
        $role = $this->role ?? 'user';

        return (string) Str::of($role)->replace('_', ' ')->title();
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\Team\Department::class, 'department_id');
    }

    public function teamRole()
    {
        return $this->belongsTo(\App\Models\Team\Role::class, 'team_role_id');
    }

    public function staffMember()
    {
        return $this->hasOne(\App\Models\Staff\StaffMember::class, 'user_id');
    }

    public function getDefaultGuardName(): string
    {
        return config('auth.defaults.guard', 'web');
    }
}
