<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
    <title>{{ setting('site.name', 'To The North') }}</title>
    <link>{{ url('/') }}</link>
    <description>{{ setting('site.description', 'Stories from the north of Pakistan.') }}</description>
    <language>en</language>
    <atom:link href="{{ url('/feed.xml') }}" rel="self" type="application/rss+xml" />
@foreach ($stories as $story)
    <item>
        <title>{{ $story->title }}</title>
        <link>{{ $story->url }}</link>
        <guid isPermaLink="true">{{ $story->url }}</guid>
        <pubDate>{{ $story->published_at->toRssString() }}</pubDate>
        <description>{{ $story->excerpt }}</description>
    </item>
@endforeach
</channel>
</rss>
