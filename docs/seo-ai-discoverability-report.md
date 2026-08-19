# Vettas SEO and AI Discoverability

Updated: 2026-08-17

## Public discovery surface

- Each active Vettas category containing at least one published photo has a server-rendered page at `/vettas/category/{slug}`.
- Category pages expose a unique title, meta description, canonical URL, visible breadcrumb, descriptive copy, image alternative text, internal links, and optional FAQ content.
- FAQ content is rendered visibly and included as `FAQPage` JSON-LD when administrators provide complete question-and-answer pairs.
- Active category pages are included in the pages sitemap and the AI-readable digest at `/llms-full.txt`.
- The main Vettas gallery contains crawlable links to category pages. Inactive categories and categories without published photos return 404 and are excluded from the sitemap and AI digest.

## Editorial controls

The Vettas category editor now manages the page eyebrow, headline, full description, SEO title, search description, highlights, and FAQs. Copy should remain factual and specific to the actual apartment space. Do not publish amenities, locations, availability, or guest claims that have not been confirmed.

## Validation

- Targeted Vettas feature suite: 10 tests passed, 41 assertions.
- Edited PHP files passed `php -l`.
- Blade templates compiled with `php artisan view:cache`.
- The named category route is registered.
- `git diff --check` passed.

## Launch tasks

- Apply the pending Vettas category-content migration in the deployed environment.
- Complete unique content for every category before expecting it to compete in search.
- Confirm the production canonical domain and inspect representative category HTML after deployment.
- Submit or refresh the sitemap in Google Search Console and Bing Webmaster Tools.
- Monitor impressions, indexed pages, click-through rate, enquiries, and search terms; technical discoverability does not guarantee rankings.
