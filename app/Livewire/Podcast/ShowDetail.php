<?php

namespace App\Livewire\Podcast;

use App\Models\Podcast\Show;
use App\Models\Podcast\Subscription;
use App\Models\Podcast\Review;
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
        if (!auth()->check()) {
            session()->flash('error', 'Please login to review');
            return redirect()->route('login');
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|max:500',
        ]);

        Review::updateOrCreate(
            ['user_id' => auth()->id(), 'show_id' => $this->show->id],
            ['rating' => $this->rating, 'review' => $this->review]
        );

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
        return view('livewire.podcast.show-detail', [
            'episodes' => $this->episodes,
            'seasons' => $this->seasons,
        ])->layout('layouts.app', ['title' => $this->show->title . ' - Podcast - Glow FM']);
    }
}