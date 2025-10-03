@extends('themes.'.$theme.'.frontend_layout')

@section('meta')
    <title>Video Galeriler</title>
    <meta name="description" content="Video Galeriler">
@endsection

@section('content')

<div class="container">

	<div class="spotlar">

	   <div class="col-12">
            <div class="category-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                <h2 class="text-black">Video Galeri</h2>
                <div class="headline-block-indicator"><div class="indicator-ball" style="background-color:#EC0000;"></div></div>
            </div>
        </div>

        <div class="solda">

            @foreach($video_galleries as $key => $video_gallery)

			<div class="spot-sabit spot-7 spot-7-1">
			<a href="@if($video_gallery->category!=null) {{ route('video_gallery', ['categoryslug'=>$video_gallery->category->slug,'slug'=>$video_gallery->slug,'id'=>$video_gallery->id]) }} @endif" title="{{ $video_gallery["title"] }}">
			<p><b>VİDEO</b><span>{{ $video_gallery["title"] }}</span></p>
			<div class="spot-sabit-resim">
			<img src="{{ route('resizeImage', ["i_url" => imageCheck($video_gallery["images"]), "w" => 777, "h" => 510]) }}" alt="{{ $video_gallery["title"] }}" />
			</div>
			</a>
			</div>

			@break($key==0)
			@endforeach

		</div>

		<div class="sagda">

			@foreach($video_galleries as $key => $video_gallery)
			@if($key==1)

					<div class="spot-sabit spot-6 spot-6-0">
					<a href="@if($video_gallery->category!=null) {{ route('video_gallery', ['categoryslug'=>$video_gallery->category->slug,'slug'=>$video_gallery->slug,'id'=>$video_gallery->id]) }} @endif" title="{{ $video_gallery["title"] }}">
					<p><b>VİDEO</b><span>{{ $video_gallery["title"] }}</span></p>
					<div class="spot-sabit-resim">
					<img src="{{ route('resizeImage', ["i_url" => imageCheck($video_gallery["images"]), "w" => 377, "h" => 261 ]) }}" alt="{{ $video_gallery["title"] }}" />
					</div>
					</a>
					</div>

			@break($key==1)
			@endif
			@endforeach

			@foreach($video_galleries as $key => $video_gallery)
			@if($key==2)

					<div class="spot-sabit spot-6 spot-6-1">
					<a href="@if($video_gallery->category!=null) {{ route('video_gallery', ['categoryslug'=>$video_gallery->category->slug,'slug'=>$video_gallery->slug,'id'=>$video_gallery->id]) }} @endif" title="{{ $video_gallery["title"] }}">
					<p><b>VİDEO</b><span>{{ $video_gallery["title"] }}</span></p>
					<div class="spot-sabit-resim">
					<img src="{{ route('resizeImage', ["i_url" => imageCheck($video_gallery["images"]), "w" => 377, "h" => 261]) }}" alt="{{ $video_gallery["title"] }}" />
					</div>
					</a>
					</div>

			@break($key==2)
			@endif
			@endforeach

		</div>

	</div>

</div>

@if(adsCheck($ads15->id))
    <div class="container mb-4">
        @if(adsCheck($ads15->id)->type==1)
            <div class="ad-block">{!! adsCheck($ads15->id)->code !!}</div>
        @else
            <div class="ad-block">
                <a href="{{ adsCheck($ads15->id)->url }}" title="Reklam {{$ads15->id}}" class="externallink">
                    <img src="{{ asset('uploads/'.adsCheck($ads15->id)->images) }}" alt="Reklam {{$ads15->id}}" class="img-fluid lazy" data-type="{{ adsCheck($ads15->id)->type }}" height="{{ adsCheck($ads15->id)->height }}" width="{{ adsCheck($ads15->id)->width }}">
                </a>
            </div>
        @endif
    </div>
@endif

	<div class="container">
    <div class="spotlar">

		<div class="col-12">
            <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                <h2 class="text-black">Diğer Galeriler</h2>
                <div class="headline-block-indicator"><div class="indicator-ball" style="background-color:#EC0000;"></div></div>
            </div>
        </div>

			@php $counter = 1; @endphp
			@foreach($video_galleries as $key => $video_gallery)
			@if($key>2 and $key<60)

					<div class="spot spotduz spotduz-{{ $counter }}">
						<a href="{{ route('video_gallery', ['categoryslug' => categoryCheck($video_gallery['category_id'])->slug, 'slug' => $video_gallery['slug'], 'id' => $video_gallery['id']]) }}" title="{{ $video_gallery['title'] }}">
						<b>{{ categoryCheck($video_gallery['category_id'])->title }}</b>
						<div class="spot-resim"><img src="{{ route('resizeImage', ["i_url" => imageCheck($video_gallery["images"]), "w" => 377, "h" => 210]) }}" alt="{{html_entity_decode($video_gallery["title"]) }}" alt="{{ html_entity_decode($video_gallery["title"]) }}" /></div>
						<p><span>{{html_entity_decode($video_gallery["title"]) }}</span></p>
						</a>
					</div>

			@php $counter++; @endphp
            @endif
            @endforeach

    {{ $video_galleries->links() }}

	</div>
    </div>

@endsection