<?php
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


use App\Livewire\Page\HomePage;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Dashboard;
use App\Livewire\Page\AboutPage;
use App\Livewire\Page\ContactPage;
use App\Livewire\Page\PrivacyPolicy;

// Podcast Routes
use App\Livewire\Admin\Podcast\Manage as PodcastManage;
use App\Livewire\Admin\Podcast\Analytics as PodcastAnalytics;
use App\Livewire\Podcast\Index as PodcastIndex;
use App\Livewire\Podcast\ShowDetail as PodcastShowDetail;
use App\Livewire\Podcast\EpisodePlayer;

// News
use App\Livewire\Page\NewsPage;
use App\Livewire\Page\NewsDetail;
use App\Livewire\Page\ShowPage;
use App\Livewire\Page\ShowDetail;
use App\Livewire\Page\SchedulePage;
use App\Livewire\Page\OapDirectory;
use App\Livewire\Page\OapDetail;
use App\Livewire\Page\StaffDirectory;
use App\Livewire\Page\ContactSuccess;
use App\Livewire\Page\ProfilePage;
use App\Livewire\Page\UserSettingsPage;
use App\Livewire\Page\StaffDetail;
use App\Livewire\Page\StaffProfile;
use App\Livewire\Admin\News\NewsForm;
use App\Livewire\Admin\News\Categories as NewsCategories;
use App\Livewire\Admin\News\NewsIndex as AdminNewsIndex;
use App\Livewire\Admin\News\Analytics as AdminNewsAnalytics;
use App\Livewire\Page\EventPage;
use App\Livewire\Page\EventDetail;
use App\Livewire\Admin\Event\EventIndex as AdminEventIndex;
use App\Livewire\Admin\Event\EventForm as AdminEventForm;
use App\Livewire\Admin\Event\EventCategories as AdminEventCategories;
use App\Livewire\Admin\Event\EventCategoryForm as AdminEventCategoryForm;
use App\Livewire\Admin\Settings\StationSettings as AdminStationSettings;
use App\Livewire\Admin\Settings\WebsiteSettings as AdminWebsiteSettings;
use App\Livewire\Admin\Settings\SystemSettings as AdminSystemSettings;
use App\Livewire\Admin\Team\Oaps as AdminOaps;
use App\Livewire\Admin\Team\OapForm as AdminOapForm;
use App\Livewire\Admin\Team\StaffIndex as AdminStaffIndex;
use App\Livewire\Admin\Team\StaffForm as AdminStaffForm;
use App\Livewire\Admin\Team\ProfileLinks as AdminTeamProfileLinks;
use App\Livewire\Admin\Inbox\ContactInbox as AdminContactInbox;
use App\Livewire\Admin\Newsletter\Subscriptions as AdminNewsletterSubscriptions;
use App\Http\Controllers\NewsletterController;
use App\Livewire\Admin\Stream\LiveStream as AdminLiveStream;
use App\Livewire\Admin\Users\Index as AdminUsersIndex;
use App\Livewire\Admin\Users\Form as AdminUsersForm;
use App\Livewire\Admin\Ads\Index as AdminAdsIndex;
use App\Livewire\Admin\Ads\Form as AdminAdsForm;
use App\Livewire\Admin\Approvals\ApproverSettings;
use App\Livewire\Admin\Profile\ProfileForm as AdminProfileForm;
use App\Http\Controllers\SitemapController;

use App\Livewire\Admin\Show\Manage as ShowManage;
use App\Livewire\Admin\Show\ShowForm as AdminShowForm;
use App\Livewire\Admin\Show\CategoryForm as AdminShowCategoryForm;
use App\Livewire\Admin\Show\ScheduleForm as AdminShowScheduleForm;
use App\Livewire\Admin\Show\SegmentForm as AdminShowSegmentForm;
use App\Livewire\Admin\Show\OapForm as AdminShowOapForm;
use App\Livewire\Admin\Show\Reviews as AdminShowReviews;

// Public Routes
Route::get('/sitemap', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/ads.txt', function () {
    return response()->file(public_path('ads.txt'), [
        'Content-Type' => 'text/plain; charset=UTF-8',
    ]);
})->name('ads.txt');
Route::get('/', HomePage::class)->name('home');
Route::get('/about', AboutPage::class)->name('about');
Route::get('/contact', ContactPage::class)->name('contact');
Route::get('/privacy-policy', PrivacyPolicy::class)->name('privacy.policy');
Route::get('/contact/success', ContactSuccess::class)->name('contact.success');
Route::get('/shows', ShowPage::class)->name('shows.index');
Route::get('/shows/{slug}', ShowDetail::class)->name('shows.show');
Route::get('/schedule', SchedulePage::class)->name('schedule');
Route::get('/oaps', OapDirectory::class)->name('oaps.index');
Route::get('/oaps/{slug}', OapDetail::class)->name('oaps.show');
Route::get('/team', StaffDirectory::class)->name('staff.index');
Route::get('/team/profile/{type}/{identifier}', StaffProfile::class)->name('staff.profile');
Route::get('/team/{slug}', StaffDetail::class)->name('staff.show');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');
Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');


