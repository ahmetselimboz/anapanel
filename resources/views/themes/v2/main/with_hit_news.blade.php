@if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/hit_news.json'))
@php $trend_news = collect(\Illuminate\Support\Facades\Storage::disk('public')->json('main/hit_news.json'));@endphp

    <div class="container">
	<div class="spotlar">
		
	@php
	$counter = 1;
	@endphp
	@foreach ($trend_news->take(21) as $trend)
							
		<div class="spot spotduz spotduz-{{ $counter }}">
		<a href="{{ route('post', ['categoryslug' => categoryCheck($trend['category_id'])->slug, 'slug' => $trend['slug'], 'id' => $trend['id']]) }}" title="{{ $trend['title'] }}">
		<b>{{ categoryCheck($trend['category_id'])->title }}</b>
		<img src="{{ route('resizeImage', ["i_url" => imageCheck($trend["images"]), "w" => 377, "h" => 247]) }}" alt="{{html_entity_decode($trend["title"]) }}" alt="{{ html_entity_decode($trend["title"]) }}" />
		<p><span>{{html_entity_decode($trend["title"]) }}</span></p>
		</a>
		</div>
		
	@php
	$counter++;
	@endphp
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
