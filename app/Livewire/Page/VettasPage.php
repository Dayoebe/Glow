<?php

namespace App\Livewire\Page;

use App\Mail\VettasReservationSubmittedMail;
use App\Models\Setting;
use App\Models\Vettas\VettasCategory;
use App\Models\Vettas\VettasPhoto;
use App\Models\Vettas\VettasReservation;
use App\Support\VettasPageSettings;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class VettasPage extends Component
{
    use WithPagination;

    public string $category = '';
    public string $search = '';
    public string $sortBy = 'latest';
    public array $pageContent = [];
    public string $full_name = '';
    public string $email = '';
    public string $phone = '';
    public string $check_in_date = '';
    public string $check_out_date = '';
    public string $guest_count = '1';
    public string $special_requests = '';

    protected $queryString = [
        'category' => ['except' => ''],
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
    ];

    public function mount(): void
    {
        $this->pageContent = array_replace_recursive(
            VettasPageSettings::defaults(),
            Setting::get('vettas', [])
        );

        if (auth()->check()) {
            $this->full_name = (string) auth()->user()->name;
            $this->email = (string) auth()->user()->email;
        }
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSortBy(): void
    {
        $this->resetPage();
    }

    public function filterByCategory(string $slug = ''): void
    {
        $this->category = $slug;
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['category', 'search', 'sortBy']);
        $this->sortBy = 'latest';
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'full_name' => 'required|string|min:3|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|min:7|max:40',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guest_count' => 'required|integer|min:1|max:12',
            'special_requests' => 'nullable|string|max:2000',
        ];
    }

    public function submitReservation(): void
    {
        $validated = $this->validate();

        $reservation = VettasReservation::create([
            'user_id' => auth()->id(),
            'full_name' => trim($validated['full_name']),
            'email' => trim($validated['email']),
            'phone' => trim($validated['phone']),
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'guest_count' => (int) $validated['guest_count'],
            'status' => 'new',
            'special_requests' => $this->normalizeOptionalString($validated['special_requests'] ?? ''),
            'source' => 'website',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        try {
            Mail::to(config('mail.vettas_reservations_to', 'chairman@glowfmradio.com'))
                ->send(new VettasReservationSubmittedMail($reservation));
        } catch (\Throwable $exception) {
            report($exception);
        }

        $this->reset([
            'phone',
            'check_in_date',
            'check_out_date',
            'special_requests',
        ]);

        $this->guest_count = '1';

        if (!auth()->check()) {
            $this->reset(['full_name', 'email']);
        }

        session()->flash('vettas_reservation_success', 'Your reservation request has been received. We will confirm availability with you shortly.');
    }

    public function getCategoriesProperty()
    {
        return VettasCategory::query()
            ->active()
            ->whereHas('photos', fn ($query) => $query->published())
            ->withCount([
                'photos as published_photos_count' => fn ($query) => $query->published(),
            ])
            ->ordered()
            ->get();
    }

    public function getFeaturedPhotosProperty()
    {
        return $this->applyPhotoFilters(
            VettasPhoto::query()
                ->with('category')
                ->published()
                ->featured()
                ->whereHas('category', fn ($query) => $query->active())
        )
            ->ordered()
            ->take(4)
            ->get();
    }

    public function getAboutContentProperty(): array
    {
        return $this->pageContent['about'] ?? [];
    }

    public function getContactContentProperty(): array
    {
        return $this->pageContent['contact'] ?? [];
    }

    public function getContactMethodsProperty(): array
    {
        $contact = $this->contactContent;

        return collect([
            [
                'label' => 'Phone',
                'value' => trim((string) ($contact['phone'] ?? '')),
                'icon' => 'fas fa-phone-alt',
                'href' => $this->phoneUrl($contact['phone'] ?? ''),
                'accent' => 'emerald',
            ],
            [
                'label' => 'WhatsApp',
                'value' => trim((string) ($contact['whatsapp'] ?? '')),
                'icon' => 'fab fa-whatsapp',
                'href' => $this->whatsappUrl($contact['whatsapp'] ?? ''),
                'accent' => 'green',
            ],
            [
                'label' => 'Email',
                'value' => trim((string) ($contact['email'] ?? '')),
                'icon' => 'fas fa-envelope',
                'href' => $this->emailUrl($contact['email'] ?? ''),
                'accent' => 'slate',
            ],
        ])
            ->filter(fn (array $item) => filled($item['value']) && filled($item['href']))
            ->values()
            ->all();
    }

    public function getHasContactDetailsProperty(): bool
    {
        return count($this->contactMethods) > 0
            || filled($this->contactContent['address'] ?? '')
            || filled($this->contactContent['hours'] ?? '')
            || filled($this->contactContent['booking_note'] ?? '')
            || filled($this->contactContent['instagram'] ?? '')
            || filled($this->contactContent['website'] ?? '');
    }

    public function getInstagramLinkProperty(): ?string
    {
        $instagram = trim((string) ($this->contactContent['instagram'] ?? ''));

        if ($instagram === '') {
            return null;
        }

        if (Str::startsWith($instagram, ['http://', 'https://'])) {
            return $instagram;
        }

        return 'https://instagram.com/' . ltrim($instagram, '@/');
    }

    public function getWebsiteLinkProperty(): ?string
    {
        $website = trim((string) ($this->contactContent['website'] ?? ''));

        if ($website === '') {
            return null;
        }

        return Str::startsWith($website, ['http://', 'https://'])
            ? $website
            : 'https://' . ltrim($website, '/');
    }

    public function getPhotosProperty()
    {
        $query = $this->applyPhotoFilters(
            VettasPhoto::query()
            ->with('category')
            ->published()
            ->whereHas('category', fn ($builder) => $builder->active())
        );

        $this->applyPhotoSorting($query);

        return $query->paginate(12);
    }

    private function applyPhotoFilters($query)
    {
        if ($this->category !== '') {
            $query->whereHas('category', function ($builder) {
                $builder->where('slug', $this->category);
            });
        }

        if ($this->search !== '') {
            $searchTerm = $this->search;

            $query->where(function ($builder) use ($searchTerm) {
                $builder->search($searchTerm)
                    ->orWhereHas('category', function ($categoryQuery) use ($searchTerm) {
                        $categoryQuery->where('name', 'like', "%{$searchTerm}%")
                            ->orWhere('description', 'like', "%{$searchTerm}%");
                    });
            });
        }

        return $query;
    }

    private function applyPhotoSorting($query): void
    {
        switch ($this->sortBy) {
            case 'oldest':
                $query->orderByRaw('captured_at is null')
                    ->orderBy('captured_at')
                    ->orderByRaw('published_at is null')
                    ->orderBy('published_at')
                    ->orderBy('id');
                break;

            case 'category':
                $query->leftJoin('vettas_categories as sort_categories', 'sort_categories.id', '=', 'vettas_photos.category_id')
                    ->select('vettas_photos.*')
                    ->orderBy('sort_categories.name')
                    ->orderByDesc('vettas_photos.is_featured')
                    ->orderBy('vettas_photos.display_order')
                    ->orderByDesc('vettas_photos.captured_at')
                    ->orderByDesc('vettas_photos.published_at')
                    ->orderByDesc('vettas_photos.id');
                break;

            case 'featured':
                $query->orderByDesc('is_featured')
                    ->orderBy('display_order')
                    ->orderByDesc('captured_at')
                    ->orderByDesc('published_at')
                    ->orderByDesc('id');
                break;

            default:
                $query->ordered();
                break;
        }
    }

    public function render()
    {
        $activeCategory = $this->categories->firstWhere('slug', $this->category);
        $metaDescription = $this->aboutContent['summary']
            ?? 'Explore the Vettas gallery on Glow FM with curated photos organized by category.';

        return view('livewire.page.vettas-page', [
            'photos' => $this->photos,
            'categories' => $this->categories,
            'featuredPhotos' => $this->featuredPhotos,
            'activeCategory' => $activeCategory,
            'aboutContent' => $this->aboutContent,
            'contactContent' => $this->contactContent,
            'contactMethods' => $this->contactMethods,
            'hasContactDetails' => $this->hasContactDetails,
            'instagramLink' => $this->instagramLink,
            'websiteLink' => $this->websiteLink,
        ])->layout('layouts.app', [
            'title' => 'Vettas - Glow FM',
            'meta_title' => 'Vettas - Glow FM',
            'meta_description' => $metaDescription,
            'meta_image' => $this->featuredPhotos->first()?->image_path,
        ]);
    }

    private function phoneUrl(string $phone): ?string
    {
        $trimmed = trim($phone);

        if ($trimmed === '') {
            return null;
        }

        $normalized = preg_replace('/[^0-9+]/', '', $trimmed);

        if ($normalized === '') {
            return null;
        }

        return 'tel:' . $normalized;
    }

    private function whatsappUrl(string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        if ($digits === '') {
            return null;
        }

        return 'https://wa.me/' . $digits;
    }

    private function emailUrl(string $email): ?string
    {
        $trimmed = trim($email);

        if ($trimmed === '') {
            return null;
        }

        return 'mailto:' . $trimmed;
    }

    private function normalizeOptionalString(mixed $value): ?string
    {
        $normalized = trim((string) $value);

        return $normalized === '' ? null : $normalized;
    }
}