// Public Blog Routes - ADD THESE AT THE TOP of public routes section
Route::get('/blog', \App\Livewire\Page\BlogPage::class)->name('blog.index');
Route::get('/blog/{slug}', \App\Livewire\Page\BlogDetail::class)->name('blog.show');

// Public News Routes
Route::get('/news', NewsPage::class)->name('news');
Route::get('/news/{slug}', NewsDetail::class)->name('news.show');

// Public Event Routes
Route::get('/events', EventPage::class)->name('events.index');
Route::get('/events/{slug}', EventDetail::class)->name('events.show');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/download-database', function () {
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');

        $fileName = $dbName . '_' . now()->format('Y_m_d_His') . '.sql';
        $filePath = storage_path('app/' . $fileName);

        $mysqldumpPath = env('MYSQLDUMP_PATH', 'mysqldump');

        $command = [
            $mysqldumpPath,
            "-h{$dbHost}",
            "-u{$dbUser}",
            "--password={$dbPass}",
            $dbName
        ];

        $process = new Process($command);
        $process->run(function ($type, $buffer) use ($filePath) {
            file_put_contents($filePath, $buffer, FILE_APPEND);
        });

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return response()->download($filePath)->deleteFileAfterSend(true);
    });
});


// Guest Routes (only accessible when not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});


// Admin Blog Routes
Route::middleware(['auth', 'admin_or_staff'])->prefix('admin/blog')->name('admin.blog.')->group(function () {
    Route::get('/', \App\Livewire\Admin\Blog\BlogIndex::class)->name('index');
    Route::get('/analytics', \App\Livewire\Admin\Blog\Analytics::class)->name('analytics');
    Route::get('/create', \App\Livewire\Admin\Blog\BlogForm::class)->name('create');
    Route::get('/{id}/edit', \App\Livewire\Admin\Blog\BlogForm::class)->name('edit');
    Route::get('/categories', \App\Livewire\Admin\Blog\BlogCategories::class)->name('categories');
    Route::get('/{slug}/preview', \App\Livewire\Page\BlogDetail::class)->name('preview');
});

