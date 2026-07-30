@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
@php
    $namespaces = '';
    if ($includeImages) {
        $namespaces .= ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"';
    }
    if ($includeVideos) {
        $namespaces .= ' xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"';
    }
@endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"{!! $namespaces !!}>
@foreach ($urls as $url)
    <url>
        <loc>{{ $url['loc'] }}</loc>
        @isset($url['lastmod'])
        <lastmod>{{ $url['lastmod'] }}</lastmod>
        @endisset
        @isset($url['changefreq'])
        <changefreq>{{ $url['changefreq'] }}</changefreq>
        @endisset
        @isset($url['priority'])
        <priority>{{ $url['priority'] }}</priority>
        @endisset
        @if($includeImages)
            @foreach((array) ($url['images'] ?? []) as $image)
                @continue(empty($image['loc']))
                <image:image>
                    <image:loc>{{ $image['loc'] }}</image:loc>
                    @if(!empty($image['title']))
                    <image:title>{{ $image['title'] }}</image:title>
                    @endif
                </image:image>
            @endforeach
        @endif
        @if($includeVideos)
            @foreach((array) ($url['videos'] ?? []) as $video)
                @continue(empty($video['content_loc']) && empty($video['player_loc']))
                <video:video>
                    @if(!empty($video['thumbnail_loc']))
                    <video:thumbnail_loc>{{ $video['thumbnail_loc'] }}</video:thumbnail_loc>
                    @endif
                    <video:title>{{ $video['title'] }}</video:title>
                    <video:description>{{ $video['description'] }}</video:description>
                    @if(!empty($video['content_loc']))
                    <video:content_loc>{{ $video['content_loc'] }}</video:content_loc>
                    @endif
                    @if(!empty($video['player_loc']))
                    <video:player_loc allow_embed="yes">{{ $video['player_loc'] }}</video:player_loc>
                    @endif
                    @if(!empty($video['publication_date']))
                    <video:publication_date>{{ $video['publication_date'] }}</video:publication_date>
                    @endif
                </video:video>
            @endforeach
        @endif
    </url>
@endforeach
</urlset>
