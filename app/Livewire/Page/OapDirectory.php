<?php

namespace App\Livewire\Page;

use App\Models\Show\OAP;
use App\Support\Seo;
use Livewire\Component;
use Livewire\WithPagination;

class OapDirectory extends Component
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

    public function getOapsProperty()
    {
        return OAP::active()
            ->with(['department', 'teamRole', 'staffMember'])
            ->withCount('shows')
            ->when($this->searchQuery, function ($q) {
                $q->where('name', 'like', "%{$this->searchQuery}%")
                  ->orWhere('bio', 'like', "%{$this->searchQuery}%")
                  ->orWhereHas('department', function ($dept) {
                      $dept->where('name', 'like', "%{$this->searchQuery}%");
                  })
                  ->orWhereHas('teamRole', function ($role) {
                      $role->where('name', 'like', "%{$this->searchQuery}%");
                  });
            })
            ->orderBy('name')
            ->paginate(12);
    }

    public function render()
    {
        $oaps = $this->oaps;
        $page = max(1, (int) request()->query('page', 1));
        $canonical = Seo::canonicalUrl(route('oaps.index'), $page > 1 ? ['page' => $page] : []);
        $title = $page > 1
            ? "Glow FM Presenters - Page {$page}"
            : 'Glow FM Presenters and On-Air Personalities';
        $description = 'Meet the presenters and on-air personalities behind Glow 99.1 FM programmes, conversations, music, and community broadcasting in Akure.';
        $firstPosition = $oaps->firstItem() ?: 1;
        $people = $oaps->getCollection()
            ->values()
            ->map(function (OAP $oap, int $index) use ($firstPosition) {
                $url = route('oaps.show', $oap->slug);

                return [
                    '@type' => 'ListItem',
                    'position' => $firstPosition + $index,
                    'url' => $url,
                    'item' => Seo::person([
                        'name' => $oap->name,
                        'role' => $oap->teamRole?->name ?? 'On-Air Personality',
                        'bio' => $oap->bio,
                        'photo' => $oap->profile_photo,
                        'social_links' => $oap->public_social_links,
                    ], $url),
                ];
            })
            ->all();

        return view('livewire.page.oap-directory', [
            'oaps' => $oaps,
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
                    ['name' => 'Presenters', 'url' => route('oaps.index')],
                ],
                'mainEntity' => [
                    '@type' => 'ItemList',
                    'name' => 'Glow FM presenters',
                    'numberOfItems' => $oaps->total(),
                    'itemListElement' => $people,
                ],
            ]),
        ]);
    }
}
