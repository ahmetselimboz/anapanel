@php
    "<?xml version='1.0' encoding='UTF-8'?>".PHP_EOL;

    $settings = json_decode(Storage::disk('public')->get("settings.json"), TRUE);


@endphp
<rss version="2.0">
    <channel>
        <title>{{$settings['title']}} RSS</title>
        <link>{{route('frontend.index')}}</link>
        <description>{{$settings['description']}} </description>
        <language>tr-TR</language>
        <lastmod>{{ date('Y-m-d', strtotime(now()) )}} </lastmod>
        @foreach ($posts as $post )
        <item>
            <title>{{$post->title}}</title>
            <link>{{ route('post', ['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }}</link>
            <description>{{$post->description}}</description>
            <pubDate>{{ date('Y-m-d', strtotime($post->created_at)) }} </pubDate>

        </item>
        @endforeach
        <!-- Daha fazla haber öğesi ekleyebilirsiniz -->
    </channel>
</rss>
