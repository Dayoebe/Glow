<?php

namespace App\Livewire\Page;

use App\Models\News\News;
use Illuminate\Support\Str;
use Livewire\Component;

class NewsDetail extends Component
{
    public News $news;
    public $comment = '';
    public $userReactions = [];
    public $isBookmarked = false;
    public $shareUrl = '';

    public function mount($slug)
    {
        $this->news = News::with(['category', 'author', 'comments.user'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $this->news->incrementViews(request()->ip(), auth()->id());
        $this->shareUrl = route('news.show', $this->news->slug);

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
        
        $rawUrl = $this->shareUrl ?: url()->current();
        $url = urlencode($rawUrl);
        $title = $this->news->title;
        $excerpt = trim($this->news->excerpt ?? '');
        if ($excerpt === '') {
            $excerpt = Str::limit(strip_tags($this->news->content ?? ''), 180);
        }
        $shareText = trim($title . ' - ' . $excerpt);
        $textWithUrl = urlencode($shareText . ' ' . $rawUrl);
        $encodedTitle = urlencode($title);
        $encodedShareText = urlencode($shareText);
        $redditTitle = urlencode(Str::limit($shareText, 200));
        
        $shareUrls = [
            'x' => "https://x.com/intent/post?text={$textWithUrl}",
            'twitter' => "https://x.com/intent/post?text={$textWithUrl}",
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u={$url}",
            'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url={$url}",
            'whatsapp' => "https://wa.me/?text={$textWithUrl}",
            'telegram' => "https://t.me/share/url?url={$url}&text={$encodedShareText}",
            'reddit' => "https://www.reddit.com/submit?url={$url}&title={$redditTitle}",
            'email' => "mailto:?subject={$encodedTitle}&body={$textWithUrl}",
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
        $excerpt = trim($this->news->excerpt ?? '');
        if ($excerpt === '') {
            $excerpt = Str::limit(strip_tags($this->news->content ?? ''), 180);
        }

        return view('livewire.page.news-detail', [
            'relatedNews' => $this->relatedNews,
            'reactions' => $this->news->getAllReactionCounts(),
        ])->layout('layouts.app', [
            'title' => $this->news->title . ' - Glow FM News',
            'meta_title' => $this->news->title . ' - Glow FM News',
            'meta_description' => $excerpt,
            'meta_image' => $this->news->featured_image,
            'canonical_url' => $this->shareUrl ?: request()->url(),
        ]);
    }
}
