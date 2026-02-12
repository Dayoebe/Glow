<?php

namespace App\Providers;

use App\Models\Show\OAP;
use App\Models\News\News;
use App\Models\Staff\StaffMember;
use App\Models\User;
use App\Observers\NewsObserver;
use App\Observers\OapObserver;
use App\Observers\StaffMemberObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        StaffMember::observe(StaffMemberObserver::class);
        OAP::observe(OapObserver::class);
        News::observe(NewsObserver::class);

        Blade::directive('continueIfNotArray', function ($expression) {
            return "<?php if (!is_array($expression) && !($expression instanceof \\ArrayAccess)) continue; ?>";
        });

        Blade::directive('normalizeArray', function ($expression) {
            return "<?php if (!is_array($expression) && !($expression instanceof \\ArrayAccess)) { $expression = []; } ?>";
        });
    }
}
