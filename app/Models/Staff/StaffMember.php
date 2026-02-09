<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
