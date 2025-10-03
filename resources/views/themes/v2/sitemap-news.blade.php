
@php
	'
	<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet href="/vendor/te/packages/sitemap/styles/google-news.xsl" type="text/xsl"?>
	';
	$settings = json_decode(Storage::disk('public')->get("settings.json"), TRUE);
	$newsName = json_decode($settings["magicbox"], TRUE)['googlenews'];
@endphp

<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9' 
	xmlns:news='http://www.google.com/schemas/sitemap-news/0.9' 
	xmlns:xhtml='http://www.w3.org/1999/xhtml' 
	xmlns:image='http://www.google.com/schemas/sitemap-image/1.1'>

@foreach ($posts as $post)
    @if ($post->category != null)
        <url>
			<loc>{{ route('post', ['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }}</loc>
            <news:news>
                <news:publication>
                    <news:name>{{!blank($newsName)?$newsName:"newsName"}}</news:name>
                    <news:language>tr</news:language>
                </news:publication>
                <news:publication_date>{{ date('Y-m-d', strtotime($post->created_at)) }}</news:publication_date>
                <news:title>
                    <![CDATA[{{$post->title}}]]>
                </news:title>
            </news:news>
        </url>
    @endif
@endforeach

</urlset>
