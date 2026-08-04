<?php

namespace App\Livewire\Admin;

use App\Models\Career\CareerApplication;
use App\Models\ContactMessage;
use App\Models\News\News;
use App\Models\Show\Review;
use App\Models\Vettas\VettasReservation;
use Livewire\Component;

class NotificationDropdown extends Component
{
    public int $unreadCount = 0;

    public array $notifications = [];

    public bool $canAccessAdminQueues = false;

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $user = auth()->user();
        $isAdmin = $user?->isAdmin() ?? false;
        $this->canAccessAdminQueues = $isAdmin;

        $notifications = ContactMessage::query()
            ->where('is_read', false)
            ->latest()
            ->take(4)
            ->get()
            ->map(fn (ContactMessage $message) => [
                'title' => $message->subject,
                'detail' => 'Contact message from ' . $message->name,
                'received' => $message->created_at?->diffForHumans() ?? '',
                'time_raw' => $message->created_at?->timestamp ?? 0,
                'icon' => 'fas fa-envelope',
                'color' => 'emerald',
                'url' => route('admin.messages.inbox', ['message' => $message->id]),
            ])
            ->collect();

        $actionableCount = ContactMessage::where('is_read', false)->count();

        if ($isAdmin) {
            $newApplications = CareerApplication::query()
                ->with('position')
                ->where('status', 'new')
                ->latest()
                ->take(4)
                ->get();

            $actionableCount += CareerApplication::where('status', 'new')->count();
            $notifications = $notifications->merge($newApplications->map(
                fn (CareerApplication $application) => [
                    'title' => 'New ' . ($application->application_type === 'job' ? 'job' : $application->application_type) . ' application',
                    'detail' => $application->full_name . ' — ' . ($application->position?->title ?? $application->department ?? 'Career application'),
                    'received' => $application->created_at?->diffForHumans() ?? '',
                    'time_raw' => $application->created_at?->timestamp ?? 0,
                    'icon' => 'fas fa-briefcase',
                    'color' => 'blue',
                    'url' => route('admin.careers.applications.type', [
                        'type' => $application->application_type,
                        'search' => $application->application_code,
                    ]),
                ]
            ));

            $newReservations = VettasReservation::query()
                ->where('status', 'new')
                ->latest()
                ->take(4)
                ->get();

            $actionableCount += VettasReservation::where('status', 'new')->count();
            $notifications = $notifications->merge($newReservations->map(
                fn (VettasReservation $reservation) => [
                    'title' => 'New Vettas reservation',
                    'detail' => $reservation->full_name . ' — ' . $reservation->reservation_code,
                    'received' => $reservation->created_at?->diffForHumans() ?? '',
                    'time_raw' => $reservation->created_at?->timestamp ?? 0,
                    'icon' => 'fas fa-calendar-check',
                    'color' => 'violet',
                    'url' => route('admin.vettas.reservations', [
                        'search' => $reservation->reservation_code,
                        'filterTimeline' => '',
                    ]),
                ]
            ));

            $pendingReviews = Review::query()
                ->with(['show', 'user'])
                ->where('is_approved', false)
                ->latest()
                ->take(3)
                ->get();

            $actionableCount += Review::where('is_approved', false)->count();
            $notifications = $notifications->merge($pendingReviews->map(
                fn (Review $review) => [
                    'title' => 'Show review awaiting approval',
                    'detail' => ($review->show?->title ?? 'Show') . ' — ' . $review->rating . '/5 rating',
                    'received' => $review->created_at?->diffForHumans() ?? '',
                    'time_raw' => $review->created_at?->timestamp ?? 0,
                    'icon' => 'fas fa-star',
                    'color' => 'amber',
                    'url' => route('admin.shows.reviews'),
                ]
            ));
        }

        if ($user?->canApproveNews()) {
            $pendingNews = News::query()
                ->where('approval_status', 'pending')
                ->latest()
                ->take(3)
                ->get();

            $actionableCount += News::where('approval_status', 'pending')->count();
            $notifications = $notifications->merge($pendingNews->map(
                fn (News $news) => [
                    'title' => 'News awaiting approval',
                    'detail' => $news->title,
                    'received' => $news->created_at?->diffForHumans() ?? '',
                    'time_raw' => $news->created_at?->timestamp ?? 0,
                    'icon' => 'fas fa-newspaper',
                    'color' => 'rose',
                    'url' => route('admin.news.show', $news->id),
                ]
            ));
        }

        $this->unreadCount = $actionableCount;
        $this->notifications = $notifications
            ->sortByDesc('time_raw')
            ->take(10)
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.admin.notification-dropdown');
    }
}
