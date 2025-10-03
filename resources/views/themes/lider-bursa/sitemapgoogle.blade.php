{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($posts as $post)
        @if($post->category!=null)
            <sitemap>
                <loc>{{ route('post', ['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }}</loc>
            </sitemap>
        @endif
    @endforeach
</sitemapindex>
