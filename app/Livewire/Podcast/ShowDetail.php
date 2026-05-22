<?php

namespace App\Livewire\Podcast;

use App\Models\Podcast\Show;
use App\Models\Podcast\Subscription;
use App\Models\Podcast\Review;
use App\Support\Seo;
use Illuminate\Support\Str;
use Livewire\Component;

class ShowDetail extends Component
{
    public Show $show;
    public $isSubscribed = false;
    public $selectedSeason = 'all';
    public $sortBy = 'latest'; // latest, oldest, popular
    
    // Review form
    public $rating = 5;
    public $review = '';
    public $showReviewForm = false;

    public function mount($slug)
    {
        $this->show = Show::with(['host', 'publishedEpisodes', 'reviews.user'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        if (auth()->check()) {
            $this->checkSubscription();
        }
    }

    private function checkSubscription()
    {
        $this->isSubscribed = Subscription::where('user_id', auth()->id())
            ->where('show_id', $this->show->id)
            ->exists();
    }

    public function toggleSubscribe()
    {
        if (!auth()->check()) {
            session()->flash('error', 'Please login to subscribe');
            return redirect()->route('login');
        }

        if ($this->isSubscribed) {
            Subscription::where('user_id', auth()->id())
                ->where('show_id', $this->show->id)
                ->delete();
            $this->show->decrementSubscribers();
            $this->isSubscribed = false;
            session()->flash('success', 'Unsubscribed successfully!');
        } else {
            Subscription::create([
                'user_id' => auth()->id(),
                'show_id' => $this->show->id,
                'subscribed_at' => now(),
            ]);
            $this->show->incrementSubscribers();
            $this->isSubscribed = true;
            session()->flash('success', 'Subscribed successfully!');
        }

        $this->show->refresh();
    }

    public function submitReview()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|max:500',
        ]);

        if (auth()->check()) {
            Review::updateOrCreate(
                ['user_id' => auth()->id(), 'show_id' => $this->show->id],
                ['rating' => $this->rating, 'review' => $this->review]
            );
        } else {
            Review::create([
                'user_id' => null,
                'show_id' => $this->show->id,
                'rating' => $this->rating,
                'review' => $this->review ?: null,
            ]);
        }

        $this->showReviewForm = false;
        $this->reset(['rating', 'review']);
        $this->show->refresh();
        
        session()->flash('success', 'Review submitted successfully!');
    }

    public function getEpisodesProperty()
    {
        $query = $this->show->publishedEpisodes();

        if ($this->selectedSeason !== 'all') {
            $query->bySeason($this->selectedSeason);
        }

        switch ($this->sortBy) {
            case 'oldest':
                $query->oldest('published_at');
                break;
            case 'popular':
                $query->orderBy('plays', 'desc');
                break;
            default:
                $query->latest('published_at');
        }

        return $query->get();
    }

    public function getSeasonsProperty()
    {
        return $this->show->publishedEpisodes()
            ->whereNotNull('season_number')
            ->distinct()
            ->pluck('season_number')
            ->sort()
            ->values();
    }

    public function render()
    {
        $description = Str::limit(strip_tags($this->show->description ?? ''), 180);
        $canonical = route('podcasts.show', $this->show->slug);
        $episodes = $this->episodes;
        $episodeItems = $episodes
            ->take(30)
            ->values()
            ->map(fn ($episode, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $episode->title,
                'url' => route('podcasts.episode', ['showSlug' => $this->show->slug, 'episodeSlug' => $episode->slug]),
                'description' => Seo::text($episode->description, 140),
            ])
            ->all();

        return view('livewire.podcast.show-detail', [
            'episodes' => $episodes,
            'seasons' => $this->seasons,
        ])->layout('layouts.app', [
            'title' => $this->show->title . ' - Podcast - Glow 99.1 FM',
            'meta_title' => $this->show->title . ' - Podcast - Glow 99.1 FM',
            'meta_description' => $description,
            'meta_image' => $this->show->cover_image,
            'meta_image_alt' => $this->show->title,
            'canonical_url' => $canonical,
            'structured_data' => Seo::siteGraph([
                'title' => $this->show->title . ' - Glow 99.1 FM Podcast',
                'description' => $description,
                'url' => $canonical,
                'image' => $this->show->cover_image,
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Podcasts', 'url' => route('podcasts.index')],
                    ['name' => $this->show->title, 'url' => $canonical],
                ],
                'extra' => [
                    [
                        '@type' => 'PodcastSeries',
                        '@id' => $canonical . '#podcast-series',
                        'name' => $this->show->title,
                        'description' => $description,
                        'url' => $canonical,
                        'publisher' => ['@id' => Seo::station()['url'] . '/#organization'],
                        'episode' => [
                            '@type' => 'ItemList',
                            'itemListElement' => $episodeItems,
                        ],
                    ],
                ],
            ]),
        ]);
    }
}
