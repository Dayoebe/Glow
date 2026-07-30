@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
    <channel>
        <title>{{ $station['name'] }} Programs</title>
        <link>{{ \App\Support\Seo::absoluteUrl(route('shows.index')) }}</link>
        <atom:link href="{{ \App\Support\Seo::absoluteUrl(route('shows.feed')) }}" rel="self" type="application/rss+xml" />
        <description>Active radio programs and shows from {{ $station['name'] }}.</description>
        <language>en-ng</language>
        @if ($lastBuildDate)
            <lastBuildDate>{{ $lastBuildDate->toRfc2822String() }}</lastBuildDate>
        @endif
        @foreach ($items as $item)
            @php
                $url = \App\Support\Seo::absoluteUrl(route('shows.show', $item->slug));
                $description = \App\Support\Seo::text($item->description ?: $item->full_description, 300);
            @endphp
            <item>
                <title>{{ $item->title }}</title>
                <link>{{ $url }}</link>
                <guid isPermaLink="true">{{ $url }}</guid>
                <description>{{ $description }}</description>
                <category>{{ $item->category?->name ?? 'Program' }}</category>
                <author>{{ $station['email'] }} ({{ $item->primaryHost?->name ?? $station['name'] }})</author>
                @if ($item->updated_at)
                    <pubDate>{{ $item->updated_at->toRfc2822String() }}</pubDate>
                @endif
                @if ($item->feed_image_url)
                    <media:content url="{{ $item->feed_image_url }}" medium="image" />
                @endif
            </item>
        @endforeach
    </channel>
</rss>
