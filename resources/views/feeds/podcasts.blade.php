@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:media="http://search.yahoo.com/mrss/">
    <channel>
        <title>{{ $station['name'] }} Podcasts</title>
        <link>{{ \App\Support\Seo::absoluteUrl(route('podcasts.index')) }}</link>
        <atom:link href="{{ \App\Support\Seo::absoluteUrl(route('podcasts.feed')) }}" rel="self" type="application/rss+xml" />
        <description>Podcast and audio/video episodes from {{ $station['name'] }}.</description>
        <language>en-ng</language>
        <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>
        @foreach ($items as $item)
            @php
                $url = \App\Support\Seo::absoluteUrl(route('podcasts.episode', ['showSlug' => $item->show?->slug, 'episodeSlug' => $item->slug]));
                $image = \App\Support\Seo::absoluteUrl($item->cover_image ?: $item->show?->cover_image);
                $audio = \App\Support\Seo::absoluteUrl($item->audio_file);
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
                @if ($image)
                    <itunes:image href="{{ $image }}" />
                    <media:content url="{{ $image }}" medium="image" />
                @endif
                @if ($audio)
                    <enclosure url="{{ $audio }}" length="{{ (int) $item->file_size }}" type="audio/{{ $item->audio_format ?: 'mpeg' }}" />
                @endif
            </item>
        @endforeach
    </channel>
</rss>
