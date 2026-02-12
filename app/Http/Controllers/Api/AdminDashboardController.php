<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog\Post;
use App\Models\ContactMessage;
use App\Models\Event\Event;
use App\Models\News\News;
use App\Models\Podcast\Episode as PodcastEpisode;
use App\Models\Podcast\Show as PodcastShow;
use App\Models\Show\Show;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function overview(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $this->formatUser($user),
            'modules' => $this->getModulesForRole($user?->role ?? 'user'),
            'stats' => [
                'news' => News::published()->count(),
                'blog_posts' => Post::published()->count(),
                'events' => Event::published()->count(),
                'shows' => Show::active()->count(),
                'podcast_shows' => PodcastShow::active()->count(),
                'podcast_episodes' => PodcastEpisode::published()->count(),
                'contact_unread' => ContactMessage::where(function ($query) {
                    $query->where('is_read', false)->orWhereNull('is_read');
                })->count(),
                'users' => User::where('is_active', true)->count(),
            ],
        ]);
    }

    private function getModulesForRole(string $role): array
    {
        $allModules = [
            'news' => [
                'label' => 'News',
                'subtitle' => 'Manage articles',
                'route' => 'AdminNews',
            ],
            'blog' => [
                'label' => 'Blog',
                'subtitle' => 'Manage posts',
                'route' => 'AdminBlog',
            ],
            'shows' => [
                'label' => 'Shows',
                'subtitle' => 'Manage programs',
                'route' => 'AdminShows',
            ],
            'team' => [
                'label' => 'Team',
                'subtitle' => 'OAPs and staff',
                'route' => 'AdminTeam',
            ],
        ];

        $roleMap = [
            'admin' => ['news', 'blog', 'shows', 'team'],
            'staff' => ['news', 'blog', 'shows'],
            'corp_member' => ['news', 'blog'],
            'intern' => ['news'],
        ];

        $keys = $roleMap[$role] ?? ['news'];

        return collect($keys)
            ->map(fn ($key) => $allModules[$key])
            ->filter()
            ->values()
            ->all();
    }

    private function formatUser(?User $user): ?array
    {
        if (!$user) {
            return null;
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'role_label' => $user->role_label ?? null,
        ];
    }
}
