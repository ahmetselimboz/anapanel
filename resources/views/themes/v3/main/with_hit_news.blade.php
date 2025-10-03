@if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/hit_news.json'))
@php $trend_news = collect(\Illuminate\Support\Facades\Storage::disk('public')->json('main/hit_news.json'));@endphp

    <div class="container">
	<div class="spotlar">

	<div class="col-12">
	<div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
	<h2 class="text-black">EN ÇOK OKUNANLAR</h2>
	<div class="headline-block-indicator">
	<div class="indicator-ball" style="background-color:#000 !important;"></div>
	</div>
	</div>
	</div>

	@php $counter = 1; @endphp

	@foreach ($trend_news->take(21) as $trend)

		<div class="spot spotduz spotduz-{{ $counter }}">
		<a href="{{ route('post', ['categoryslug' => categoryCheck($trend['category_id'])->slug, 'slug' => $trend['slug'], 'id' => $trend['id']]) }}" title="{{ $trend['title'] }}">
		<b>{{ categoryCheck($trend['category_id'])->title }}</b>
		<div class="spot-resim"><img src="{{ route('resizeImage', ["i_url" => imageCheck($trend["images"]), "w" => 550, "h" => 307]) }}" alt="{{html_entity_decode($trend["title"]) }}" alt="{{ html_entity_decode($trend["title"]) }}" /></div>
		<p><span>{{html_entity_decode($trend["title"]) }}</span></p>
		</a>
		</div>

		@php $counter++; @endphp

	@endforeach

	</div>
	</div>

@else

	<div class="container">
	<div class="row">
	<div class="alert alert-warning"> Trend haberler bulunamadı</div>
	</div>
	</div>

@endif

<div style="clear:both;"></div>
