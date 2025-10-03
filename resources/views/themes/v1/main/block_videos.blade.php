@if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/videos.json'))


    @php $videos = \Illuminate\Support\Facades\Storage::disk('public')->json('main/videos.json'); @endphp
    @if (!blank($videos))
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="position-relative overflow-hidden rounded-1">
                        <div class="block-headline bg-video-gallery text-black">VİDEO GALERİ</div>
                        <div class="video-gallery-block">
                            <div class="container bg-black overflow-hidden rounded-1 mb-4">
                                <div class="row">
                                    @foreach ($videos as $vkey => $video)
                                        @if ($vkey == 0)
                                            <div class="col-4 d-none d-lg-block position-relative">
                                                <div class="px-4 vertical-center">
                                                    <a href="{{ route('video_gallery', ['categoryslug' => $video['categoryslug'], 'slug' => $video['slug'], 'id' => $video['id']]) }}"
                                                        title="{{ $video['title'] }}" class="externallink">
                                                        <h1 class="horizontal-card-title text-white pt-3">
                                                            {{ $video['title'] }} </h1>
                                                        <p class="horizontal-card-desc text-video-gallery mb-0">
                                                            {!! isset($video['detail']) ? \Illuminate\Support\Str::limit($video['detail'], 178) : '...' !!}
                                                        </p>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-8">
                                                <div class="video-gallery-thumbnail">
                                                    <a href="{{ route('video_gallery', ['categoryslug' => $video['categoryslug'], 'slug' => $video['slug'], 'id' => $video['id']]) }}"
                                                        title="{{ $video['title'] }}" class="externallink">
                                                        <img src="
                                                    {{ route('resizeImage', ['i_url' => imageCheck($video['images']), 'w' => 786, 'h' => 443]) }}
                                                        "
                                                            class="w-100 lazy">
                                                        <i class="video-play-icon"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-12 d-blcok d-lg-none">
                                                <div class="px-4 pt-4">
                                                    <a href="{{ route('video_gallery', ['categoryslug' => $video['categoryslug'], 'slug' => $video['slug'], 'id' => $video['id']]) }}"
                                                        title="{{ $video['title'] }}" class="externallink">
                                                        <h1 class="horizontal-card-title text-white">
                                                            {{ $video['title'] }}
                                                        </h1>
                                                        <p class="horizontal-card-desc text-video-gallery">
                                                            {!! isset($video['detail']) ? \Illuminate\Support\Str::limit($video['detail'], 178) : '...' !!}
                                                        </p>
                                                    </a>
                                                </div>
                                            </div>
                                        @break
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @foreach ($videos as $vkey => $video)
                @if ($vkey > 0)
                    <div class="col-6 col-md-4 col-lg-2 mb-4">
                        <div class="card overflow-hidden rounded-1 border-0 shadow-sm video-news-card">
                            <a href="{{ route('video_gallery', ['categoryslug' => $video['categoryslug'], 'slug' => $video['slug'], 'id' => $video['id']]) }}"
                                title="{{ $video['title'] }}" class="externallink">
                                <img src="
                                {{ route('resizeImage', ['i_url' => imageCheck($video['images']), 'w' => 179, 'h' => 100]) }}
                                    "
                                    alt="{{ $video['title'] }}" class="card-img-top rounded-0 lazy">
                                <i class="video-play-icon"></i>
                            </a>
                            <div class="card-body bg-black">
                                <a href="{{ route('video_gallery', ['categoryslug' => $video['categoryslug'], 'slug' => $video['slug'], 'id' => $video['id']]) }}"
                                    title="{{ $video['title'] }}" class="externallink">
                                    <h5 class="card-title video-news-card-title text-white">{{ $video['title'] }}
                                    </h5>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif
@else
<div class="container d-none">
    <div class="row">
        <div class="alert alert-warning"> Videolar Bulunamadı </div>
    </div>
</div>
@endif
