@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($sections as $section)
    <sitemap>
        <loc>{{ $section['loc'] }}</loc>
        @isset($section['lastmod'])
        <lastmod>{{ $section['lastmod'] }}</lastmod>
        @endisset
    </sitemap>
@endforeach
</sitemapindex>
