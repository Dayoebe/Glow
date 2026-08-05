<?php

namespace App\Support;

use App\Models\Setting;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Seo
{
    public const BRAND_NAME = 'Glow 99.1 FM';
    public const ALTERNATE_NAME = 'Glow FM Akure';
    public const TAGLINE = 'Your Station, Your Voice';
    public const FREQUENCY = '99.1 FM';
    public const LANGUAGE = ['English', 'Yoruba', 'Nigerian Pidgin'];
    public const TOPICS = [
        'Ondo State news',
        'Akure community updates',
        'Nigerian politics and governance',
        'Yoruba programs',
        'Public affairs interviews',
        'Youth empowerment',
        'Health and public safety',
        'Sports',
        'Entertainment',
        'Live radio',
        'Podcasts',
        'Glow TV',
    ];

    public static function siteUrl(): string
    {
        return rtrim(
            (string) config('seo.canonical_url', 'https://www.glowfmradio.com'),
            '/'
        );
    }

    public static function station(): array
    {
        $settings = Setting::get('station', []);
        if (!is_array($settings)) {
            $settings = [];
        }

        $logo = data_get($settings, 'logo_url') ?: '/glowfm logo.jpeg';
        $email = data_get($settings, 'email', 'glow991fm@gmail.com');
        $phone = data_get($settings, 'phone', '+234 703 022 3281');

        return [
            'name' => self::BRAND_NAME,
            'display_name' => data_get($settings, 'name', self::BRAND_NAME),
            'alternate_name' => self::ALTERNATE_NAME,
            'tagline' => data_get($settings, 'tagline', self::TAGLINE),
            'frequency' => self::FREQUENCY,
            'display_frequency' => data_get($settings, 'frequency', self::FREQUENCY),
            'url' => self::siteUrl(),
            'address' => data_get($settings, 'address', 'Ijapo Estate, Akure, Ondo State, Nigeria'),
            'phone' => $phone,
            'email' => $email,
            'stream_url' => data_get($settings, 'stream_url', 'https://stream-176.zeno.fm/mwam2yirv1pvv'),
            'logo' => self::absoluteUrl($logo),
            'socials' => self::socialUrls((array) data_get($settings, 'socials', [])),
        ];
    }

    public static function absoluteUrl(?string $value, ?string $fallback = null): ?string
    {
        $value = trim((string) ($value ?: $fallback ?: ''));
        if ($value === '' || $value === '#') {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            $host = parse_url($value, PHP_URL_HOST);
            $requestHost = request()?->getHost();
            $appHost = parse_url(config('app.url'), PHP_URL_HOST);
            $siteHost = parse_url(self::siteUrl(), PHP_URL_HOST);
            $siteHosts = array_filter([
                $requestHost,
                $appHost,
                $siteHost,
                $appHost && !Str::startsWith($appHost, 'www.') ? 'www.' . $appHost : null,
                $appHost ? preg_replace('/^www\./', '', $appHost) : null,
                $siteHost && !Str::startsWith($siteHost, 'www.') ? 'www.' . $siteHost : null,
                $siteHost ? preg_replace('/^www\./', '', $siteHost) : null,
                'localhost',
                '127.0.0.1',
            ]);

            if ($host && in_array($host, $siteHosts, true)) {
                $path = parse_url($value, PHP_URL_PATH) ?: '/';
                $query = parse_url($value, PHP_URL_QUERY);
                $fragment = parse_url($value, PHP_URL_FRAGMENT);

                return self::siteUrl()
                    . $path
                    . ($query ? '?' . $query : '')
                    . ($fragment ? '#' . $fragment : '');
            }

            return $value;
        }

        if (Str::startsWith($value, '//')) {
            return 'https:' . $value;
        }

        return self::siteUrl() . '/' . ltrim($value, '/');
    }

    public static function canonicalUrl(string $path, array $query = []): string
    {
        $url = self::absoluteUrl($path) ?: self::siteUrl() . '/';
        $query = collect($query)
            ->reject(fn ($value) => $value === null || $value === '' || $value === false)
            ->map(fn ($value) => is_bool($value) ? (int) $value : $value)
            ->all();

        return $query === [] ? $url : $url . '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
    }

    public static function robotsDirectives(?Request $request = null, ?int $status = null): string
    {
        $request ??= request();

        if (($status !== null && ($status < 200 || $status >= 300)) || self::isPrivateRequest($request)) {
            return (string) config('seo.private_robots', 'noindex, nofollow, noarchive, nosnippet, noimageindex');
        }

        if (self::isInternalSearchRequest($request)) {
            return (string) config('seo.filtered_robots', 'noindex, follow, noarchive');
        }

        return (string) config(
            'seo.public_robots',
            'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'
        );
    }

    public static function isPrivateRequest(?Request $request = null): bool
    {
        $request ??= request();
        $routeName = (string) optional($request->route())->getName();
        $path = trim($request->path(), '/');

        if (
            Str::startsWith($routeName, ['admin.', 'password.'])
            || in_array($routeName, [
                'dashboard',
                'login',
                'register',
                'profile',
                'settings',
                'contact.success',
                'newsletter.confirm',
                'newsletter.unsubscribe',
            ], true)
        ) {
            return true;
        }

        if (Str::startsWith($path, [
            'admin',
            'dashboard',
            'profile',
            'settings',
            'login',
            'register',
            'forgot-password',
            'reset-password',
            'download-database',
            'livewire',
            'newsletter/confirm',
            'newsletter/unsubscribe',
            'team/profile/user/',
        ])) {
            return true;
        }

        $route = $request->route();
        if ($route && collect($route->gatherMiddleware())->contains(
            fn ($middleware) => $middleware === 'auth' || Str::startsWith((string) $middleware, 'auth:')
        )) {
            return true;
        }

        return false;
    }

    public static function isInternalSearchRequest(?Request $request = null): bool
    {
        $request ??= request();

        foreach (['search', 'searchQuery'] as $parameter) {
            if (filled($request->query($parameter))) {
                return true;
            }
        }

        return filled($request->query('sortBy')) || filled($request->query('view'));
    }

    public static function isCrawler(?string $userAgent = null): bool
    {
        $userAgent ??= request()->userAgent();

        return $userAgent !== null && preg_match(
            '/(?:bot|crawl|spider|slurp|bingpreview|google-inspectiontool|facebookexternalhit|whatsapp|telegram|discord|linkedin|oai-search|chatgpt-user|gptbot)/i',
            $userAgent
        ) === 1;
    }

    public static function text(?string $value, int $limit = 180): string
    {
        $value = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $value))));

        return $value === '' ? '' : Str::limit($value, $limit);
    }

    public static function words(?string $value, int $words = 55): string
    {
        $value = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $value))));
        if ($value === '') {
            return '';
        }

        return Str::words($value, $words);
    }

    public static function sentences(?string $value, int $limit = 3): array
    {
        $value = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $value))));
        if ($value === '') {
            return [];
        }

        $sentences = preg_split('/(?<=[.!?])\s+/u', $value) ?: [];

        return collect($sentences)
            ->map(fn ($sentence) => trim($sentence))
            ->filter(fn ($sentence) => mb_strlen($sentence) >= 25)
            ->take($limit)
            ->values()
            ->all();
    }

    public static function siteGraph(array $page = []): array
    {
        $station = self::station();
        $canonical = self::absoluteUrl($page['url'] ?? request()->url()) ?: request()->url();
        $image = self::absoluteUrl($page['image'] ?? null, $station['logo']);
        $description = $page['description'] ?? ($station['tagline'] . ' from Akure, Ondo State, Nigeria.');
        $pageType = $page['type'] ?? 'WebPage';
        $sameAs = $station['socials'];
        $editorialStandardsUrl = self::absoluteUrl('/editorial-standards');

        $organization = [
            '@type' => ['Organization', 'NewsMediaOrganization'],
            '@id' => $station['url'] . '/#organization',
            'name' => $station['name'],
            'alternateName' => $station['alternate_name'],
            'url' => $station['url'],
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $station['logo'],
            ],
            'slogan' => $station['tagline'],
            'address' => self::postalAddress($station['address']),
            'knowsAbout' => self::TOPICS,
            'contactPoint' => ['@id' => $station['url'] . '/#contact'],
            'publishingPrinciples' => $editorialStandardsUrl,
            'ethicsPolicy' => $editorialStandardsUrl,
            'correctionsPolicy' => $editorialStandardsUrl . '#corrections',
            'masthead' => self::absoluteUrl('/team'),
            'missionCoveragePriorities' => self::absoluteUrl('/about'),
        ];

        if ($sameAs !== []) {
            $organization['sameAs'] = $sameAs;
        }

        $radioStation = [
            '@type' => ['RadioStation', 'LocalBusiness'],
            '@id' => $station['url'] . '/#radio-station',
            'name' => $station['name'],
            'alternateName' => $station['alternate_name'],
            'url' => $station['url'],
            'image' => $station['logo'],
            'logo' => $station['logo'],
            'slogan' => $station['tagline'],
            'address' => self::postalAddress($station['address']),
            'areaServed' => [
                '@type' => 'AdministrativeArea',
                'name' => 'Akure, Ondo State, Nigeria',
            ],
            'parentOrganization' => ['@id' => $station['url'] . '/#organization'],
            'knowsAbout' => self::TOPICS,
        ];

        if ($station['phone']) {
            $radioStation['telephone'] = $station['phone'];
        }
        if ($station['email']) {
            $radioStation['email'] = $station['email'];
        }
        if ($sameAs !== []) {
            $radioStation['sameAs'] = $sameAs;
        }

        $broadcastService = [
            '@type' => 'RadioBroadcastService',
            '@id' => $station['url'] . '/#broadcast-service',
            'name' => $station['name'],
            'alternateName' => $station['alternate_name'],
            'broadcastDisplayName' => $station['name'],
            'broadcastFrequency' => $station['frequency'],
            'broadcastSignalModulation' => 'FM',
            'broadcaster' => ['@id' => $station['url'] . '/#radio-station'],
            'areaServed' => [
                '@type' => 'AdministrativeArea',
                'name' => 'Akure, Ondo State, Nigeria',
            ],
            'inLanguage' => self::LANGUAGE,
        ];

        $website = [
            '@type' => 'WebSite',
            '@id' => $station['url'] . '/#website',
            'name' => $station['name'],
            'alternateName' => $station['alternate_name'],
            'url' => $station['url'],
            'publisher' => ['@id' => $station['url'] . '/#organization'],
            'inLanguage' => self::LANGUAGE,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => self::absoluteUrl('/news') . '?searchQuery={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];

        $webPage = [
            '@type' => $pageType,
            '@id' => $canonical . '#webpage',
            'url' => $canonical,
            'name' => $page['title'] ?? $station['name'],
            'description' => $description,
            'isPartOf' => ['@id' => $station['url'] . '/#website'],
            'publisher' => ['@id' => $station['url'] . '/#organization'],
            'about' => ['@id' => $station['url'] . '/#radio-station'],
            'inLanguage' => 'en-NG',
        ];

        if ($image) {
            $webPage['primaryImageOfPage'] = [
                '@type' => 'ImageObject',
                'url' => $image,
            ];
        }

        if (!empty($page['datePublished'])) {
            $webPage['datePublished'] = self::date($page['datePublished']);
        }

        if (!empty($page['dateModified'])) {
            $webPage['dateModified'] = self::date($page['dateModified']);
        }

        if (!empty($page['mainEntity'])) {
            $webPage['mainEntity'] = $page['mainEntity'];
        }

        $contactPoint = [
            '@type' => 'ContactPoint',
            '@id' => $station['url'] . '/#contact',
            'contactType' => 'listener support',
            'areaServed' => 'NG',
            'availableLanguage' => self::LANGUAGE,
        ];

        if ($station['phone']) {
            $contactPoint['telephone'] = $station['phone'];
        }
        if ($station['email']) {
            $contactPoint['email'] = $station['email'];
        }

        $graph = [$organization, $radioStation, $broadcastService, $website, $webPage, $contactPoint];

        if (!empty($page['breadcrumbs'])) {
            $graph[] = self::breadcrumbList($page['breadcrumbs']);
        }

        foreach (($page['extra'] ?? []) as $extra) {
            if (is_array($extra) && $extra !== []) {
                $graph[] = $extra;
            }
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => self::normalizeStructuredUrls(array_values($graph)),
        ];
    }

    public static function breadcrumbList(array $items): array
    {
        $canonical = self::absoluteUrl(request()->url()) ?: request()->url();

        return [
            '@type' => 'BreadcrumbList',
            '@id' => $canonical . '#breadcrumb',
            'itemListElement' => collect($items)
                ->values()
                ->map(function ($item, int $index) {
                    return [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'name' => $item['name'] ?? '',
                        'item' => self::absoluteUrl($item['url'] ?? null) ?? request()->url(),
                    ];
                })
                ->all(),
        ];
    }

    public static function newsArticle(object $news, string $canonical, string $description): array
    {
        $canonical = self::absoluteUrl($canonical) ?: request()->url();
        $station = self::station();
        $image = self::absoluteUrl($news->featured_image ?? null, $station['logo']);
        $keywords = collect((array) ($news->tags ?? []))
            ->merge(preg_split('/\s*,\s*/', (string) ($news->meta_keywords ?? ''), -1, PREG_SPLIT_NO_EMPTY) ?: [])
            ->merge(array_filter([$news->category?->name ?? null]))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $article = [
            '@type' => 'NewsArticle',
            '@id' => $canonical . '#article',
            'headline' => $news->title,
            'description' => $description,
            'url' => $canonical,
            'mainEntityOfPage' => ['@id' => $canonical . '#webpage'],
            'datePublished' => self::date($news->published_at),
            'dateModified' => self::date($news->updated_at) ?: self::date($news->published_at),
            'author' => self::author($news->author ?? null),
            'publisher' => ['@id' => $station['url'] . '/#organization'],
            'articleSection' => $news->category?->name ?? 'News',
            'inLanguage' => 'en-NG',
            'isAccessibleForFree' => true,
            'wordCount' => str_word_count(self::text($news->content ?? '', PHP_INT_MAX)),
        ];

        if ($image) {
            $article['image'] = [$image];
        }

        if ($keywords !== []) {
            $article['keywords'] = $keywords;
        }

        return $article;
    }

    public static function blogPosting(object $post, string $canonical, string $description): array
    {
        $canonical = self::absoluteUrl($canonical) ?: request()->url();
        $station = self::station();
        $image = self::absoluteUrl($post->featured_image ?? null, $station['logo']);
        $keywords = collect((array) ($post->tags ?? []))
            ->merge(preg_split('/\s*,\s*/', (string) ($post->meta_keywords ?? ''), -1, PREG_SPLIT_NO_EMPTY) ?: [])
            ->merge(array_filter([$post->category?->name ?? null]))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $posting = [
            '@type' => 'BlogPosting',
            '@id' => $canonical . '#article',
            'headline' => $post->title,
            'description' => $description,
            'url' => $canonical,
            'mainEntityOfPage' => ['@id' => $canonical . '#webpage'],
            'datePublished' => self::date($post->published_at ?: $post->created_at),
            'dateModified' => self::date($post->updated_at) ?: self::date($post->published_at),
            'author' => self::author($post->author ?? null),
            'publisher' => ['@id' => $station['url'] . '/#organization'],
            'articleSection' => $post->category?->name ?? 'Blog',
            'inLanguage' => 'en-NG',
            'isAccessibleForFree' => true,
            'wordCount' => str_word_count(self::text($post->content ?? '', PHP_INT_MAX)),
        ];

        if ($image) {
            $posting['image'] = [$image];
        }

        if ($keywords !== []) {
            $posting['keywords'] = $keywords;
        }

        return $posting;
    }

    public static function event(object $event, string $canonical, string $description): array
    {
        $canonical = self::absoluteUrl($canonical) ?: request()->url();
        $station = self::station();
        $image = self::absoluteUrl($event->featured_image ?? null);
        $address = trim(implode(', ', array_filter([
            $event->venue_address ?? null,
            $event->city ?? null,
            $event->state ?? null,
            $event->country ?? null,
        ])));

        $schema = [
            '@type' => 'Event',
            '@id' => $canonical . '#event',
            'name' => $event->title,
            'description' => $description,
            'url' => $canonical,
            'mainEntityOfPage' => ['@id' => $canonical . '#webpage'],
            'startDate' => self::date($event->start_at),
            'eventStatus' => 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'organizer' => ['@id' => $station['url'] . '/#organization'],
            'isAccessibleForFree' => empty($event->price) || (float) $event->price <= 0,
        ];

        if ($event->end_at) {
            $schema['endDate'] = self::date($event->end_at);
        }

        if ($image) {
            $schema['image'] = [$image];
        }

        if (($event->venue_name ?? null) || $address !== '') {
            $schema['location'] = [
                '@type' => 'Place',
                'name' => $event->venue_name ?: $address,
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $event->venue_address ?: $address,
                    'addressLocality' => $event->city ?: null,
                    'addressRegion' => $event->state ?: null,
                    'addressCountry' => $event->country ?: 'NG',
                ],
            ];
        }

        $ticketUrl = $event->ticket_url ?: $event->registration_url;
        if ($ticketUrl || $event->price !== null) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'url' => self::absoluteUrl($ticketUrl, $canonical),
                'price' => (float) ($event->price ?: 0),
                'priceCurrency' => 'NGN',
                'availability' => 'https://schema.org/InStock',
                'validFrom' => self::date($event->published_at ?: $event->created_at),
            ];
        }

        return self::removeEmptyValues($schema);
    }

    public static function jobPosting(object $position, string $canonical, string $description): array
    {
        $canonical = self::absoluteUrl($canonical) ?: request()->url();
        $station = self::station();
        $employmentTypes = [
            'full-time' => 'FULL_TIME',
            'part-time' => 'PART_TIME',
            'contract' => 'CONTRACTOR',
            'temporary' => 'TEMPORARY',
            'internship' => 'INTERN',
            'volunteer' => 'VOLUNTEER',
        ];
        $employmentType = Str::lower(str_replace('_', '-', (string) ($position->employment_type ?? '')));
        $country = $position->country ?: 'NG';

        $schema = [
            '@type' => 'JobPosting',
            '@id' => $canonical . '#job',
            'title' => $position->title,
            'description' => self::text(
                implode(' ', array_filter([
                    $position->description ?? null,
                    $position->responsibilities ?? null,
                    $position->requirements ?? null,
                    $position->benefits ?? null,
                ])),
                5000
            ),
            'url' => $canonical,
            'datePosted' => self::date($position->published_at ?: $position->created_at),
            'validThrough' => self::date($position->application_deadline),
            'employmentType' => $employmentTypes[$employmentType] ?? Str::upper(str_replace('-', '_', $employmentType)),
            'hiringOrganization' => [
                '@type' => 'Organization',
                '@id' => $station['url'] . '/#organization',
                'name' => $station['name'],
                'sameAs' => $station['url'],
                'logo' => $station['logo'],
            ],
            'jobLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $position->location ?: null,
                    'addressLocality' => $position->city ?: 'Akure',
                    'addressRegion' => $position->state ?: 'Ondo State',
                    'addressCountry' => $country,
                ],
            ],
            'industry' => 'Broadcasting and digital media',
            'occupationalCategory' => $position->department ?: null,
        ];

        if (Str::lower((string) $position->workplace_type) === 'remote') {
            $schema['jobLocationType'] = 'TELECOMMUTE';
            $schema['applicantLocationRequirements'] = [
                '@type' => 'Country',
                'name' => $country,
            ];
            unset($schema['jobLocation']);
        }

        if ($position->min_salary !== null || $position->max_salary !== null) {
            $min = $position->min_salary !== null ? (float) $position->min_salary : (float) $position->max_salary;
            $max = $position->max_salary !== null ? (float) $position->max_salary : (float) $position->min_salary;
            $salaryUnits = [
                'hourly' => 'HOUR',
                'daily' => 'DAY',
                'weekly' => 'WEEK',
                'monthly' => 'MONTH',
                'yearly' => 'YEAR',
                'annual' => 'YEAR',
                'annually' => 'YEAR',
            ];
            $salaryPeriod = Str::lower((string) ($position->salary_period ?: 'monthly'));
            $schema['baseSalary'] = [
                '@type' => 'MonetaryAmount',
                'currency' => strtoupper((string) ($position->salary_currency ?: 'NGN')),
                'value' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => min($min, $max),
                    'maxValue' => max($min, $max),
                    'unitText' => $salaryUnits[$salaryPeriod] ?? 'MONTH',
                ],
            ];
        }

        return self::removeEmptyValues($schema);
    }

    public static function person(array $profile, string $canonical): array
    {
        $canonical = self::absoluteUrl($canonical) ?: request()->url();
        $station = self::station();
        $socials = collect((array) ($profile['socials'] ?? $profile['social_links'] ?? []))
            ->filter(fn ($url) => is_string($url) && Str::startsWith($url, ['http://', 'https://']))
            ->values()
            ->all();

        return self::removeEmptyValues([
            '@type' => 'Person',
            '@id' => $canonical . '#person',
            'name' => $profile['name'] ?? null,
            'url' => $canonical,
            'image' => self::absoluteUrl($profile['image'] ?? $profile['photo'] ?? null),
            'description' => self::text($profile['bio'] ?? null, 500),
            'jobTitle' => $profile['role'] ?? $profile['job_title'] ?? null,
            'worksFor' => ['@id' => $station['url'] . '/#organization'],
            'sameAs' => $socials,
        ]);
    }

    public static function showBroadcastEvent(object $show, Collection $slots, string $canonical): array
    {
        $canonical = self::absoluteUrl($canonical) ?: request()->url();
        $station = self::station();
        $event = [
            '@type' => 'BroadcastEvent',
            '@id' => $canonical . '#broadcast-event',
            'name' => $show->title,
            'description' => self::text($show->description ?? $show->full_description ?? '', 260),
            'url' => $canonical,
            'isAccessibleForFree' => true,
            'publishedOn' => ['@id' => $station['url'] . '/#broadcast-service'],
            'organizer' => ['@id' => $station['url'] . '/#radio-station'],
            'inLanguage' => self::LANGUAGE,
        ];

        if ($show->primaryHost) {
            $event['performer'] = [
                '@type' => 'Person',
                'name' => $show->primaryHost->name,
                'url' => self::absoluteUrl(route('oaps.show', $show->primaryHost->slug)),
            ];
        }

        $schedules = $slots->map(function ($slot) {
            return [
                '@type' => 'Schedule',
                'byDay' => 'https://schema.org/' . ucfirst($slot->day_of_week),
                'startTime' => substr((string) $slot->start_time, 0, 5),
                'endTime' => substr((string) $slot->end_time, 0, 5),
                'repeatFrequency' => 'P1W',
                'scheduleTimezone' => 'Africa/Lagos',
            ];
        })->values()->all();

        if ($schedules !== []) {
            $event['eventSchedule'] = $schedules;
        }

        return $event;
    }

    public static function podcastEpisode(object $episode, string $canonical, string $description): array
    {
        $canonical = self::absoluteUrl($canonical) ?: request()->url();
        $station = self::station();
        $image = self::absoluteUrl($episode->cover_image ?? $episode->show?->cover_image ?? null, $station['logo']);
        $duration = self::isoDurationMinutes($episode->duration ?? null);

        $podcast = [
            '@type' => 'PodcastEpisode',
            '@id' => $canonical . '#podcast-episode',
            'name' => $episode->title,
            'description' => $description,
            'url' => $canonical,
            'datePublished' => self::date($episode->published_at),
            'dateModified' => self::date($episode->updated_at) ?: self::date($episode->published_at),
            'partOfSeries' => [
                '@type' => 'PodcastSeries',
                'name' => $episode->show?->title,
                'url' => self::absoluteUrl($episode->show ? route('podcasts.show', $episode->show->slug) : route('podcasts.index')),
            ],
            'publisher' => ['@id' => $station['url'] . '/#organization'],
            'mainEntityOfPage' => ['@id' => $canonical . '#webpage'],
        ];

        if ($image) {
            $podcast['image'] = $image;
        }
        if ($duration) {
            $podcast['duration'] = $duration;
        }
        if ($episode->audio_file) {
            $audio = [
                '@type' => 'AudioObject',
                'name' => $episode->title,
                'description' => $description,
                'contentUrl' => self::absoluteUrl($episode->audio_file),
                'encodingFormat' => $episode->audio_format ?: 'mp3',
            ];

            if ($duration) {
                $audio['duration'] = $duration;
            }

            $podcast['associatedMedia'] = $audio;
        }

        return $podcast;
    }

    public static function videoObject(string $name, string $description, ?string $videoUrl, ?string $thumbnail, $uploadDate = null): ?array
    {
        $videoUrl = self::absoluteUrl($videoUrl);
        if (!$videoUrl) {
            return null;
        }

        $date = self::date($uploadDate);
        $canonical = self::absoluteUrl(request()->url()) ?: request()->url();
        $embedUrl = self::videoEmbedUrl($videoUrl);
        $isDirectMedia = preg_match('/\.(?:mp4|m4v|webm|mov|ogv)(?:$|[?#])/i', $videoUrl) === 1;
        $video = [
            '@type' => 'VideoObject',
            '@id' => $canonical . '#video',
            'name' => $name,
            'description' => $description,
            'embedUrl' => $embedUrl,
        ];

        if ($isDirectMedia) {
            $video['contentUrl'] = $videoUrl;
        }

        if ($date) {
            $video['uploadDate'] = $date;
        }

        $thumbnail = self::absoluteUrl($thumbnail);
        if ($thumbnail) {
            $video['thumbnailUrl'] = [$thumbnail];
        }

        return self::removeEmptyValues($video);
    }

    private static function author(?object $author): array
    {
        $station = self::station();
        if (!$author) {
            return [
                '@type' => 'Organization',
                '@id' => $station['url'] . '/#organization',
                'name' => $station['name'],
                'url' => $station['url'],
            ];
        }

        $staff = null;
        if (method_exists($author, 'staffMember')) {
            $staff = $author->relationLoaded('staffMember')
                ? $author->staffMember
                : $author->staffMember()->where('is_active', true)->first();
        }

        if ($staff && $staff->is_active && $staff->slug) {
            $url = self::absoluteUrl(route('staff.show', $staff->slug));
            $sameAs = collect((array) $staff->social_links)
                ->filter(fn ($value) => is_string($value) && Str::startsWith($value, ['http://', 'https://']))
                ->values()
                ->all();

            return self::removeEmptyValues([
                '@type' => 'Person',
                '@id' => $url . '#person',
                'name' => $author->name,
                'url' => $url,
                'image' => self::absoluteUrl($staff->photo_url ?: $author->avatar),
                'jobTitle' => $staff->teamRole?->name ?? $staff->role ?? null,
                'worksFor' => ['@id' => $station['url'] . '/#organization'],
                'sameAs' => $sameAs,
            ]);
        }

        return [
            '@type' => 'Person',
            'name' => $author->name,
            'worksFor' => ['@id' => $station['url'] . '/#organization'],
        ];
    }

    public static function videoEmbedUrl(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        $host = Str::lower((string) parse_url($url, PHP_URL_HOST));
        $path = (string) parse_url($url, PHP_URL_PATH);
        $videoId = null;

        if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $videoId = trim($path, '/');
        } elseif (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
            parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
            $videoId = $query['v'] ?? null;

            if (!$videoId && preg_match('#/(?:embed|shorts)/([^/?]+)#', $path, $matches)) {
                $videoId = $matches[1];
            }
        }

        if ($videoId && preg_match('/^[A-Za-z0-9_-]{6,20}$/', $videoId)) {
            return 'https://www.youtube.com/embed/' . $videoId;
        }

        return $url;
    }

    private static function postalAddress(string $address): array
    {
        return [
            '@type' => 'PostalAddress',
            'streetAddress' => $address,
            'addressLocality' => 'Akure',
            'addressRegion' => 'Ondo State',
            'addressCountry' => 'NG',
        ];
    }

    private static function socialUrls(array $socials): array
    {
        return collect($socials)
            ->filter(fn ($url) => is_string($url) && Str::startsWith($url, ['http://', 'https://']))
            ->unique()
            ->values()
            ->all();
    }

    private static function date($value): ?string
    {
        if ($value instanceof CarbonInterface) {
            return $value->toAtomString();
        }

        return $value ? (string) $value : null;
    }

    private static function isoDurationMinutes($minutes): ?string
    {
        if (!$minutes || !is_numeric($minutes)) {
            return null;
        }

        $totalSeconds = (int) round(((float) $minutes) * 60);
        $hours = intdiv($totalSeconds, 3600);
        $minutes = intdiv($totalSeconds % 3600, 60);
        $seconds = $totalSeconds % 60;

        $duration = 'PT';
        if ($hours > 0) {
            $duration .= $hours . 'H';
        }
        if ($minutes > 0) {
            $duration .= $minutes . 'M';
        }
        if ($seconds > 0) {
            $duration .= $seconds . 'S';
        }

        return $duration === 'PT' ? null : $duration;
    }

    private static function removeEmptyValues(array $value): array
    {
        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $item = self::removeEmptyValues($item);
                if ($item === []) {
                    unset($value[$key]);

                    continue;
                }
                $value[$key] = $item;

                continue;
            }

            if ($item === null || $item === '') {
                unset($value[$key]);
            }
        }

        return $value;
    }

    private static function normalizeStructuredUrls(array $value): array
    {
        $urlKeys = [
            '@id',
            'url',
            'item',
            'contentUrl',
            'embedUrl',
            'thumbnailUrl',
            'image',
            'logo',
        ];

        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $value[$key] = self::normalizeStructuredUrls($item);

                continue;
            }

            if (!is_string($item) || !in_array((string) $key, $urlKeys, true)) {
                continue;
            }

            $value[$key] = self::absoluteUrl($item) ?: $item;
        }

        return $value;
    }
}
