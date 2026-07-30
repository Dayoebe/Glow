<?php

namespace App\Providers;

use App\Models\News\News;
use App\Models\Show\OAP;
use App\Models\Staff\StaffMember;
use App\Models\User;
use App\Observers\NewsObserver;
use App\Observers\OapObserver;
use App\Observers\StaffMemberObserver;
use App\Observers\UserObserver;
use App\Support\RichTextSanitizer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RichTextSanitizer::class);
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

        Vite::usePreloadTagAttributes(function (
            string $src,
            string $url,
            ?array $chunk,
            ?array $manifest
        ): array|false {
            $path = parse_url($url, PHP_URL_PATH);

            if (
                request()->hasHeader('X-Livewire-Navigate')
                && is_string($path)
                && str_ends_with($path, '.css')
            ) {
                return false;
            }

            return [];
        });
    }
}
