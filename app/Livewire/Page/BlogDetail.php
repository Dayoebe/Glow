<?php

namespace App\Livewire\Page;

use App\Models\Blog\Post;
use App\Models\Blog\Comment;
use Illuminate\Support\Str;
use Livewire\Component;

class BlogDetail extends Component
{
    public Post $post;
    public $comment = '';
    public $replyTo = null;
    public $userReactions = [];
    public $isBookmarked = false;
    public $qualifiedViewRecorded = false;

    protected $rules = [
        'comment' => 'required|min:3|max:1000',
    ];

    public function mount($slug)
    {
        // Find post by slug with relationships
        $query = Post::with([
            'category', 
            'author', 
            'comments' => function($query) {
                $query->where('is_approved', true)
                      ->whereNull('parent_id')
                      ->latest();
            },
            'comments.user',
            'comments.replies' => function($query) {
                $query->where('is_approved', true);
            }
        ]);

        if (!request()->routeIs('admin.blog.preview')) {
            $query->published();
        }

        $this->post = $query->where('slug', $slug)->firstOrFail();

        if (!request()->routeIs('admin.blog.preview')) {
            $this->post->incrementRawView();
        }

        // Load user interactions if authenticated
        if (auth()->check()) {
            $this->loadUserInteractions();
        }
    }

    private function loadUserInteractions()
    {
        $userId = auth()->id();
        
        // Get user reactions
        $reactions = $this->post->interactions()
            ->where('user_id', $userId)
            ->where('type', 'reaction')
            ->pluck('value')
            ->toArray();
        
        $this->userReactions = array_fill_keys($reactions, true);
        
        // Check if bookmarked
        $this->isBookmarked = $this->post->isBookmarkedBy($userId);
    }

    public function toggleReaction($type)
    {
        if (!auth()->check()) {
            session()->flash('error', 'Please login to react');
            return redirect()->route('login');
        }

        $toggled = $this->post->toggleReaction(auth()->id(), $type);
        
        if ($toggled) {
            $this->userReactions[$type] = true;
        } else {
            unset($this->userReactions[$type]);
        }

        $this->post->refresh();
    }

    public function toggleBookmark()
    {
        if (!auth()->check()) {
            session()->flash('error', 'Please login to bookmark');
            return redirect()->route('login');
        }

        $toggled = $this->post->toggleBookmark(auth()->id());
        $this->isBookmarked = $toggled;
        
        session()->flash('success', $toggled ? 
            'Added to reading list' : 
            'Removed from reading list'
        );
    }

    public function recordQualifiedView()
    {
        if ($this->qualifiedViewRecorded) {
            return;
        }

        $this->qualifiedViewRecorded = true;
        $this->post->incrementViews(request()->ip(), auth()->id());
    }

    public function setReplyTo($commentId)
    {
        $this->replyTo = $commentId;
    }

    public function cancelReply()
    {
        $this->replyTo = null;
    }

    public function submitComment()
    {
        $this->validate();

        if ($this->replyTo) {
            $validParent = $this->post->comments()
                ->where('id', $this->replyTo)
                ->where('is_approved', true)
                ->exists();
            if (!$validParent) {
                $this->replyTo = null;
            }
        }

        $this->post->comments()->create([
            'user_id' => auth()->id(),
            'parent_id' => $this->replyTo,
            'comment' => $this->comment,
            'is_approved' => true,
        ]);

        $this->comment = '';
        $this->replyTo = null;
        $this->post->refresh();
        
        session()->flash('success', 'Comment posted successfully!');
    }

    public function sharePost($platform)
    {
        $this->post->trackShare($platform);
        
        $rawUrl = route('blog.show', $this->post->slug);
        $url = urlencode($rawUrl);
        $title = $this->post->title;
        $excerpt = trim($this->post->excerpt ?? '');
        if ($excerpt === '') {
            $excerpt = Str::limit(strip_tags($this->post->content ?? ''), 180);
        }
        $shareText = trim($title . ' - ' . $excerpt);
        $textWithUrl = urlencode($shareText . ' ' . $rawUrl);
        $encodedTitle = urlencode($title);
        $encodedShareText = urlencode($shareText);
        $redditTitle = urlencode(Str::limit($shareText, 200));
        
        $shareUrls = [
            'x' => "https://x.com/intent/post?text={$textWithUrl}",
            'twitter' => "https://x.com/intent/post?text={$textWithUrl}",
            'facebook' => "https://m.facebook.com/sharer.php?u={$url}&quote={$encodedShareText}",
            'instagram' => "https://www.instagram.com/",
            'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url={$url}",
            'whatsapp' => "https://wa.me/?text={$textWithUrl}",
            'telegram' => "https://t.me/share/url?url={$url}&text={$encodedShareText}",
            'reddit' => "https://www.reddit.com/submit?url={$url}&title={$redditTitle}",
            'email' => "mailto:?subject={$encodedTitle}&body={$textWithUrl}",
        ];

        $shareUrl = $shareUrls[$platform] ?? route('blog.show', $this->post->slug);
        if (method_exists($this, 'dispatchBrowserEvent')) {
            $this->dispatchBrowserEvent('open-share-url', ['url' => $shareUrl]);
        }
        $this->dispatch('open-share-url', url: $shareUrl);
    }

    public function getRelatedPostsProperty()
    {
        return Post::with(['category', 'author'])
            ->published()
            ->where('category_id', $this->post->category_id)
            ->where('id', '!=', $this->post->id)
            ->latest('published_at')
            ->take(3)
            ->get();
    }

    public function getSeriesPostsProperty()
    {
        if (!$this->post->series) {
            return collect();
        }
        
        return Post::published()
            ->where('series', $this->post->series)
            ->orderBy('series_order')
            ->get();
    }

    public function render()
    {
        $excerpt = trim($this->post->excerpt ?? '');
        if ($excerpt === '') {
            $excerpt = Str::limit(strip_tags($this->post->content ?? ''), 180);
        }

        return view('livewire.page.blog-detail', [
            'relatedPosts' => $this->relatedPosts,
            'seriesPosts' => $this->seriesPosts,
            'reactions' => $this->post->getAllReactionCounts(),
        ])->layout('layouts.app', [
            'title' => $this->post->title . ' - Glow FM Blog',
            'meta_title' => $this->post->title . ' - Glow FM Blog',
            'meta_description' => $excerpt,
            'meta_image' => $this->post->featured_image,
            'meta_image_alt' => $this->post->title,
            'meta_type' => 'article',
            'canonical_url' => route('blog.show', $this->post->slug),
        ]);
    }
}
