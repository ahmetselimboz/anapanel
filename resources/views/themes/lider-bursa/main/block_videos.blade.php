@if(\Illuminate\Support\Facades\Storage::disk('public')->exists('main/videos.json'))
    <div class="container">
	<div class="spotlar">

	<div class="col-12">
			<div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
			<h2 class="text-black">VİDEO GALERİ</h2>
			<div class="headline-block-indicator">
			<div class="indicator-ball" style="background-color:#252525!important;"></div>
			</div>
			<a href="/video-galeriler" class="all-button externallink">Tümü</a>
			</div>
	</div>

	@php $videos = \Illuminate\Support\Facades\Storage::disk('public')->json('main/videos.json'); @endphp

			<div class="solda">

				@foreach($videos as $vkey => $video)
				@if($vkey==0)

					<div class="spot-sabit spot-7 spot-7-1">
					<a href="{{ route('video_gallery', ['categoryslug'=>$video["categoryslug"],'slug'=>$video["slug"],'id'=>$video["id"]] ) }}" title="{{ $video["title"] }}">
					<p><span>{{ $video["title"] }}</span></p>
					<img src="{{ route('resizeImage', ["i_url" => imageCheck($video["images"]), "w" => 800, "h" => 450]) }}" alt="{{ $video["title"] }}" />
					</a>
					</div>

				@endif
				@endforeach

			</div>

			<div class="sagda">

				@foreach($videos as $vkey => $video)
				@if($vkey==1)

					<div class="spot-sabit spot-6 spot-6-0">
					<a href="{{ route('video_gallery', ['categoryslug'=>$video["categoryslug"],'slug'=>$video["slug"],'id'=>$video["id"]] ) }}" title="{{ $video["title"] }}">
					<p><span>{{ $video["title"] }}</span></p>
					<img src="{{ route('resizeImage', ["i_url" => imageCheck($video["images"]), "w" => 350, "h" => 200]) }}" alt="{{ $video["title"] }}" />
					</a>
					</div>

				@endif
				@endforeach

				@foreach($videos as $vkey => $video)
				@if($vkey==2)

					<div class="spot-sabit spot-6 spot-6-1">
					<a href="{{ route('video_gallery', ['categoryslug'=>$video["categoryslug"],'slug'=>$video["slug"],'id'=>$video["id"]] ) }}" title="{{ $video["title"] }}">
					<p><span>{{ $video["title"] }}</span></p>
					<img src="{{ route('resizeImage', ["i_url" => imageCheck($video["images"]), "w" => 350, "h" => 200]) }}" alt="{{ $video["title"] }}" />
					</a>
					</div>

				@endif
				@endforeach

			</div>

	</div>
    </div>

@else

    <div class="container d-none">
	<div class="row">
	<div class="alert alert-warning"> Videolar Bulunamadı </div>
	</div>
    </div>

@endif
