<?php

namespace App\Support;

use App\Models\Setting;
use Carbon\CarbonInterface;
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
            'url' => rtrim(config('app.url', 'https://glowfmradio.com'), '/'),
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
            $siteHost = parse_url(config('app.url', 'https://glowfmradio.com'), PHP_URL_HOST);
            $siteHosts = array_filter([
                $requestHost,
                $siteHost,
                $siteHost && !Str::startsWith($siteHost, 'www.') ? 'www.' . $siteHost : null,
                $siteHost ? preg_replace('/^www\./', '', $siteHost) : null,
                'localhost',
                '127.0.0.1',
            ]);

            if ($host && in_array($host, $siteHosts, true)) {
                $path = parse_url($value, PHP_URL_PATH) ?: '/';
                $query = parse_url($value, PHP_URL_QUERY);
                $fragment = parse_url($value, PHP_URL_FRAGMENT);

                return rtrim(config('app.url', 'https://glowfmradio.com'), '/')
                    . $path
                    . ($query ? '?' . $query : '')
                    . ($fragment ? '#' . $fragment : '');
            }

            return $value;
        }

        if (Str::startsWith($value, '//')) {
            return 'https:' . $value;
        }

        return rtrim(config('app.url', 'https://glowfmradio.com'), '/') . '/' . ltrim($value, '/');
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

        $organization = [
            '@type' => 'Organization',
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
            'author' => [
                '@type' => $news->author ? 'Person' : 'Organization',
                'name' => $news->author?->name ?: $station['name'],
            ],
            'publisher' => ['@id' => $station['url'] . '/#organization'],
            'articleSection' => $news->category?->name ?? 'News',
            'inLanguage' => 'en-NG',
        ];

        if ($image) {
            $article['image'] = [$image];
        }

        if ($keywords !== []) {
            $article['keywords'] = $keywords;
        }

        return $article;
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
        $video = [
            '@type' => 'VideoObject',
            '@id' => $canonical . '#video',
            'name' => $name,
            'description' => $description,
            'embedUrl' => $videoUrl,
            'contentUrl' => $videoUrl,
        ];

        if ($date) {
            $video['uploadDate'] = $date;
        }

        $thumbnail = self::absoluteUrl($thumbnail);
        if ($thumbnail) {
            $video['thumbnailUrl'] = [$thumbnail];
        }

        return $video;
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
