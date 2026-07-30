<?php

namespace App\Livewire\Page;

use App\Models\Staff\StaffMember;
use App\Support\Seo;
use Livewire\Component;
use Livewire\WithPagination;

class StaffDirectory extends Component
{
    use WithPagination;

    public $searchQuery = '';

    protected $queryString = [
        'searchQuery' => ['except' => ''],
    ];

    public function updatingSearchQuery()
    {
        $this->resetPage();
    }

    public function getStaffProfilesProperty()
    {
        $staff = StaffMember::query()
            ->with(['departmentRelation', 'teamRole'])
            ->where('is_active', true)
            ->when($this->searchQuery, function ($query) {
                $query->where(function ($search) {
                    $search->where('name', 'like', "%{$this->searchQuery}%")
                        ->orWhere('role', 'like', "%{$this->searchQuery}%")
                        ->orWhere('department', 'like', "%{$this->searchQuery}%")
                        ->orWhereHas('departmentRelation', function ($dept) {
                            $dept->where('name', 'like', "%{$this->searchQuery}%");
                        })
                        ->orWhereHas('teamRole', function ($role) {
                            $role->where('name', 'like', "%{$this->searchQuery}%");
                        });
                });
            })
            ->orderBy('name')
            ->paginate(12);

        $staff->getCollection()->transform(function ($staff) {
            return [
                'type' => 'staff',
                'id' => $staff->id,
                'name' => $staff->name,
                'slug' => $staff->slug,
                'role' => $staff->teamRole?->name ?? ($staff->role ?? 'Staff Member'),
                'department' => $staff->departmentRelation?->name ?? ($staff->department ?? 'General'),
                'photo' => $staff->photo_url,
                'bio' => $staff->bio,
                'social_links' => $staff->social_links ?? [],
                'profile_url' => route('staff.show', $staff->slug),
            ];
        });

        return $staff;
    }

    public function render()
    {
        $staffProfiles = $this->staffProfiles;
        $page = max(1, (int) request()->query('page', 1));
        $canonical = Seo::canonicalUrl(route('staff.index'), $page > 1 ? ['page' => $page] : []);
        $title = $page > 1
            ? "Glow FM Team - Page {$page}"
            : 'Glow FM Team and Editorial Staff';
        $description = 'Meet the presenters, journalists, producers, and professionals responsible for Glow 99.1 FM radio and digital media in Akure.';
        $firstPosition = $staffProfiles->firstItem() ?: 1;
        $people = $staffProfiles->getCollection()
            ->values()
            ->map(function (array $staff, int $index) use ($firstPosition) {
                return [
                    '@type' => 'ListItem',
                    'position' => $firstPosition + $index,
                    'url' => $staff['profile_url'],
                    'item' => Seo::person($staff, $staff['profile_url']),
                ];
            })
            ->all();

        return view('livewire.page.staff-directory', [
            'staffProfiles' => $staffProfiles,
        ])->layout('layouts.app', [
            'title' => $title,
            'meta_title' => $title,
            'meta_description' => $description,
            'canonical_url' => $canonical,
            'structured_data' => Seo::siteGraph([
                'title' => $title,
                'description' => $description,
                'url' => $canonical,
                'type' => 'CollectionPage',
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Team', 'url' => route('staff.index')],
                ],
                'mainEntity' => [
                    '@type' => 'ItemList',
                    'name' => 'Glow FM team',
                    'numberOfItems' => $staffProfiles->total(),
                    'itemListElement' => $people,
                ],
            ]),
        ]);
    }
}
