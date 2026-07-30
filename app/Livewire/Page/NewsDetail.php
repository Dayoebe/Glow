<?php

namespace App\Livewire\Page;

use App\Models\News\News;
use App\Support\Seo;
use Illuminate\Support\Str;
use Livewire\Component;

class NewsDetail extends Component
{
    public News $news;
    public $comment = '';
    public $userReactions = [];
    public $isBookmarked = false;
    public $shareUrl = '';
    public $qualifiedViewRecorded = false;

    public function mount($slug)
    {
        $this->news = News::with(['category', 'author', 'comments.user'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $this->news->incrementRawView();
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
        $rawUrl = url($rawUrl);
        $url = urlencode($rawUrl);
        $title = $this->news->title;
        $excerpt = trim($this->news->excerpt ?? '');
        if ($excerpt === '') {
            $excerpt = Str::limit(strip_tags($this->news->content ?? ''), 180);
        }
        
        // For share tracking and building share text
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

        $shareUrl = $shareUrls[$platform] ?? $rawUrl;
        if (method_exists($this, 'dispatchBrowserEvent')) {
            $this->dispatchBrowserEvent('open-share-url', ['url' => $shareUrl]);
        }
        $this->dispatch('open-share-url', url: $shareUrl);
    }

    public function recordQualifiedView()
    {
        if ($this->qualifiedViewRecorded) {
            return;
        }

        $this->qualifiedViewRecorded = true;
        $this->news->incrementViews(request()->ip(), auth()->id());
    }

    public function getRelatedNewsProperty()
    {
        return News::with('category')
            ->published()
            ->where('category_id', $this->news->category_id)
            ->where('id', '!=', $this->news->id)
            ->latest('published_at')
            ->take(3)
            ->get();
    }

    public function getArticleSummaryProperty(): string
    {
        $source = trim(($this->news->excerpt ?? '') . ' ' . Seo::text($this->news->content ?? '', 500));

        return Seo::words($source, 58);
    }

    public function getKeyTakeawaysProperty(): array
    {
        $source = trim(($this->news->excerpt ?? '') . ' ' . ($this->news->content ?? ''));
        $takeaways = Seo::sentences($source, 3);

        if (count($takeaways) < 3 && $this->news->category?->name) {
            $takeaways[] = 'This story is filed under ' . $this->news->category->name . ' on Glow 99.1 FM.';
        }

        if (count($takeaways) < 3 && $this->news->published_at) {
            $takeaways[] = 'It was published on ' . $this->news->published_at->format('F j, Y') . '.';
        }

        return array_slice(array_values(array_unique(array_filter($takeaways))), 0, 3);
    }

    public function getArticleFaqsProperty(): array
    {
        return [
            [
                'question' => 'What is this story about?',
                'answer' => $this->articleSummary ?: 'This is a public news story published by Glow 99.1 FM.',
            ],
            [
                'question' => 'Who published this story?',
                'answer' => 'Glow 99.1 FM published this story' . ($this->news->author?->name ? ' with ' . $this->news->author->name . ' listed as author.' : '.'),
            ],
            [
                'question' => 'When was it published?',
                'answer' => $this->news->published_at
                    ? $this->news->published_at->format('F j, Y')
                    : 'A public published date is not available on this page.',
            ],
        ];
    }

    public function render()
    {
        $excerpt = trim($this->news->meta_description ?: $this->news->excerpt ?? '');
        if ($excerpt === '') {
            $excerpt = Str::limit(strip_tags($this->news->content ?? ''), 180);
        }

        $canonical = $this->shareUrl ?: route('news.show', $this->news->slug);
        $extraSchema = [
            Seo::newsArticle($this->news, $canonical, $excerpt),
        ];

        $videoObject = Seo::videoObject(
            $this->news->title,
            $excerpt,
            $this->news->video_url,
            $this->news->featured_image,
            $this->news->published_at
        );

        if ($videoObject) {
            $extraSchema[] = $videoObject;
        }

        return view('livewire.page.news-detail', [
            'relatedNews' => $this->relatedNews,
            'reactions' => $this->news->getAllReactionCounts(),
            'articleSummary' => $this->articleSummary,
            'keyTakeaways' => $this->keyTakeaways,
            'articleFaqs' => $this->articleFaqs,
        ])->layout('layouts.app', [
            'title' => $this->news->title . ' - Glow 99.1 FM News',
            'meta_title' => $this->news->title,
            'meta_description' => $excerpt,
            'meta_image' => $this->news->featured_image,
            'meta_image_alt' => $this->news->title,
            'meta_type' => 'article',
            'meta_published_time' => $this->news->published_at?->toAtomString(),
            'meta_modified_time' => $this->news->updated_at?->toAtomString(),
            'canonical_url' => $canonical,
            'structured_data' => Seo::siteGraph([
                'title' => $this->news->title,
                'description' => $excerpt,
                'url' => $canonical,
                'image' => $this->news->featured_image,
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'News', 'url' => route('news')],
                    ['name' => $this->news->category?->name ?? 'Article', 'url' => route('news', ['selectedCategory' => $this->news->category?->slug])],
                    ['name' => $this->news->title, 'url' => $canonical],
                ],
                'extra' => $extraSchema,
            ]),
        ]);
    }
}
