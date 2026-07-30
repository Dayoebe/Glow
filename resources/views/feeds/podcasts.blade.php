@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:media="http://search.yahoo.com/mrss/">
    <channel>
        <title>{{ $station['name'] }} Podcasts</title>
        <link>{{ \App\Support\Seo::absoluteUrl(route('podcasts.index')) }}</link>
        <atom:link href="{{ \App\Support\Seo::absoluteUrl(route('podcasts.feed')) }}" rel="self" type="application/rss+xml" />
        <description>Podcast and audio/video episodes from {{ $station['name'] }}.</description>
        <language>en-ng</language>
        @if ($lastBuildDate)
            <lastBuildDate>{{ $lastBuildDate->toRfc2822String() }}</lastBuildDate>
        @endif
        @foreach ($items as $item)
            @php
                $url = \App\Support\Seo::absoluteUrl(route('podcasts.episode', ['showSlug' => $item->show?->slug, 'episodeSlug' => $item->slug]));
                $description = \App\Support\Seo::text($item->description ?: $item->show_notes, 300);
            @endphp
            <item>
                <title>{{ $item->title }}</title>
                <link>{{ $url }}</link>
                <guid isPermaLink="true">{{ $url }}</guid>
                <description>{{ $description }}</description>
                <pubDate>{{ optional($item->published_at)->toRfc2822String() }}</pubDate>
                <itunes:author>{{ $item->show?->host_name ?: $station['name'] }}</itunes:author>
                <itunes:duration>{{ $item->formatted_duration }}</itunes:duration>
                @if ($item->feed_image_url)
                    <itunes:image href="{{ $item->feed_image_url }}" />
                    <media:content url="{{ $item->feed_image_url }}" medium="image" />
                @endif
                @if ($item->feed_audio_enclosure)
                    <enclosure
                        url="{{ $item->feed_audio_enclosure['url'] }}"
                        length="{{ $item->feed_audio_enclosure['length'] }}"
                        type="{{ $item->feed_audio_enclosure['type'] }}"
                    />
                @endif
            </item>
        @endforeach
    </channel>
</rss>
