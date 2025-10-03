<!-- Masaüstü Ana Manşetler -->

@php
$ana_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/ana_manset.json');
@endphp

<div class="container">
<div class="spotlar">

	@php $counter = 1; @endphp
	   
	@foreach ($ana_mansetler as $manset_key => $ana_manset)

		@if ($manset_key >= 10)
				
			<div class="spot spotduz spotduz-{{ $counter }}">
			<a href="{{ route('post', ['categoryslug' => categoryCheck($ana_manset['category_id'])->slug, 'slug' => $ana_manset['slug'], 'id' => $ana_manset['id']]) }}" title="{{ $ana_manset['title'] }}">
			<b>{{ categoryCheck($ana_manset['category_id'])->title }}</b>
			<div class="spot-resim"><img src="{{ route('resizeImage', ["i_url" => imageCheck($ana_manset["images"]), "w" => 550, "h" => 307]) }}" alt="{{html_entity_decode($ana_manset["title"]) }}" alt="{{ html_entity_decode($ana_manset["title"]) }}" /></div>
			<p><span>{{html_entity_decode($ana_manset["title"]) }}</span></p>
			</a>
			</div>
	
		@php $counter++; @endphp
				
		@endif

		@if ($counter == 4) @break @endif
			
	@endforeach
    
</div>
</div>

<!-- Mini Manşetler -->

@if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/mini_manset.json'))
    
@php
$mini_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/mini_manset.json');
@endphp

<div class="container">
<div class="spotlar">

	@php $counter = 1; @endphp

	@foreach ($mini_mansetler as $minimanset_key => $mini_manset)
		
		@if ($minimanset_key >= 0)
								
			<div class="spot spotduz spotduz-{{ $counter }}">
			<a href="{{ route('post', ['categoryslug' => categoryCheck($mini_manset['category_id'])->slug, 'slug' => $mini_manset['slug'], 'id' => $mini_manset['id']]) }}" title="{{ $mini_manset['title'] }}">
			<b>{{ categoryCheck($mini_manset['category_id'])->title }}</b>
			<div class="spot-resim"><img src="{{ route('resizeImage', ["i_url" => imageCheck($mini_manset["images"]), "w" => 550, "h" => 307]) }}" alt="{{html_entity_decode($mini_manset["title"]) }}" alt="{{ html_entity_decode($mini_manset["title"]) }}" /></div>
			<p><span>{{html_entity_decode($mini_manset["title"]) }}</span></p>
			</a>
			</div>

		@php $counter++; @endphp
		
		@endif

		@if ($counter == 4) @break @endif
	
	@endforeach

</div>
</div>
	
@else
    
	<div class="container">
	<div class="row my-3">
	<div class="alert alert-warning">Altılı Manşet Bulunamadı</div>
	</div>
	</div>
	
@endif