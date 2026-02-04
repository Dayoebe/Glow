<?php

namespace App\Support;

use App\Models\Show\OAP;
use App\Models\Staff\StaffMember;
use App\Models\Team\Department;
use App\Models\Team\Role as TeamRole;
use App\Models\User;

class PersonProfileSync
{
    public static function fromUser(User $user): void
    {
        $staff = $user->staffMember;
        if (!$staff) {
            return;
        }

        [$departmentName, $roleName] = self::resolveTeamLabels($user->department_id, $user->team_role_id);

        $staff->update([
            'name' => $user->name,
            'email' => $user->email,
            'bio' => $user->bio,
            'photo_url' => $user->avatar,
            'department' => $departmentName ?? $staff->department,
            'role' => $roleName ?? $staff->role,
            'department_id' => $user->department_id,
            'team_role_id' => $user->team_role_id,
            'is_active' => $user->is_active,
        ]);

        $oap = $staff->oap;
        if (!$oap) {
            return;
        }

        $oap->update([
            'name' => $user->name,
            'email' => $user->email,
            'bio' => self::normalizeText($user->bio),
            'profile_photo' => $user->avatar,
            'department_id' => $user->department_id,
            'team_role_id' => $user->team_role_id,
            'is_active' => $user->is_active,
        ]);
    }

    public static function fromStaff(StaffMember $staff): void
    {
        $staff->loadMissing(['user', 'oap']);

        if ($staff->user) {
            $user = $staff->user;
            $user->update([
                'name' => $staff->name,
                'email' => self::safeUserEmail($user, $staff->email),
                'avatar' => $staff->photo_url,
                'bio' => $staff->bio,
                'department_id' => $staff->department_id,
                'team_role_id' => $staff->team_role_id,
                'is_active' => $staff->is_active,
            ]);
        }

        if (!$staff->oap) {
            return;
        }

        $oap = $staff->oap;
        $oap->update([
            'name' => $staff->name,
            'email' => $staff->email,
            'bio' => self::normalizeText($staff->bio),
            'profile_photo' => $staff->photo_url,
            'department_id' => $staff->department_id,
            'team_role_id' => $staff->team_role_id,
            'phone' => $staff->phone,
            'employment_status' => $staff->employment_status ?: $oap->employment_status,
            'is_active' => $staff->is_active,
            'joined_date' => $staff->joined_date,
            'social_media' => self::staffSocialToOap($staff->social_links),
        ]);
    }

    public static function fromOap(OAP $oap): void
    {
        $staff = $oap->staffMember;
        if (!$staff) {
            return;
        }

        [$departmentName, $roleName] = self::resolveTeamLabels($oap->department_id, $oap->team_role_id);

        $staff->update([
            'name' => $oap->name,
            'email' => $oap->email,
            'bio' => self::normalizeText($oap->bio),
            'photo_url' => $oap->profile_photo,
            'department' => $departmentName ?? $staff->department,
            'role' => $roleName ?? $staff->role,
            'department_id' => $oap->department_id,
            'team_role_id' => $oap->team_role_id,
            'phone' => $oap->phone,
            'employment_status' => $oap->employment_status,
            'is_active' => $oap->is_active,
            'joined_date' => $oap->joined_date,
            'social_links' => self::oapSocialToStaff($oap->social_media),
        ]);

        if (!$staff->user) {
            return;
        }

        $user = $staff->user;
        $user->update([
            'name' => $oap->name,
            'email' => self::safeUserEmail($user, $oap->email),
            'avatar' => $oap->profile_photo,
            'bio' => self::normalizeNullableText($oap->bio),
            'department_id' => $oap->department_id,
            'team_role_id' => $oap->team_role_id,
            'is_active' => $oap->is_active,
        ]);
    }

    private static function resolveTeamLabels($departmentId, $teamRoleId): array
    {
        $departmentName = $departmentId
            ? Department::query()->whereKey($departmentId)->value('name')
            : null;
        $roleName = $teamRoleId
            ? TeamRole::query()->whereKey($teamRoleId)->value('name')
            : null;

        return [$departmentName, $roleName];
    }

    private static function safeUserEmail(User $user, $candidate): string
    {
        $candidate = is_string($candidate) ? trim($candidate) : '';

        if ($candidate === '') {
            return $user->email;
        }

        if ($candidate === $user->email) {
            return $candidate;
        }

        $existsOnOtherUser = User::query()
            ->where('email', $candidate)
            ->where('id', '!=', $user->id)
            ->exists();

        return $existsOnOtherUser ? $user->email : $candidate;
    }

    private static function normalizeText($value): string
    {
        return is_string($value) ? trim($value) : '';
    }

    private static function normalizeNullableText($value): ?string
    {
        $value = is_string($value) ? trim($value) : '';

        return $value === '' ? null : $value;
    }

    private static function staffSocialToOap($links): array
    {
        if (!is_array($links)) {
            $links = [];
        }

        return [
            'facebook' => $links['facebook'] ?? $links['facebook_url'] ?? '',
            'twitter' => $links['twitter'] ?? $links['twitter_url'] ?? '',
            'instagram' => $links['instagram'] ?? $links['instagram_url'] ?? '',
            'tiktok' => $links['tiktok'] ?? $links['tiktok_url'] ?? '',
            'linkedin' => $links['linkedin'] ?? $links['linkedin_url'] ?? '',
            'youtube' => $links['youtube'] ?? $links['youtube_url'] ?? '',
        ];
    }

    private static function oapSocialToStaff($links): array
    {
        if (!is_array($links)) {
            $links = [];
        }

        return [
            'facebook' => $links['facebook'] ?? $links['facebook_url'] ?? '',
            'twitter' => $links['twitter'] ?? $links['twitter_url'] ?? '',
            'instagram' => $links['instagram'] ?? $links['instagram_url'] ?? '',
            'linkedin' => $links['linkedin'] ?? $links['linkedin_url'] ?? '',
        ];
    }
}
