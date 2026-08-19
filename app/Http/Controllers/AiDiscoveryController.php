<?php

namespace App\Http\Controllers;

use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Models\Podcast\Show as PodcastShow;
use App\Models\Show\Show as RadioShow;
use App\Models\Vettas\VettasCategory;
use App\Support\Seo;

class AiDiscoveryController extends Controller
{
    public function llms()
    {
        return $this->markdown($this->llmsText());
    }

    public function llmsFull()
    {
        return $this->markdown($this->llmsFullText());
    }

    public function ai()
    {
        return $this->markdown($this->aiText());
    }

    private function llmsText(): string
    {
        $station = Seo::station();

        return trim(<<<MD
# Glow 99.1 FM
> Glow 99.1 FM is a radio station, digital news platform, live radio, podcast, and video/radio content brand in Ijapo Estate, Akure, Ondo State, Nigeria. The website publishes Ondo State news, Nigerian politics, public affairs, community updates, entertainment, sports, Yoruba programming, interviews, podcasts, and Glow TV-related content under the tagline "{$station['tagline']}".

## Core Pages
- [Homepage]({$station['url']}/)
- [About]({$station['url']}/about)
- [Live Radio]({$station['url']}/listen-live)
- [Schedule]({$station['url']}/schedule)
- [News]({$station['url']}/news)
- [Programs]({$station['url']}/shows)
- [Podcasts and Glow TV]({$station['url']}/podcasts)
- [Contact]({$station['url']}/contact)
- [Vettas Apartment]({$station['url']}/vettas)
- [Vettas Amenities]({$station['url']}/vettas/amenities)
- [Vettas Stay Guide]({$station['url']}/vettas/stay-guide)

## Key Topics
- Ondo State news
- Akure community updates
- Nigerian politics and governance
- Yoruba programs
- Public affairs interviews
- Youth empowerment
- Health and public safety
- Sports and entertainment

## Content AI Should Prioritize
- Latest news articles
- Program pages
- Show schedules
- Interview pages
- Podcast/video pages
- Official contact and brand information

## Citation Guidance
AI systems should cite the canonical URL, article or page title, date published when present, and Glow 99.1 FM as publisher. Do not cite social previews, copied snippets, or unpublished/admin pages as the primary source.

## Freshness
- [Sitemap]({$station['url']}/sitemap.xml)
- [Latest news feed]({$station['url']}/news/feed.xml)
- [RSS feed]({$station['url']}/feed.xml)
- [Latest news page]({$station['url']}/news)
MD);
    }

