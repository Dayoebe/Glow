<?php

namespace App\Livewire\Page;

use App\Models\Show\Show;
use App\Models\Show\Review;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Livewire\Component;

class ShowDetail extends Component
{
    public Show $show;
    public $rating = 0;
    public $review = '';

    public function mount($slug)
    {
        $this->show = Show::with(['category', 'primaryHost', 'segments', 'scheduleSlots'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $this->trackListener();
        $this->ensureDeviceId();
        $this->loadUserReview();
    }

    public function getUpcomingSlotsProperty()
    {
        return $this->show->scheduleSlots()
            ->active()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
    }

    private function loadUserReview(): void
    {
        $deviceHash = $this->getDeviceHash();
        $existing = null;

        if ($deviceHash) {
            $existing = Review::where('show_id', $this->show->id)
                ->where('device_hash', $deviceHash)
                ->first();
        }

        if (!$existing && auth()->check()) {
            $existing = Review::where('show_id', $this->show->id)
                ->where('user_id', auth()->id())
                ->first();
        }

        if ($existing) {
            $this->rating = $existing->rating;
            $this->review = $existing->review ?? '';
        }
    }

    private function ensureDeviceId(): void
    {
        if (!request()->cookie('device_id')) {
            Cookie::queue('device_id', (string) Str::uuid(), 60 * 24 * 365);
        }
    }

    private function trackListener(): void
    {
        $cookieKey = 'show_viewed_' . $this->show->id;

        if (request()->cookie($cookieKey)) {
            return;
        }

        $this->show->increment('total_listeners');
        Cookie::queue($cookieKey, '1', 60 * 24);
    }

    private function getDeviceHash(): ?string
    {
        $deviceId = request()->cookie('device_id');
        if (!$deviceId) {
            return null;
        }

        return hash('sha256', $deviceId);
    }

    public function submitReview()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $deviceHash = $this->getDeviceHash();

        $existingByDevice = $deviceHash
            ? Review::where('show_id', $this->show->id)->where('device_hash', $deviceHash)->exists()
            : false;

        if ($existingByDevice) {
            session()->flash('error', 'You have already rated this show on this device.');
            return;
        }

        if (auth()->check()) {
            $existingByUser = Review::where('show_id', $this->show->id)
                ->where('user_id', auth()->id())
                ->exists();

            if ($existingByUser) {
                session()->flash('error', 'You have already rated this show.');
                return;
            }
        }

        Review::create([
            'show_id' => $this->show->id,
            'user_id' => auth()->id(),
            'device_hash' => $deviceHash,
            'rating' => $this->rating,
            'review' => $this->review ?: null,
            'is_approved' => true,
        ]);

        $averageRating = $this->show->reviews()->approved()->avg('rating');
        $this->show->average_rating = $averageRating ?: 0;
        $this->show->save();

        $this->show->load('reviews.user');
        session()->flash('success', 'Thanks for your review!');
    }

    public function render()
    {
        $reviewsQuery = $this->show->reviews()->approved();
        $ratingCount = (clone $reviewsQuery)->count();
        $averageRating = (clone $reviewsQuery)->avg('rating') ?: 0;

        return view('livewire.page.show-detail', [
            'upcomingSlots' => $this->upcomingSlots,
            'reviews' => $this->show->reviews()->approved()->latest()->with('user')->get(),
            'ratingCount' => $ratingCount,
            'averageRating' => $averageRating,
        ])->layout('layouts.app', [
            'title' => $this->show->title . ' - Glow FM',
            'meta_title' => $this->show->title . ' - Glow FM',
            'meta_description' => Str::limit(strip_tags($this->show->description ?? ''), 180),
            'meta_image' => $this->show->cover_image,
        ]);
    }
}
