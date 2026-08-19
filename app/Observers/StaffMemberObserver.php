<?php

namespace App\Observers;

use App\Models\Staff\StaffMember;
use App\Support\PersonProfileSync;

class StaffMemberObserver
{
    public function saved(StaffMember $staff): void
    {
        if (
            !$staff->wasRecentlyCreated
            && !$staff->wasChanged([
                'user_id',
                'name',
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
                'social_links',
                'profile_visibility',
            ])
        ) {
            return;
        }

        PersonProfileSync::fromStaff($staff);
    }
}
