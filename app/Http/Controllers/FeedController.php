<?php

namespace App\Http\Controllers;

use App\Models\News\News;
use App\Models\Podcast\Episode as PodcastEpisode;
use App\Models\Show\Show as RadioShow;
use App\Support\PublicImage;
use App\Support\Seo;
use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class FeedController extends Controller
{
    private const CACHE_SECONDS = 300;

    private const AUDIO_MIME_TYPES = [
        'aac' => 'audio/aac',
        'flac' => 'audio/flac',
        'm4a' => 'audio/mp4',
        'mp3' => 'audio/mpeg',
        'mp4' => 'audio/mp4',
        'mpeg' => 'audio/mpeg',
        'oga' => 'audio/ogg',
        'ogg' => 'audio/ogg',
        'opus' => 'audio/ogg',
        'wav' => 'audio/wav',
        'wave' => 'audio/wav',
        'webm' => 'audio/webm',
    ];

    public function news(): Response
    {
        return $this->rss('news.v2', 'feeds.news', function (): EloquentCollection {
            $items = News::published()
                ->with(['category', 'author'])
                ->latest('published_at')
                ->take(50)
                ->get();

            $items->each(function (News $item): void {
                $item->setAttribute(
                    'feed_image_url',
                    $this->feedImage($item->featured_image)
                );
            });

            return $items;
        });
    }

    public function podcasts(): Response
    {
        return $this->rss('podcasts.v2', 'feeds.podcasts', function (): EloquentCollection {
            $items = PodcastEpisode::published()
                ->with(['show'])
                ->whereHas('show', fn ($query) => $query->active())
                ->latest('published_at')
                ->take(50)
                ->get();

            $items->each(function (PodcastEpisode $item): void {
                $item->setAttribute(
                    'feed_image_url',
                    $this->feedImage($item->cover_image ?: $item->show?->cover_image)
                );
                $item->setAttribute(
                    'feed_audio_enclosure',
                    $this->audioEnclosure($item)
                );
            });

            return $items;
        });
    }

    public function shows(): Response
    {
        return $this->rss('shows.v2', 'feeds.shows', function (): EloquentCollection {
            $items = RadioShow::active()
                ->with(['category', 'primaryHost'])
                ->orderBy('title')
                ->take(100)
                ->get();

            $items->each(function (RadioShow $item): void {
                $item->setAttribute(
                    'feed_image_url',
                    $this->feedImage($item->cover_image)
                );
            });

            return $items;
        });
    }

    private function rss(string $cacheKey, string $view, Closure $itemsResolver): Response
    {
        $payload = Cache::remember(
            'public-feed.' . $cacheKey,
            self::CACHE_SECONDS,
            function () use ($itemsResolver, $view): array {
                $items = $itemsResolver();
                $lastBuildDate = $this->latestContentDate($items);

                return [
                    'content' => view($view, [
                        'items' => $items,
                        'lastBuildDate' => $lastBuildDate,
                        'station' => Seo::station(),
                    ])->render(),
                    'last_modified' => $lastBuildDate?->toAtomString(),
                ];
            }
        );

        $response = response($payload['content'], 200)
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8')
            ->header(
                'Cache-Control',
                'public, max-age=' . self::CACHE_SECONDS
                    . ', s-maxage=' . self::CACHE_SECONDS
                    . ', stale-while-revalidate=3600'
            )
            ->header('ETag', '"' . hash('sha256', $payload['content']) . '"');

        if (! empty($payload['last_modified'])) {
            $response->setLastModified(CarbonImmutable::parse($payload['last_modified']));
        }

        $response->isNotModified(request());

        return $response;
    }

    private function feedImage(mixed $value): ?string
    {
        return Seo::absoluteUrl(PublicImage::url($value));
    }

    private function audioEnclosure(PodcastEpisode $episode): ?array
    {
        if (! $episode->has_playable_audio || (int) $episode->file_size <= 0) {
            return null;
        }

        $url = Seo::absoluteUrl($episode->public_audio_url);
        $scheme = strtolower((string) parse_url((string) $url, PHP_URL_SCHEME));
        $path = rawurldecode((string) parse_url((string) $url, PHP_URL_PATH));
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (
            ! in_array($scheme, ['http', 'https'], true)
            || ! isset(self::AUDIO_MIME_TYPES[$extension])
        ) {
            return null;
        }

        return [
            'url' => $url,
            'length' => (int) $episode->file_size,
            'type' => self::AUDIO_MIME_TYPES[$extension],
        ];
    }

    private function latestContentDate(EloquentCollection $items): ?CarbonImmutable
    {
        $timestamp = $items
            ->flatMap(fn ($item) => [$item->updated_at, $item->published_at])
            ->filter()
            ->map(fn ($date) => $date->getTimestamp())
            ->max();

        return $timestamp
            ? CarbonImmutable::createFromTimestamp($timestamp, config('app.timezone'))
            : null;
    }
}
