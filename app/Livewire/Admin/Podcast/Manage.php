<?php

namespace App\Livewire\Admin\Podcast;

use App\Models\Podcast\Show;
use App\Models\Podcast\Episode;
use App\Models\Setting;
use App\Notifications\ContentApprovalUpdated;
use App\Support\CloudinaryUploader;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Manage extends Component
{
    use WithPagination, WithFileUploads;

    public $view = 'shows';
    public $selectedShow = null;
    public $search = '';
    
    // Modal state
    public $showModal = false;
    public $modalType = 'show';
    public $editMode = false;
    public $itemId = null;
    public $approvalAction = '';
    public $approvalReason = '';
    public $approvalFormId = null;

    // Show form
    public $show_title = '';
    public $show_description = '';
    public $show_cover;
    public $show_cover_url = '';
    public $existing_show_cover = '';
    public $show_host_name = '';
    public $show_category = 'music';
    public $show_category_choice = 'music';
    public $show_category_custom = '';
    public $show_frequency = 'weekly';
    public $show_explicit = false;
    public $show_tags = '';

    protected array $podcastCategoryOptions = [
        'music',
        'talk',
        'interview',
        'tech',
        'lifestyle',
        'education',
    ];

    // Episode form
    public $episode_show_id = '';
    public $episode_title = '';
    public $episode_description = '';
    public $episode_show_notes = '';
    public $episode_audio;
    public $episode_audio_url = '';
    public $existing_episode_audio = '';
    
    // Video fields
    public $episode_video;
    public $episode_video_url = '';
    public $existing_episode_video = '';
    public $episode_video_type = 'youtube';
    
    public $episode_cover;
    public $episode_cover_url = '';
    public $existing_episode_cover = '';
    public $episode_duration = 0;
    public $episode_number = null;
    public $episode_season = null;
    public $episode_type = 'full';
    public $episode_status = 'draft';
    public $episode_explicit = false;
    public $episode_guests = '';
    public $episode_tags = '';
    
    // External platform links
    public $episode_spotify_url = '';
    public $episode_apple_url = '';
    public $episode_youtube_music_url = '';
    public $episode_audiomack_url = '';
    public $episode_soundcloud_url = '';
    
    // Custom links
    public $custom_link_name = '';
    public $custom_link_url = '';
    public $custom_links = [];

    protected $queryString = ['view', 'selectedShow', 'search'];

    public function addCustomLink()
    {
        if (!empty($this->custom_link_name) && !empty($this->custom_link_url)) {
            $this->custom_links[$this->custom_link_name] = $this->custom_link_url;
            $this->reset(['custom_link_name', 'custom_link_url']);
        }
    }

    public function removeCustomLink($name)
    {
        unset($this->custom_links[$name]);
    }

    public function openShowModal($id = null)
    {
        $this->resetForm();
        $this->modalType = 'show';
        
        if ($id) {
            $this->editMode = true;
            $this->itemId = $id;
            $this->loadShow($id);
        } else {
            $this->editMode = false;
        }
        
        $this->showModal = true;
    }

    public function openEpisodeModal($id = null, $showId = null)
    {
        $this->resetForm();
        $this->modalType = 'episode';
        
        if ($id) {
            $this->editMode = true;
            $this->itemId = $id;
            $this->loadEpisode($id);
        } else {
            $this->editMode = false;
            $this->episode_show_id = $showId ?? $this->selectedShow;
        }
        
        $this->showModal = true;
    }

    private function loadShow($id)
    {
        $show = Show::findOrFail($id);
        $this->show_title = $show->title;
        $this->show_description = $show->description;
        $this->show_host_name = $show->host_name;
        $this->show_category = $show->category;
        if ($this->isDefaultPodcastCategory($show->category)) {
            $this->show_category_choice = $show->category;
            $this->show_category_custom = '';
        } else {
            $this->show_category_choice = '__new__';
            $this->show_category_custom = $show->category;
        }
        $this->show_frequency = $show->frequency;
        $this->show_explicit = $show->explicit;
        $this->show_tags = $show->tags ? implode(', ', $show->tags) : '';
        $this->existing_show_cover = $show->cover_image;
    }

    private function loadEpisode($id)
    {
        $episode = Episode::findOrFail($id);
        $this->episode_show_id = $episode->show_id;
        $this->episode_title = $episode->title;
        $this->episode_description = $episode->description;
        $this->episode_show_notes = $episode->show_notes;
        $this->episode_duration = $episode->duration;
        $this->episode_number = $episode->episode_number;
        $this->episode_season = $episode->season_number;
        $this->episode_type = $episode->episode_type;
        $this->episode_status = $episode->status;
        $this->episode_explicit = $episode->explicit;
        $this->episode_guests = $episode->guests ? implode(', ', $episode->guests) : '';
        $this->episode_tags = $episode->tags ? implode(', ', $episode->tags) : '';
        $this->existing_episode_audio = $episode->audio_file;
        $this->existing_episode_video = $episode->video_url;
        $this->episode_video_type = $episode->video_type ?? 'youtube';
        $this->existing_episode_cover = $episode->cover_image;
        
        // Load platform links
        $this->episode_spotify_url = $episode->spotify_url ?? '';
        $this->episode_apple_url = $episode->apple_url ?? '';
        $this->episode_youtube_music_url = $episode->youtube_music_url ?? '';
        $this->episode_audiomack_url = $episode->audiomack_url ?? '';
        $this->episode_soundcloud_url = $episode->soundcloud_url ?? '';
        $this->custom_links = $episode->custom_links ?? [];
    }

    private function isDefaultPodcastCategory(?string $value): bool
    {
        if ($value === null) {
            return false;
        }

        return in_array($value, $this->podcastCategoryOptions, true);
    }

    public function updatedShowCover()
    {
        $this->resetErrorBag('show_cover');
        $this->show_cover_url = '';
        $this->validateOnly('show_cover', [
            'show_cover' => 'nullable|image|max:5120',
        ]);
    }

    public function updatedEpisodeCover()
    {
        $this->resetErrorBag('episode_cover');
        $this->episode_cover_url = '';
        $this->validateOnly('episode_cover', [
            'episode_cover' => 'nullable|image|max:5120',
        ]);
    }

    public function save()
    {
        if ($this->modalType === 'show') {
            $this->saveShow();
        } else {
            $this->saveEpisode();
        }
    }

    private function saveShow()
    {
        $this->validate([
            'show_title' => 'required|min:3|max:255',
            'show_description' => 'required|min:10',
            'show_host_name' => 'required',
            'show_category' => 'required',
            'show_cover' => $this->editMode ? 'nullable|image|max:5120' : 'nullable|image|max:5120',
            'show_cover_url' => 'nullable|url',
        ]);

        // Handle cover image
        $coverPath = $this->existing_show_cover;
        if ($this->show_cover) {
            $coverPath = CloudinaryUploader::uploadImage($this->show_cover, 'podcasts/covers');
        } elseif (!empty($this->show_cover_url)) {
            $coverPath = $this->show_cover_url;
        }

        $data = [
            'title' => $this->show_title,
            'slug' => Str::slug($this->show_title),
            'description' => $this->show_description,
            'cover_image' => $coverPath,
            'host_name' => $this->show_host_name,
            'host_id' => auth()->id(),
            'category' => $this->show_category,
            'frequency' => $this->show_frequency,
            'explicit' => $this->show_explicit,
            'is_active' => true,
            'tags' => !empty($this->show_tags) ? array_map('trim', explode(',', $this->show_tags)) : null,
        ];

        if ($this->editMode) {
            Show::find($this->itemId)->update($data);
            session()->flash('success', 'Show updated successfully!');
        } else {
            Show::create($data);
            session()->flash('success', 'Show created successfully!');
        }

        $this->closeModal();
    }

    private function saveEpisode()
    {
        $rules = [
            'episode_show_id' => 'required|exists:podcast_shows,id',
            'episode_title' => 'required|min:3|max:255',
            'episode_description' => 'required|min:10',
            'episode_duration' => 'required|integer|min:1',
            'episode_spotify_url' => 'nullable|url',
            'episode_apple_url' => 'nullable|url',
            'episode_youtube_music_url' => 'nullable|url',
            'episode_audiomack_url' => 'nullable|url',
            'episode_soundcloud_url' => 'nullable|url',
        ];

        // Audio validation
        if (!$this->editMode) {
            $rules['episode_audio'] = 'required_without:episode_audio_url|nullable|file|mimes:mp3,m4a,wav,aac,ogg|max:512000';
            $rules['episode_audio_url'] = 'required_without:episode_audio|nullable|url';
        } else {
            $rules['episode_audio'] = 'nullable|file|mimes:mp3,m4a,wav,aac,ogg|max:512000';
            $rules['episode_audio_url'] = 'nullable|url';
        }

        // Video validation (optional)
        $rules['episode_video'] = 'nullable|file|mimes:mp4,mov,avi,wmv|max:1024000';
        $rules['episode_video_url'] = 'nullable|url';

        $this->validate($rules);

        // Handle audio file
        $audioPath = $this->existing_episode_audio;
        $audioFormat = 'mp3';
        $fileSize = 0;

        if ($this->episode_audio) {
            $path = $this->episode_audio->store('podcasts/episodes', 'public');
            $audioPath = Storage::url($path);
            $audioFormat = $this->episode_audio->getClientOriginalExtension();
            $fileSize = $this->episode_audio->getSize();
        } elseif (!empty($this->episode_audio_url)) {
            $audioPath = $this->episode_audio_url;
            $ext = pathinfo(parse_url($this->episode_audio_url, PHP_URL_PATH), PATHINFO_EXTENSION);
            $audioFormat = $ext ?: 'mp3';
        }

        // Handle video file/URL
        $videoPath = $this->existing_episode_video;
        $videoType = $this->episode_video_type;
        
        if ($this->episode_video) {
            $path = $this->episode_video->store('podcasts/videos', 'public');
            $videoPath = Storage::url($path);
            $videoType = 'upload';
        } elseif (!empty($this->episode_video_url)) {
            $videoPath = $this->episode_video_url;
            // Auto-detect video type from URL
            if (str_contains($videoPath, 'youtube.com') || str_contains($videoPath, 'youtu.be')) {
                $videoType = 'youtube';
            } elseif (str_contains($videoPath, 'vimeo.com')) {
                $videoType = 'vimeo';
            } else {
                $videoType = 'other';
            }
        }

        // Handle cover image
        $coverPath = $this->existing_episode_cover;
        if ($this->episode_cover) {
            $coverPath = CloudinaryUploader::uploadImage($this->episode_cover, 'podcasts/episode-covers');
        } elseif (!empty($this->episode_cover_url)) {
            $coverPath = $this->episode_cover_url;
        }

        $data = [
            'show_id' => $this->episode_show_id,
            'title' => $this->episode_title,
            'slug' => Str::slug($this->episode_title),
            'description' => $this->episode_description,
            'show_notes' => $this->episode_show_notes,
            'audio_file' => $audioPath,
            'audio_format' => $audioFormat,
            'video_url' => $videoPath,
            'video_type' => $videoType,
            'cover_image' => $coverPath,
            'duration' => $this->episode_duration,
            'file_size' => $fileSize,
            'episode_number' => $this->episode_number,
            'season_number' => $this->episode_season,
            'episode_type' => $this->episode_type,
            'status' => $this->episode_status,
            'explicit' => $this->episode_explicit,
            'guests' => !empty($this->episode_guests) ? array_map('trim', explode(',', $this->episode_guests)) : null,
            'tags' => !empty($this->episode_tags) ? array_map('trim', explode(',', $this->episode_tags)) : null,
            'published_at' => $this->episode_status === 'published' ? now() : null,
            'spotify_url' => $this->episode_spotify_url ?: null,
            'apple_url' => $this->episode_apple_url ?: null,
            'youtube_music_url' => $this->episode_youtube_music_url ?: null,
            'audiomack_url' => $this->episode_audiomack_url ?: null,
            'soundcloud_url' => $this->episode_soundcloud_url ?: null,
            'custom_links' => !empty($this->custom_links) ? $this->custom_links : null,
        ];

        if ($this->editMode) {
            Episode::find($this->itemId)->update($data);
            session()->flash('success', 'Episode updated successfully!');
        } else {
            Episode::create($data);
            Show::find($this->episode_show_id)->increment('total_episodes');
            session()->flash('success', 'Episode created successfully!');
        }

        $this->closeModal();
    }

    public function deleteShow($id)
    {
        $show = Show::find($id);
        if ($show) {
            foreach ($show->episodes as $episode) {
                $episode->delete();
            }
            $show->delete();
            session()->flash('success', 'Show and all episodes deleted successfully!');
        }
    }

    public function deleteEpisode($id)
    {
        $episode = Episode::find($id);
        if ($episode) {
            $episode->show->decrement('total_episodes');
            $episode->delete();
            session()->flash('success', 'Episode deleted successfully!');
        }
    }

    public function togglePublish($id)
    {
        $episode = Episode::find($id);
        if ($episode) {
            if ($episode->status !== 'published' && $episode->approval_status !== 'approved') {
                session()->flash('error', 'This episode must be approved before publishing.');
                return;
            }

            $episode->status = $episode->status === 'published' ? 'draft' : 'published';
            $episode->published_at = $episode->status === 'published' ? now() : null;
            $episode->save();
            session()->flash('success', 'Status updated!');
        }
    }

    public function startApproval($episodeId, $action)
    {
        if (!$this->canReview()) {
            session()->flash('error', 'You do not have permission to review content.');
            return;
        }

        if ($action === 'approved') {
            $this->applyApproval($episodeId, $action);
            return;
        }

        $this->approvalFormId = $episodeId;
        $this->approvalAction = $action;
        $this->approvalReason = '';
    }

    public function submitApprovalForm()
    {
        if (!$this->canReview() || !$this->approvalFormId) {
            session()->flash('error', 'Unable to update approval status.');
            return;
        }

        $this->validate([
            'approvalReason' => 'required|min:5|max:1000',
        ]);

        $this->applyApproval($this->approvalFormId, $this->approvalAction, $this->approvalReason);
    }

    public function cancelApprovalForm()
    {
        $this->approvalFormId = null;
        $this->approvalAction = '';
        $this->approvalReason = '';
    }

    private function applyApproval(int $episodeId, string $action, ?string $reason = null): void
    {
        $episode = Episode::with('show.host')->find($episodeId);
        if (!$episode) {
            session()->flash('error', 'Episode not found.');
            return;
        }

        $episode->approval_status = $action;
        $episode->approval_reason = $reason ?: null;
        $episode->reviewed_by = auth()->id();
        $episode->reviewed_at = now();

        if (in_array($action, ['flagged', 'rejected'], true)) {
            $episode->status = 'draft';
            $episode->published_at = null;
        } elseif ($episode->status === 'published' && !$episode->published_at) {
            $episode->published_at = now();
        }

        $episode->save();

        $host = $episode->show?->host;
        if ($host) {
            $host->notify(new ContentApprovalUpdated(
                'podcast episode',
                $episode->title,
                $action,
                $reason ?: null
            ));
        }

        $this->approvalFormId = null;
        $this->approvalReason = '';
        $this->approvalAction = '';

        session()->flash('success', 'Approval status updated successfully.');
    }

    private function canReview(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        $approverId = Setting::get('system.content_approver_id');
        return $approverId && $user->staffMember && $user->staffMember->id === (int) $approverId;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'itemId', 'show_title', 'show_description', 'show_cover', 'show_cover_url',
            'existing_show_cover', 'show_host_name', 'show_category', 'show_category_choice',
            'show_category_custom', 'show_frequency',
            'show_explicit', 'show_tags', 'episode_show_id', 'episode_title', 
            'episode_description', 'episode_show_notes', 'episode_audio', 'episode_audio_url',
            'existing_episode_audio', 'episode_video', 'episode_video_url', 
            'existing_episode_video', 'episode_video_type', 'episode_cover', 'episode_cover_url',
            'existing_episode_cover', 'episode_duration', 'episode_number', 'episode_season',
            'episode_type', 'episode_status', 'episode_explicit', 'episode_guests', 'episode_tags',
            'episode_spotify_url', 'episode_apple_url', 'episode_youtube_music_url',
            'episode_audiomack_url', 'episode_soundcloud_url', 'custom_links',
            'custom_link_name', 'custom_link_url'
        ]);
    }

    public function getShowsProperty()
    {
        return Show::withCount('publishedEpisodes')
            ->when($this->search, function($q) {
                $q->where('title', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(12);
    }

    public function getEpisodesProperty()
    {
        return Episode::with('show')
            ->when($this->selectedShow, function($q) {
                $q->where('show_id', $this->selectedShow);
            })
            ->when($this->search, function($q) {
                $q->where('title', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(15);
    }

    public function getAllShowsProperty()
    {
        return Show::where('is_active', true)->get();
    }

    public function getStatsProperty()
    {
        return [
            'total_shows' => Show::count(),
            'total_episodes' => Episode::count(),
            'total_plays' => Episode::sum('plays'),
            'total_subscribers' => Show::sum('subscribers'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.podcast.manage', [
            'shows' => $this->shows,
            'episodes' => $this->episodes,
            'allShows' => $this->allShows,
            'stats' => $this->stats,
        ])->layout('layouts.admin', ['header' => 'Podcast Management']);
    }
}
