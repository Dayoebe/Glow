<?php

namespace App\Livewire\Page;

use App\Models\News\News;
use Livewire\Component;

class NewsDetail extends Component
{
    public News $news;
    public $comment = '';
    public $userReactions = [];
    public $isBookmarked = false;

    public function mount($slug)
    {
        $this->news = News::with(['category', 'author', 'comments.user'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $this->news->incrementViews(request()->ip(), auth()->id());

        if (auth()->check()) {
            $this->loadUserInteractions();
        }
    }

    private function loadUserInteractions()
    {
        $userId = auth()->id();
        
        // Get user reactions
        $reactions = $this->news->interactions()
            ->where('user_id', $userId)
            ->where('type', 'reaction')
            ->pluck('value')
            ->toArray();
        
        $this->userReactions = array_fill_keys($reactions, true);
        
        // Check if bookmarked
        $this->isBookmarked = $this->news->isBookmarkedBy($userId);
    }

    public function toggleReaction($type)
    {
        if (!auth()->check()) {
            session()->flash('error', 'Please login to react');
            return redirect()->route('login');
        }

        $toggled = $this->news->toggleReaction(auth()->id(), $type);
        
        if ($toggled) {
            $this->userReactions[$type] = true;
        } else {
            unset($this->userReactions[$type]);
        }

        $this->news->refresh();
    }

    public function toggleBookmark()
    {
        if (!auth()->check()) {
            session()->flash('error', 'Please login to bookmark');
            return redirect()->route('login');
        }

        $toggled = $this->news->toggleBookmark(auth()->id());
        $this->isBookmarked = $toggled;
        
        session()->flash('success', $toggled ? 
            'Added to reading list' : 
            'Removed from reading list'
        );
    }

    public function submitComment()
    {
        if (!auth()->check()) {
            session()->flash('error', 'Please login to comment');
            return redirect()->route('login');
        }

        $this->validate(['comment' => 'required|min:3|max:500']);

        $this->news->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $this->comment,
            'is_approved' => true,
        ]);

        $this->comment = '';
        $this->news->refresh();
        
        session()->flash('success', 'Comment posted successfully!');
    }

    public function shareNews($platform)
    {
        $this->news->trackShare($platform);
        
        $url = urlencode(url()->current());
        $title = urlencode($this->news->title);
        
        $shareUrls = [
            'twitter' => "https://twitter.com/intent/tweet?url={$url}&text={$title}",
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u={$url}",
            'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url={$url}",
            'whatsapp' => "https://wa.me/?text={$title} {$url}",
            'telegram' => "https://t.me/share/url?url={$url}&text={$title}",
        ];

        return redirect()->away($shareUrls[$platform] ?? url()->current());
    }

    public function getRelatedNewsProperty()
    {
        return News::published()
            ->where('category_id', $this->news->category_id)
            ->where('id', '!=', $this->news->id)
            ->latest('published_at')
            ->take(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.page.news-detail', [
            'relatedNews' => $this->relatedNews,
            'reactions' => $this->news->getAllReactionCounts(),
        ])->layout('layouts.app', [
            'title' => $this->news->title . ' - Glow FM News'
        ]);
    }
}