Route::middleware(['auth', 'admin_or_staff'])->group(function () {
    Route::prefix('admin/listeners')->name('admin.listeners.')->group(function () {
        Route::get('/', \App\Livewire\Admin\Community\Placeholder::class)->name('index')
            ->defaults('title', 'Listeners')
            ->defaults('subtitle', 'Manage listener profiles and engagement')
            ->defaults('icon', 'fas fa-users')
            ->defaults('accent', 'emerald')
            ->defaults('description', 'Track audience growth, contact history, and segmentation.');
        Route::get('/demographics', \App\Livewire\Admin\Community\Placeholder::class)->name('demographics')
            ->defaults('title', 'Listener Demographics')
            ->defaults('subtitle', 'Understand audience distribution and trends')
            ->defaults('icon', 'fas fa-chart-pie')
            ->defaults('accent', 'indigo')
            ->defaults('description', 'Analyze age, location, and device mix once data is connected.');
        Route::get('/feedback', \App\Livewire\Admin\Community\Placeholder::class)->name('feedback')
            ->defaults('title', 'Listener Feedback')
            ->defaults('subtitle', 'Capture suggestions and sentiment')
            ->defaults('icon', 'fas fa-comment-dots')
            ->defaults('accent', 'amber')
            ->defaults('description', 'Centralize feedback, tags, and response workflows.');
    });

    Route::prefix('admin/requests')->name('admin.requests.')->group(function () {
        Route::get('/songs', \App\Livewire\Admin\Community\Placeholder::class)->name('songs')
            ->defaults('title', 'Song Requests')
            ->defaults('subtitle', 'Manage incoming song requests')
            ->defaults('icon', 'fas fa-music')
            ->defaults('accent', 'purple')
            ->defaults('description', 'Review, approve, and queue listener song requests.');
        Route::get('/dedications', \App\Livewire\Admin\Community\Placeholder::class)->name('dedications')
            ->defaults('title', 'Dedications')
            ->defaults('subtitle', 'Track shoutouts and special messages')
            ->defaults('icon', 'fas fa-heart')
            ->defaults('accent', 'pink')
            ->defaults('description', 'Organize dedications by show, time, and host.');
        Route::get('/settings', \App\Livewire\Admin\Community\Placeholder::class)->name('settings')
            ->defaults('title', 'Request Settings')
            ->defaults('subtitle', 'Configure request workflows')
            ->defaults('icon', 'fas fa-sliders')
            ->defaults('accent', 'blue')
            ->defaults('description', 'Set operating hours, filters, and notifications.');
    });

    Route::prefix('admin/contests')->name('admin.contests.')->group(function () {
        Route::get('/active', \App\Livewire\Admin\Community\Placeholder::class)->name('active')
            ->defaults('title', 'Active Contests')
            ->defaults('subtitle', 'Monitor running contests and entries')
            ->defaults('icon', 'fas fa-gift')
            ->defaults('accent', 'emerald')
            ->defaults('description', 'Track entries, eligibility, and promotional status.');
        Route::get('/past', \App\Livewire\Admin\Community\Placeholder::class)->name('past')
            ->defaults('title', 'Past Contests')
            ->defaults('subtitle', 'Review historical contest performance')
            ->defaults('icon', 'fas fa-clock')
            ->defaults('accent', 'gray')
            ->defaults('description', 'Audit results, engagement, and participation.');
        Route::get('/winners', \App\Livewire\Admin\Community\Placeholder::class)->name('winners')
            ->defaults('title', 'Contest Winners')
            ->defaults('subtitle', 'Confirm winners and fulfillment')
            ->defaults('icon', 'fas fa-trophy')
            ->defaults('accent', 'amber')
            ->defaults('description', 'Manage winner confirmations and prize delivery.');
    });

    Route::get('/admin/messages/textline', \App\Livewire\Admin\Community\Placeholder::class)->name('admin.messages.textline')
        ->defaults('title', 'Text Line')
        ->defaults('subtitle', 'Handle incoming text messages')
        ->defaults('icon', 'fas fa-sms')
        ->defaults('accent', 'emerald')
        ->defaults('description', 'Route text line messages to the right team.');
    Route::get('/admin/messages/social', \App\Livewire\Admin\Community\Placeholder::class)->name('admin.messages.social')
        ->defaults('title', 'Social Messages')
        ->defaults('subtitle', 'Track social mentions and DMs')
        ->defaults('icon', 'fas fa-hashtag')
        ->defaults('accent', 'indigo')
        ->defaults('description', 'Collect messages from social platforms in one place.');
});

// Admin Event Routes
Route::middleware(['auth', 'admin_or_staff'])->prefix('admin/events')->name('admin.events.')->group(function () {
    Route::get('/', AdminEventIndex::class)->name('index');
    Route::get('/create', AdminEventForm::class)->name('create');
    Route::get('/categories', AdminEventCategories::class)->name('categories');
    Route::get('/categories/create', AdminEventCategoryForm::class)->name('categories.create');
    Route::get('/categories/{categoryId}/edit', AdminEventCategoryForm::class)->name('categories.edit');
    Route::get('/{slug}/preview', EventDetail::class)->name('preview');
    Route::get('/{id}/edit', AdminEventForm::class)->name('edit');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', ProfilePage::class)->name('profile');
    Route::get('/settings', UserSettingsPage::class)->name('settings');

    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('home');
    })->name('logout');
});

