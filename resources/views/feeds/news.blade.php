@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
    <channel>
        <title>{{ $station['name'] }} Latest News</title>
        <link>{{ \App\Support\Seo::absoluteUrl(route('news')) }}</link>
        <atom:link href="{{ \App\Support\Seo::absoluteUrl(route('news.feed')) }}" rel="self" type="application/rss+xml" />
        <description>Latest public news articles from {{ $station['name'] }} in Akure, Ondo State, Nigeria.</description>
        <language>en-ng</language>
        @if ($lastBuildDate)
            <lastBuildDate>{{ $lastBuildDate->toRfc2822String() }}</lastBuildDate>
        @endif
        @foreach ($items as $item)
            @php
                $url = \App\Support\Seo::absoluteUrl(route('news.show', $item->slug));
                $description = $item->excerpt ?: \App\Support\Seo::text($item->content, 300);
            @endphp
            <item>
                <title>{{ $item->title }}</title>
                <link>{{ $url }}</link>
                <guid isPermaLink="true">{{ $url }}</guid>
                <description>{{ $description }}</description>
                <pubDate>{{ optional($item->published_at)->toRfc2822String() }}</pubDate>
                <category>{{ $item->category?->name ?? 'News' }}</category>
                <author>{{ $station['email'] }} ({{ $item->author?->name ?? $station['name'] }})</author>
                @if ($item->feed_image_url)
                    <media:content url="{{ $item->feed_image_url }}" medium="image" />
                @endif
            </item>
        @endforeach
    </channel>
</rss>
