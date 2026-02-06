<?php

namespace App\Livewire\Podcast;

use App\Models\Podcast\Episode;
use App\Models\Podcast\Comment;
use App\Models\Podcast\ListeningHistory;
use Illuminate\Support\Str;
use Livewire\Component;

class EpisodePlayer extends Component
{
    public Episode $episode;
    public $comment = '';
    public $commentTimestamp = null;
    public $replyTo = null;
    public $qualifiedPlayRecorded = false;
    public $rawPlayRecorded = false;
    
    // Player state
    public $currentPosition = 0;
    public $isPlaying = false;

    // FIX: Changed from $slug to $showSlug and $episodeSlug
    public function mount($showSlug, $episodeSlug)
    {
        $this->episode = Episode::with(['show', 'comments.user', 'comments.replies.user'])
            ->published()
            ->where('slug', $episodeSlug)
            ->whereHas('show', function($query) use ($showSlug) {
                $query->where('slug', $showSlug);
            })
            ->firstOrFail();

        // Load last position if user is logged in
        if (auth()->check()) {
            $history = ListeningHistory::where('user_id', auth()->id())
                ->where('episode_id', $this->episode->id)
                ->first();

            if ($history) {
                $this->currentPosition = $history->position;
            }
        }
    }

    public function updateProgress($position, $duration)
    {
        $this->currentPosition = $position;

        // Track play and update history
        if (auth()->check()) {
            ListeningHistory::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'episode_id' => $this->episode->id,
                ],
                [
                    'position' => $position,
                    'completed' => $position >= ($duration * 0.9),
                    'last_listened_at' => now(),
                ]
            );
        }

        // Track analytics every 30 seconds
        if ($position % 30 === 0) {
            $this->episode->trackPlay(auth()->id(), $position, $position);
        }
    }

    public function recordQualifiedPlay()
    {
        if ($this->qualifiedPlayRecorded) {
            return;
        }

        $this->qualifiedPlayRecorded = true;
        $this->episode->recordQualifiedPlay(auth()->id(), $this->currentPosition, $this->currentPosition);
    }

    public function recordRawPlay()
    {
        if ($this->rawPlayRecorded) {
            return;
        }

        $this->rawPlayRecorded = true;
        $this->episode->recordRawPlay();
    }

    public function trackDownload()
    {
        $this->episode->trackDownload();
        session()->flash('success', 'Download started!');
    }

    public function submitComment()
    {
        $this->validate(['comment' => 'required|min:3|max:500']);

        Comment::create([
            'episode_id' => $this->episode->id,
            'user_id' => auth()->id(),
            'parent_id' => $this->replyTo,
            'comment' => $this->comment,
            'timestamp' => $this->commentTimestamp,
        ]);

        $this->reset(['comment', 'commentTimestamp', 'replyTo']);
        $this->episode->refresh();
        
        session()->flash('success', 'Comment posted!');
    }

    public function setCommentTime($time)
    {
        $this->commentTimestamp = $time;
    }

    public function setReplyTo($commentId)
    {
        $this->replyTo = $commentId;
    }

    public function shareEpisode($platform)
    {
        $this->episode->increment('shares');
        
        $rawUrl = url()->current();
        $url = urlencode($rawUrl);
        $title = $this->episode->title;
        $textWithUrl = urlencode($title . ' ' . $rawUrl);
        $encodedTitle = urlencode($title);
        
        $shareUrls = [
            'x' => "https://x.com/intent/post?text={$textWithUrl}",
            'twitter' => "https://x.com/intent/post?text={$textWithUrl}",
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u={$url}",
            'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url={$url}",
            'whatsapp' => "https://wa.me/?text={$textWithUrl}",
            'telegram' => "https://t.me/share/url?url={$url}&text={$encodedTitle}",
            'reddit' => "https://www.reddit.com/submit?url={$url}&title={$encodedTitle}",
            'email' => "mailto:?subject={$encodedTitle}&body={$textWithUrl}",
        ];

        return redirect()->away($shareUrls[$platform] ?? $rawUrl);
    }

    public function getRelatedEpisodesProperty()
    {
        return Episode::published()
            ->where('show_id', $this->episode->show_id)
            ->where('id', '!=', $this->episode->id)
            ->latest('published_at')
            ->take(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.podcast.episode-player', [
            'relatedEpisodes' => $this->relatedEpisodes,
        ])->layout('layouts.app', [
            'title' => $this->episode->title . ' - ' . $this->episode->show->title,
            'meta_title' => $this->episode->title . ' - ' . $this->episode->show->title,
            'meta_description' => Str::limit(strip_tags($this->episode->description ?? ''), 180),
            'meta_image' => $this->episode->cover_image ?? $this->episode->show->cover_image,
        ]);
    }
}
