<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\BlogCommentController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\AdminPushController;
use App\Http\Controllers\Api\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Api\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Api\Admin\ShowsController as AdminShowsController;
use App\Http\Controllers\Api\Admin\TeamController as AdminTeamController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\NewsEngagementController;
use App\Http\Controllers\Api\NewsCommentController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventCommentController;
use App\Http\Controllers\Api\EventAttendanceController;
use App\Http\Controllers\Api\EventEngagementController;
use App\Http\Controllers\Api\NowPlayingController;
use App\Http\Controllers\Api\PodcastController;
use App\Http\Controllers\Api\PodcastEngagementController;
use App\Http\Controllers\Api\PodcastCommentController;
use App\Http\Controllers\Api\PodcastReviewController;
use App\Http\Controllers\Api\ShowController;
use App\Http\Controllers\Api\BlogEngagementController;
use App\Http\Controllers\Api\PublicAuthController;
use App\Http\Controllers\Api\UserLibraryController;
use App\Http\Controllers\Api\SearchController;

Route::middleware(['api_optional_token'])->get('/home', [HomeController::class, 'show']);
Route::get('/now-playing', [NowPlayingController::class, 'show']);
Route::get('/search', [SearchController::class, 'index']);

Route::get('/shows', [ShowController::class, 'index']);
Route::get('/shows/{slug}', [ShowController::class, 'show']);
Route::get('/schedule', [ShowController::class, 'schedule']);

Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{slug}', [EventController::class, 'show']);
Route::middleware(['api_optional_token'])->group(function () {
    Route::get('/events/{slug}/interactions', [EventEngagementController::class, 'summary']);
    Route::post('/events/{slug}/share', [EventEngagementController::class, 'share']);
    Route::post('/events/{slug}/view', [EventEngagementController::class, 'view']);
    Route::get('/events/{slug}/comments', [EventCommentController::class, 'index']);
    Route::post('/events/{slug}/comments', [EventCommentController::class, 'store']);
    Route::post('/events/{slug}/comments/{commentId}/like', [EventCommentController::class, 'like']);
    Route::get('/events/{slug}/rsvp', [EventAttendanceController::class, 'summary']);
});
Route::middleware(['api_token'])->group(function () {
    Route::post('/events/{slug}/react', [EventEngagementController::class, 'react']);
    Route::post('/events/{slug}/bookmark', [EventEngagementController::class, 'bookmark']);
    Route::post('/events/{slug}/rsvp', [EventAttendanceController::class, 'store']);
});

Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{slug}', [NewsController::class, 'show']);
Route::middleware(['api_optional_token'])->group(function () {
    Route::get('/news/{slug}/comments', [NewsCommentController::class, 'index']);
    Route::post('/news/{slug}/comments', [NewsCommentController::class, 'store']);
    Route::post('/news/{slug}/comments/{commentId}/like', [NewsCommentController::class, 'like']);
});
Route::middleware(['api_optional_token'])->group(function () {
    Route::get('/news/{slug}/interactions', [NewsEngagementController::class, 'summary']);
    Route::post('/news/{slug}/share', [NewsEngagementController::class, 'share']);
    Route::post('/news/{slug}/view', [NewsEngagementController::class, 'view']);
});
Route::middleware(['api_token'])->group(function () {
    Route::post('/news/{slug}/react', [NewsEngagementController::class, 'react']);
    Route::post('/news/{slug}/bookmark', [NewsEngagementController::class, 'bookmark']);
});

Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{slug}', [BlogController::class, 'show']);
Route::middleware(['api_optional_token'])->group(function () {
    Route::get('/blog/{slug}/comments', [BlogCommentController::class, 'index']);
    Route::post('/blog/{slug}/comments', [BlogCommentController::class, 'store']);
    Route::post('/blog/{slug}/comments/{commentId}/like', [BlogCommentController::class, 'like']);
});
Route::middleware(['api_optional_token'])->group(function () {
    Route::get('/blog/{slug}/interactions', [BlogEngagementController::class, 'summary']);
    Route::post('/blog/{slug}/share', [BlogEngagementController::class, 'share']);
    Route::post('/blog/{slug}/view', [BlogEngagementController::class, 'view']);
});
Route::middleware(['api_token'])->group(function () {
    Route::post('/blog/{slug}/react', [BlogEngagementController::class, 'react']);
    Route::post('/blog/{slug}/bookmark', [BlogEngagementController::class, 'bookmark']);
});

Route::get('/podcasts', [PodcastController::class, 'index']);
Route::get('/podcasts/{slug}', [PodcastController::class, 'show']);
Route::get('/podcasts/{showSlug}/{episodeSlug}', [PodcastController::class, 'episode']);
Route::middleware(['api_optional_token'])->group(function () {
    Route::get('/podcasts/{showSlug}/{episodeSlug}/comments', [PodcastCommentController::class, 'index']);
    Route::post('/podcasts/{showSlug}/{episodeSlug}/comments', [PodcastCommentController::class, 'store']);
    Route::post('/podcasts/{showSlug}/{episodeSlug}/comments/{commentId}/like', [PodcastCommentController::class, 'like']);
    Route::get('/podcasts/{slug}/reviews', [PodcastReviewController::class, 'index']);
    Route::post('/podcasts/{slug}/reviews/{reviewId}/helpful', [PodcastReviewController::class, 'helpful']);
});
Route::middleware(['api_optional_token'])->group(function () {
    Route::get('/podcasts/{slug}/subscription', [PodcastEngagementController::class, 'subscriptionStatus']);
    Route::post('/podcasts/{showSlug}/{episodeSlug}/share', [PodcastEngagementController::class, 'shareEpisode']);
});
Route::middleware(['api_token'])->group(function () {
    Route::post('/podcasts/{slug}/subscribe', [PodcastEngagementController::class, 'toggleSubscription']);
    Route::post('/podcasts/{slug}/reviews', [PodcastReviewController::class, 'store']);
});

Route::get('/about', [AboutController::class, 'show']);
Route::get('/contact', [ContactController::class, 'show']);
Route::post('/contact', [ContactController::class, 'store']);

Route::prefix('/public/auth')->group(function () {
    Route::post('/register', [PublicAuthController::class, 'register']);
    Route::post('/login', [PublicAuthController::class, 'login']);
    Route::middleware(['api_token'])->group(function () {
        Route::get('/me', [PublicAuthController::class, 'me']);
        Route::post('/logout', [PublicAuthController::class, 'logout']);
    });
});

Route::middleware(['api_token'])->prefix('/public/library')->group(function () {
    Route::get('/bookmarks', [UserLibraryController::class, 'bookmarks']);
    Route::get('/subscriptions', [UserLibraryController::class, 'subscriptions']);
});

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware(['api_token'])->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware(['api_token', 'api_admin_or_staff'])->prefix('/admin')->group(function () {
    Route::get('/overview', [AdminDashboardController::class, 'overview']);
    Route::post('/push/send', [AdminPushController::class, 'send']);
    Route::get('/news', [AdminNewsController::class, 'index']);
    Route::get('/blog', [AdminBlogController::class, 'index']);
    Route::get('/shows', [AdminShowsController::class, 'index']);
    Route::get('/team/oaps', [AdminTeamController::class, 'oaps']);
    Route::get('/team/staff', [AdminTeamController::class, 'staff']);
});
