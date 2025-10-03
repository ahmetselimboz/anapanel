{!! $xmlDeclaration !!}
{!! $xmlStylesheet !!}

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($categories as $category)
        <url>
            <loc>{{ url('/' . $category->slug) }}</loc>
            <title><![CDATA[{!! $category->title !!}]]></title>
            <language>tr</language>
            <genres></genres>
            <keywords><![CDATA[{!! $category->title !!}]]></keywords>
            <publishedDate>{{ \Carbon\Carbon::parse($category->updated_at)->format('Y-m-d H:i:s') }}</publishedDate>
        </url>
    @endforeach
</urlset>
