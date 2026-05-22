# Glow 99.1 FM AI Discoverability Report

Date: 2026-05-22

## Stack Detected

- Laravel 12 application.
- Livewire 3 public page components.
- MySQL-backed CMS data for news, shows, schedules, podcasts, settings, careers, events, staff, and Vettas.
- Vite/Tailwind frontend assets.

## What Changed

- Added crawler-friendly `robots.txt` rules with explicit handling for Googlebot, Bingbot, OAI-SearchBot, ChatGPT-User, and GPTBot.
- Added AI-readable root endpoints: `/llms.txt`, `/llms-full.txt`, and `/ai.txt`.
- Rebuilt sitemap output as a sitemap index with page, news, program, category, image, and video sitemap sections.
- Added RSS feeds for latest news, podcasts, and programs.
- Added feed discovery links in the public `<head>`.
- Added graph-based JSON-LD for Organization, RadioStation/LocalBusiness, RadioBroadcastService, WebSite, WebPage, ContactPoint, BreadcrumbList, NewsArticle, BroadcastEvent, PodcastEpisode, VideoObject, ItemList, and PodcastSeries where data exists.
- Added `/listen-live`, `/advertise`, and `/programs` public routes.
- Improved homepage, about, contact, news, program, schedule, podcast, and article metadata.
- Added visible AI-answer-friendly article summary, key takeaways, updated date, FAQ, and clearer program summary sections.
- Replaced placeholder fallback contact/station defaults with Glow 99.1 FM Akure facts.
- Fixed duplicate H1 behavior from the header logo by changing the logo title element from `h1` to text.

## Files Edited

- `app/Http/Controllers/Api/ContactController.php`
- `app/Http/Controllers/SitemapController.php`
- `app/Livewire/Admin/Settings/StationSettings.php`
- `app/Livewire/Page/AboutPage.php`
- `app/Livewire/Page/ContactPage.php`
- `app/Livewire/Page/HomePage.php`
- `app/Livewire/Page/NewsDetail.php`
- `app/Livewire/Page/NewsPage.php`
- `app/Livewire/Page/SchedulePage.php`
- `app/Livewire/Page/ShowDetail.php`
- `app/Livewire/Page/ShowPage.php`
- `app/Livewire/Podcast/EpisodePlayer.php`
- `app/Livewire/Podcast/Index.php`
- `app/Livewire/Podcast/ShowDetail.php`
- `public/robots.txt`
- `resources/views/layouts/app.blade.php`
- `resources/views/livewire/page/news-detail.blade.php`
- `resources/views/livewire/page/show-detail.blade.php`
- `routes/web.php`

## New Files Created

- `app/Http/Controllers/AiDiscoveryController.php`
- `app/Http/Controllers/FeedController.php`
- `app/Livewire/Page/AdvertisePage.php`
- `app/Livewire/Page/ListenLivePage.php`
- `app/Support/Seo.php`
- `docs/ai-discoverability-report.md`
- `resources/views/feeds/news.blade.php`
- `resources/views/feeds/podcasts.blade.php`
- `resources/views/feeds/shows.blade.php`
- `resources/views/livewire/page/advertise-page.blade.php`
- `resources/views/livewire/page/listen-live-page.blade.php`
- `resources/views/sitemap-index.blade.php`
- `resources/views/sitemap-urlset.blade.php`

## Crawler And Indexing Status

- `/robots.txt` returns `text/plain; charset=UTF-8`.
- Public pages, feeds, images, CSS, JavaScript, article pages, show pages, schedule pages, and podcast pages are not blocked.
- Private/admin/auth routes are blocked from generic crawling.
- `/sitemap.xml` returns an XML sitemap index.
- Sitemap sections validated as XML locally:
  - `/sitemaps/pages.xml`
  - `/sitemaps/news.xml`
  - `/sitemaps/programs.xml`
  - `/sitemaps/categories.xml`
  - `/sitemaps/images.xml`
  - `/sitemaps/videos.xml`
- RSS feeds validated as XML locally:
  - `/feed.xml`
  - `/news/feed.xml`
  - `/podcasts/feed.xml`
  - `/shows/feed.xml`

## Structured Data Implemented

- Homepage and shared pages: `Organization`, `RadioStation`, `LocalBusiness`, `RadioBroadcastService`, `WebSite`, `WebPage`, `ContactPoint`, `BreadcrumbList`.
- News detail pages: `NewsArticle`, `BreadcrumbList`, optional `VideoObject`.
- Program/show detail pages: `BroadcastEvent` with schedule data where available.
- Schedule and listing pages: `ItemList`.
- Podcast episode pages: `PodcastEpisode`, `AudioObject`, optional `VideoObject`.
- Podcast show pages: `PodcastSeries`.

## Validation Checklist

- PHP syntax check: passed for all changed PHP files.
- Route registration: passed for sitemap, llms, ai, feed, listen-live, advertise, and programs routes.
- Local endpoint checks: `/robots.txt`, `/llms.txt`, `/llms-full.txt`, `/ai.txt`, `/sitemap.xml`, `/news/feed.xml`, `/podcasts/feed.xml`, `/shows/feed.xml`, `/listen-live`, and `/advertise` returned successfully.
- JSON-LD syntax: validated on homepage, news index, news detail, shows index, show detail, schedule, listen-live, advertise, and podcast episode pages.
- Canonical tags: confirmed on tested public pages.
- No accidental `noindex`: confirmed on tested public pages.
- Build: `npm run build` passed.
- PHPUnit: blocked by missing `pdo_sqlite` in the local PHP environment; `phpunit.xml` uses SQLite in-memory for tests.

## Remaining Risks

- GPTBot is disallowed by default because it is training-related, not search-result retrieval. OAI-SearchBot and ChatGPT-User are allowed. The owner can explicitly allow GPTBot later if desired.
- Social profile URLs are empty in current station settings, so `sameAs` only includes verified URLs when configured. Add real Facebook, Instagram, X, YouTube, TikTok, and LinkedIn URLs in Station Settings when available.
- Some old CMS content contains third-party syndicated news images and excerpts. Keep attribution, licensing, and editorial verification processes current.
- Local PHPUnit cannot fully run until the PHP SQLite PDO extension is installed or tests are pointed to a usable testing database.
- The sitemap is cached for 30 minutes, so brand-new content may take up to 30 minutes to appear unless cache is cleared.

## Manual Steps For The Owner

- Submit `https://glowfmradio.com/sitemap.xml` in Google Search Console.
- Submit `https://glowfmradio.com/sitemap.xml` in Bing Webmaster Tools.
- Test `https://glowfmradio.com/robots.txt` with search engine robots tools.
- Test key article, homepage, show, podcast, and contact URLs in Google Rich Results Test and Schema.org Validator.
- Check ChatGPT, Perplexity, Gemini, Claude, and Bing/Copilot discovery manually using exact brand and article queries.
- Add real social profile URLs in the admin Station Settings for stronger entity reconciliation.
- Keep articles fresh, factual, dated, and internally linked.
- Build external authority through credible citations, backlinks, local directories, social profile consistency, press mentions, and partner references.