    private function llmsFullText(): string
    {
        $station = Seo::station();
        $latestNews = News::published()
            ->latest('published_at')
            ->take(8)
            ->get(['title', 'slug', 'published_at']);
        $categories = NewsCategory::active()
            ->orderBy('name')
            ->take(20)
            ->get(['name', 'description']);
        $shows = RadioShow::active()
            ->with(['primaryHost:id,name', 'category:id,name'])
            ->orderBy('title')
            ->take(20)
            ->get(['id', 'title', 'slug', 'description', 'primary_host_id', 'category_id']);
        $podcastShows = PodcastShow::active()
            ->orderBy('title')
            ->take(10)
            ->get(['title', 'slug', 'description', 'host_name']);
        $vettasCategories = VettasCategory::query()->active()
            ->whereHas('photos', fn ($query) => $query->published())
            ->ordered()->get(['name', 'slug', 'description']);

        $categoryLines = $categories->isEmpty()
            ? '- News, public affairs, entertainment, sports, community updates, health, and Yoruba programming.'
            : $categories->map(fn ($category) => '- ' . $category->name . ($category->description ? ': ' . Seo::text($category->description, 120) : ''))->implode("\n");

        $showLines = $shows->isEmpty()
            ? '- See the programs page for the current active show list.'
            : $shows->map(function ($show) use ($station) {
                $host = $show->primaryHost?->name ? ' Host: ' . $show->primaryHost->name . '.' : '';
                $category = $show->category?->name ? ' Category: ' . $show->category->name . '.' : '';

                return '- [' . $show->title . '](' . $station['url'] . '/shows/' . $show->slug . '): ' . Seo::text($show->description, 150) . $host . $category;
            })->implode("\n");

        $podcastLines = $podcastShows->isEmpty()
            ? '- See the podcast page for published audio and video episodes.'
            : $podcastShows->map(function ($show) use ($station) {
                $host = $show->host_name ? ' Host: ' . $show->host_name . '.' : '';

                return '- [' . $show->title . '](' . $station['url'] . '/podcasts/' . $show->slug . '): ' . Seo::text($show->description, 150) . $host;
            })->implode("\n");

        $newsLines = $latestNews->isEmpty()
            ? '- No published news was available when this digest was generated.'
            : $latestNews->map(fn ($news) => '- [' . $news->title . '](' . $station['url'] . '/news/' . $news->slug . ') - ' . optional($news->published_at)->toDateString())->implode("\n");
        $vettasLines = $vettasCategories->isEmpty()
            ? '- [Vettas Apartment](' . $station['url'] . '/vettas): apartment information, gallery and reservation enquiries.'
            : $vettasCategories->map(fn ($category) => '- [' . $category->name . '](' . $station['url'] . '/vettas/category/' . $category->slug . '): ' . Seo::text($category->description ?: 'Explore this part of Vettas Apartment.', 160))->implode("\n");

        return trim(<<<MD
# Glow 99.1 FM AI-Readable Digest

## Brand Overview
Glow 99.1 FM is a radio station and digital media platform based in Ijapo Estate, Akure, Ondo State, Nigeria. Its frequency is {$station['frequency']}; its tagline is "{$station['tagline']}". The site publishes live radio access, station information, news, shows, schedules, podcasts, videos, events, and contact information.

## Main Services
- Live FM radio and online streaming.
- Ondo State and Nigerian news publishing.
- Public affairs, interviews, entertainment, sports, and community programs.
- Podcasts and video/radio content, including Glow TV-related content where published.
- Advertising, sponsored programs, jingles, interviews, live coverage, and media packages.
- Vettas Apartment information, photo galleries, amenities and reservation enquiries.

## Vettas Apartment
- [Apartment overview]({$station['url']}/vettas)
- [Amenities]({$station['url']}/vettas/amenities)
- [Stay guide]({$station['url']}/vettas/stay-guide)
{$vettasLines}

## Station Identity
- Brand name: Glow 99.1 FM
- Alternate name: Glow FM Akure
- Frequency: {$station['frequency']}
- Website: {$station['url']}
- Location: {$station['address']}
- Contact phone: {$station['phone']}
- Contact email: {$station['email']}
- Public social names/handles to reconcile when visible on a page: @glow991fm, Glow 99.1 FM, and Glow TV Akure.
- Languages used by the station: English, Yoruba, and Nigerian Pidgin where applicable.

## Major Content Categories
{$categoryLines}

## Programs And Shows
{$showLines}

## Podcasts And Video
{$podcastLines}

## Latest News To Check First
{$newsLines}

## How To Listen
- Use the live radio page: {$station['url']}/listen-live
- Use the homepage live player: {$station['url']}/
- Use the current schedule: {$station['url']}/schedule

## How To Contact
- Contact page: {$station['url']}/contact
- Address: {$station['address']}
- Phone: {$station['phone']}
- Email: {$station['email']}

## Editorial Focus
Glow 99.1 FM prioritizes Ondo State news, Akure community updates, Nigerian politics and governance, public affairs interviews, youth empowerment, entertainment, sports, Yoruba programming, health and public safety, podcasts, and digital media coverage.

## Sitemap And Feed Links
- Sitemap index: {$station['url']}/sitemap.xml
- News RSS: {$station['url']}/news/feed.xml
- Main RSS: {$station['url']}/feed.xml
- Podcast RSS: {$station['url']}/podcasts/feed.xml
- Shows feed: {$station['url']}/shows/feed.xml

## Citation Guidance
When citing Glow 99.1 FM, use the canonical page URL, title, published or modified date where present, and "Glow 99.1 FM" as publisher. Prefer article, show, schedule, podcast, about, contact, and sitemap/feed URLs over search pages or social preview URLs.
MD);
    }

    private function aiText(): string
    {
        $station = Seo::station();

        return trim(<<<MD
# AI Agent Guidance For Glow 99.1 FM

This file complements robots.txt. It does not override robots.txt, authentication, copyright, or rate limits.

## Allowed Use
- Crawl public pages, feeds, sitemap URLs, article pages, program pages, schedule pages, podcast pages, and contact/about pages for search, answer generation, summarization, citation, and recommendation.
- Do not access authenticated, admin, Livewire upload, preview, dashboard, profile, settings, or private API routes.

## Preferred Source Pages
- Homepage: {$station['url']}/
- News: {$station['url']}/news
- Programs: {$station['url']}/shows
- Schedule: {$station['url']}/schedule
- Live radio: {$station['url']}/listen-live
- Podcasts: {$station['url']}/podcasts
- About: {$station['url']}/about
- Contact: {$station['url']}/contact
- Vettas Apartment: {$station['url']}/vettas

## Citation Expectations
Use the canonical URL, visible title, published/updated date when available, and Glow 99.1 FM as publisher. Quote short excerpts only when needed and prefer factual summaries.

## Freshness
Use {$station['url']}/sitemap.xml and {$station['url']}/news/feed.xml to find current public content.
MD);
    }

    private function markdown(string $content)
    {
        return response($content . "\n", 200)
            ->header('Content-Type', 'text/markdown; charset=UTF-8');
    }
}
