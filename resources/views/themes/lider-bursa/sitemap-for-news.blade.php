{!! $xmlDeclaration !!}
{!! $xmlStylesheet !!}

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($posts as $post)
        <url>
            <loc>{{ url('/' . $post->category->slug . '/' . $post->slug . '/' . $post->id) }}</loc>
            <loc>{{ route('post', ['slug' => $post->slug, 'id' => $post->id]) }}</loc>
            <title><![CDATA[{!! $post->title !!} ]]></title>
            <language>tr</language>
            <genres></genres>
            <keywords><![CDATA[{{ $post->keywords }}]]></keywords>
            <publishedDate>{{ \Carbon\Carbon::parse($post->created_at)->format('Y-m-d H:i:s') }}</publishedDate>
        </url>
    @endforeach
</urlset>