Route::get('/podcasts', PodcastIndex::class)->name('podcasts.index');
Route::get('/podcasts/{slug}', PodcastShowDetail::class)->name('podcasts.show');
Route::get('/podcasts/{showSlug}/{episodeSlug}', EpisodePlayer::class)->name('podcasts.episode');
// Admin dashboard routes (role restricted inside)
Route::middleware(['auth', 'admin_or_staff'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/admin/profile', AdminProfileForm::class)->name('admin.profile');

    // Admin News Routes
    Route::prefix('admin/news')->name('admin.news.')->group(function () {
        Route::get('/', AdminNewsIndex::class)->name('index');
        Route::get('/create', NewsForm::class)->name('create');
        Route::get('/{id}/edit', NewsForm::class)->name('edit');
        Route::get('/categories', NewsCategories::class)->name('categories');
        Route::get('/analytics', AdminNewsAnalytics::class)->name('analytics');
    });

    Route::get('/admin/podcasts', PodcastManage::class)->name('admin.podcasts.manage');
    Route::get('/admin/podcasts/analytics', PodcastAnalytics::class)->name('admin.podcasts.analytics');

    Route::get('/admin/messages', AdminContactInbox::class)->name('admin.messages.inbox');
    Route::get('/admin/comms-analytics', \App\Livewire\Admin\Analytics\CommsAnalytics::class)->name('admin.comms.analytics');

    Route::middleware('role:admin')->group(function () {
        Route::prefix('admin/shows')->name('admin.shows.')->group(function () {
            Route::get('/', ShowManage::class)->name('index');
            Route::get('/create', AdminShowForm::class)->name('create');
            Route::get('/{showId}/edit', AdminShowForm::class)->name('edit');
            Route::get('/reviews', AdminShowReviews::class)->name('reviews');
            Route::get('/oaps', ShowManage::class)->name('oaps')->defaults('view', 'oaps');
            Route::get('/oaps/create', AdminShowOapForm::class)->name('oaps.create');
            Route::get('/oaps/{oapId}/edit', AdminShowOapForm::class)->name('oaps.edit');
            Route::get('/schedule', ShowManage::class)->name('schedule')->defaults('view', 'schedule');
            Route::get('/schedule/create', AdminShowScheduleForm::class)->name('schedule.create');
            Route::get('/schedule/{slotId}/edit', AdminShowScheduleForm::class)->name('schedule.edit');
            Route::get('/segments', ShowManage::class)->name('segments')->defaults('view', 'segments');
            Route::get('/segments/create', AdminShowSegmentForm::class)->name('segments.create');
            Route::get('/segments/{segmentId}/edit', AdminShowSegmentForm::class)->name('segments.edit');
            Route::get('/categories', ShowManage::class)->name('categories')->defaults('view', 'categories');
            Route::get('/categories/create', AdminShowCategoryForm::class)->name('categories.create');
            Route::get('/categories/{categoryId}/edit', AdminShowCategoryForm::class)->name('categories.edit');
        });

        Route::prefix('admin/settings')->name('admin.settings.')->group(function () {
            Route::get('/station', AdminStationSettings::class)->name('station');
            Route::get('/website', AdminWebsiteSettings::class)->name('website');
            Route::get('/system', AdminSystemSettings::class)->name('system');
        });

        Route::get('/admin/approvals', ApproverSettings::class)->name('admin.approvals');

        Route::prefix('admin/team')->name('admin.team.')->group(function () {
            Route::get('/oaps', AdminOaps::class)->name('oaps');
            Route::get('/oaps/create', AdminOapForm::class)->name('oaps.create');
            Route::get('/oaps/{oapId}/edit', AdminOapForm::class)->name('oaps.edit');
            Route::get('/staff', AdminStaffIndex::class)->name('staff');
            Route::get('/staff/create', AdminStaffForm::class)->name('staff.create');
            Route::get('/staff/{staffId}/edit', AdminStaffForm::class)->name('staff.edit');
            Route::get('/birthdays', \App\Livewire\Admin\Team\StaffBirthdays::class)->name('birthdays');
            Route::get('/links', AdminTeamProfileLinks::class)->name('links');
            Route::get('/departments', \App\Livewire\Admin\Team\DepartmentsIndex::class)->name('departments');
            Route::get('/departments/create', \App\Livewire\Admin\Team\DepartmentForm::class)->name('departments.create');
            Route::get('/departments/{departmentId}/edit', \App\Livewire\Admin\Team\DepartmentForm::class)->name('departments.edit');
            Route::get('/roles', \App\Livewire\Admin\Team\RolesIndex::class)->name('roles');
            Route::get('/roles/create', \App\Livewire\Admin\Team\RoleForm::class)->name('roles.create');
            Route::get('/roles/{roleId}/edit', \App\Livewire\Admin\Team\RoleForm::class)->name('roles.edit');
        });

        Route::prefix('admin/users')->name('admin.users.')->group(function () {
            Route::get('/', AdminUsersIndex::class)->name('index');
            Route::get('/create', AdminUsersForm::class)->name('create');
            Route::get('/{userId}/edit', AdminUsersForm::class)->name('edit');
        });

        Route::prefix('admin/ads')->name('admin.ads.')->group(function () {
            Route::get('/', AdminAdsIndex::class)->name('index');
            Route::get('/create', AdminAdsForm::class)->name('create');
            Route::get('/{adId}/edit', AdminAdsForm::class)->name('edit');
        });

        Route::get('/admin/newsletter/subscribers', AdminNewsletterSubscriptions::class)->name('admin.newsletter.subscribers');
        Route::get('/admin/stream', AdminLiveStream::class)->name('admin.stream');











    });
});
