
@php
echo   "<?xml version='1.0' encoding='UTF-8'?>
  <urlset
    xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"
    xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
    xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">
  ";
@endphp
    @foreach($posts as $post)
        @if($post->category!=null)
            <url>
                <loc>{{ route('post', ['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }}</loc>
                <lastmod>{{ date('Y-m-d', strtotime($post->created_at)) }} </lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.9</priority>
            </url>
        @endif
    @endforeach



</urlset>
