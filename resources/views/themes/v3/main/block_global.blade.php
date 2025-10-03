@if(\Illuminate\Support\Facades\Storage::disk('public')->exists('main/standart_haberler.json'))
@php $global_posts = \Illuminate\Support\Facades\Storage::disk('public')->json('main/standart_haberler.json'); $csay=0; $c2say=0; @endphp

	@if(isset($design))
	
	@if($design=="default")
           
		<!-- MANŞET ALTINDAKİ HABER BLOK TASARIMI News (Haberler) -->
		@if(categoryCheck($block_category_id)!=false)
	
			<div class="container">
			<div class="spotlar">
			
			<div class="col-12">
			<div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
			<h2 class="text-black">{{ isset($block_category_id) ? categoryCheck($block_category_id)->title : "Kategori ismi bulunamadı" }}</h2>
			<div class="headline-block-indicator">
			<div class="indicator-ball" style="background-color:{{ categoryCheck($block_category_id)->color }}!important;"></div>
			</div>
			<a href="{{ route('category', ['slug'=>categoryCheck($block_category_id)->slug,'id'=>categoryCheck($block_category_id)->id]) }}" class="all-button externallink" title="{{ categoryCheck($block_category_id)->title }}">Tümü</a>
			</div>
			</div>
			
			<div class="solkutu">

				<div class="spotlistesi">
				@php $osay = 0; @endphp
				@foreach($global_posts as $key => $global_post)
				@if($global_post["category_id"]==$block_category_id)

					<div class="spot-list">
					<a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" title="{{ html_entity_decode($global_post["title"]) }}">
					<p><span style="height:46px;">{{html_entity_decode($global_post["title"]) }}</span></p>
					</a>
					</div>

				@php $osay++; $pre_key=$key; @endphp
				@endif
				
				@if($osay==5) @break @endif
				
				@endforeach
				</div>

			</div>

			<div class="solkutu">

				<div class="spotlistesi">
				@php $osay = 0; @endphp
				@foreach($global_posts as $key => $global_post)
				@if($global_post["category_id"]==$block_category_id)
				
					@if($osay<5) @else

					<div class="spot-list">
					<a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" title="{{ html_entity_decode($global_post["title"]) }}">
					<p><span style="height:46px;">{{html_entity_decode($global_post["title"]) }}</span></p>
					</a>
					</div>
					 
					 @endif
					 
				@php $osay++; $pre_key=$key; @endphp
				@endif
				
				@if($osay==10) @break @endif
				
				@endforeach
				</div>

			</div>

			<div class="solkutu sagkutu">

				<div class="spotlistesi">
				@php $osay = 0; @endphp
				@foreach($global_posts as $key => $global_post)
				@if($global_post["category_id"]==$block_category_id)

					@if($osay<10) @else

					<div class="spot-list">
					<a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" title="{{ html_entity_decode($global_post["title"]) }}">
					<p><span style="height:46px;">{{html_entity_decode($global_post["title"]) }}</span></p>
					</a>
					</div>
					
					 @endif

				@php $osay++; $pre_key=$key; @endphp
				@endif
				
				@if($osay==15) @break @endif
				
				@endforeach
				</div>

			</div>

			</div>
			</div>
			
			<style>
			@media only screen and (max-width: 1000px) {
				.spotlistesi .spot-list a p span { height:auto !important; }
			}
			</style>
			
		@else
	
			<div class="container">
			<div class="row">
			<!--@dump($block)-->
			<div class="alert alert-warning"> Seçilen kategori bulunamadığı için ilgili tasarıma haber getirilemedi. Panel > Anasayfa Sıralama altından kontrol ediniz.</div>
			</div>
			</div>
	
		@endif
	
	@elseif($design==1)
    
		<!-- EKONOMİ bölümü tasarımı Category News (Kategori) -->
		
		@if(categoryCheck($block_category_id)!=false)
		
			<div class="container">
			<div class="spotlar">
			
			<div class="col-12">
				<div class="news-headline-block justify-content-between mb-4">
				<h2 class="text-black">{{ isset($block_category_id) ? categoryCheck($block_category_id)->title : "Kategori ismi bulunamadı" }}</h2>
				<div class="headline-block-indicator">
				 <div class="indicator-ball" style="background-color:{{categoryCheck($block_category_id)->color}}!important;"></div>
				</div>
				<a href="{{ route('category', ['slug'=>categoryCheck($block_category_id)->slug,'id'=>categoryCheck($block_category_id)->id]) }}" class="all-button externallink" title="{{ categoryCheck($block_category_id)->title }}">Tümü</a>
				</div>
			</div>
			
			@php $counter = 1; @endphp
			
			@foreach($global_posts as $key => $global_post)
			
				@if($global_post["category_id"]==$block_category_id and $key>=0)
					
					<div class="spot spotduz spotduz-{{ $counter }}">
					<a href="{{ route('post', ['categoryslug' => categoryCheck($global_post['category_id'])->slug, 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}" title="{{ $global_post['title'] }}">
					<b>{{ categoryCheck($global_post['category_id'])->title }}</b>
					<div class="spot-resim"><img src="{{ route('resizeImage', ["i_url" => imageCheck($global_post["images"]), "w" => 550, "h" => 307]) }}" alt="{{html_entity_decode($global_post["title"]) }}" alt="{{ html_entity_decode($global_post["title"]) }}" /></div>
					<p><span>{{html_entity_decode($global_post["title"]) }}</span></p>
					</a>
					</div>

				@php $counter++; @endphp

				@endif

				@if ($counter == 13) @break @endif

			@endforeach
			
			</div>
			</div>
		
		@else
			
			<div class="container">
			<!--@dump($block)-->
			<div class="row">
			<div class="alert alert-warning">Seçilen kategori bulunamadığı için ilgili tasarıma haber getirilemedi. Panel > Anasayfa Sıralama altından kontrol ediniz.</div>
			</div>
			</div>
			
		@endif
	
	@elseif($design==2)

		@if(categoryCheck($block_category_id)!=false)
		
			<!-- GÜNDEM HABERLERİ TASARIMI Category News (Kategori) & Most Reading (En Çok Okunanalar)-->
			<div class="container">
			
			<div class="row">
			
			<div class="col-12">
			
			<div class="news-headline-block justify-content-between mb-4">
			<h2 class="text-black">{{ isset($block_category_id) ? categoryCheck($block_category_id)->title : "Kategori ismi bulunamadı" }}</h2>
			<div class="headline-block-indicator">
			<div class="indicator-ball" style="background-color:{{categoryCheck($block_category_id)->color}}!important;"></div>
			</div>
			<a href="{{ route('category', ['slug'=>categoryCheck($block_category_id)->slug,'id'=>categoryCheck($block_category_id)->id]) }}" class="all-button externallink" title="{{ categoryCheck($block_category_id)->title }}">Tümü</a>
			</div>
			
			<div class="spotlar">

				@php $counter = 1; @endphp

				@foreach($global_posts as $key => $global_post)
				
					@if($global_post["category_id"]==$block_category_id)

						<div class="spot spotduz spotduz-{{ $counter }}">
						<a href="{{ route('post', ['categoryslug' => categoryCheck($global_post['category_id'])->slug, 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}" title="{{ $global_post['title'] }}">
						<b>{{ categoryCheck($global_post['category_id'])->title }}</b>
						<div class="spot-resim"><img src="{{ route('resizeImage', ["i_url" => imageCheck($global_post["images"]), "w" => 550, "h" => 307]) }}" alt="{{html_entity_decode($global_post["title"]) }}" alt="{{ html_entity_decode($global_post["title"]) }}" /></div>
						<p><span>{{html_entity_decode($global_post["title"]) }}</span></p>
						</a>
						</div>
					
					@php $counter++; @endphp

					@endif
			
					@if($counter==13) @break @endif
				
				@endforeach

			</div>

			@if(false)	
				
				<div class="spotlar">
				
				@php $counter = 1; @endphp

				@foreach($global_posts as $key => $global_post)

					@if($global_post["category_id"]==$block_category_id )

						<div class="spot spotduz spotduz-{{ $counter }}">
						<a href="{{ route('post', ['categoryslug' => categoryCheck($global_post['category_id'])->slug, 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}" title="{{ $global_post['title'] }}">
						<b>{{ categoryCheck($global_post['category_id'])->title }}</b>
						<div class="spot-resim"><img src="{{ route('resizeImage', ["i_url" => imageCheck($global_post["images"]), "w" => 550, "h" => 307]) }}" alt="{{html_entity_decode($global_post["title"]) }}" alt="{{ html_entity_decode($global_post["title"]) }}" /></div>
						<p><span>{{html_entity_decode($global_post["title"]) }}</span></p>
						</a>
						</div>

					@php $counter++; @endphp
					
					@endif
					
					@if($counter==13) @break @endif
				
				@endforeach
				
				</div>
			
			@endif
			
			</div>

			</div>
			
			</div>
			
			</div>
		
		@else
		
			<div class="container">
			<!--@dump($block)-->
			<div class="row">
			<div class="alert alert-warning">Seçilen kategori bulunamadığı için ilgili tasarıma haber getirilemedi. Panel > Anasayfa Sıralama altından kontrol ediniz.</div>
			</div>
			</div>
		
		@endif
	
	@elseif($design==3)
    
		<!-- Category News (Kategori) & Most Reading (En Çok Okunanalar)-->
		@if(categoryCheck($block_category_id)!=false)

			<div class="container mb-5">
			<div class="spotlar">
			
			<div class="col-12">
			<div class="news-headline-block justify-content-between mb-4">
			<h2 class="text-black">{{ isset($block_category_id) ? categoryCheck($block_category_id)->title : "Kategori ismi bulunamadı" }}</h2>
			<div class="headline-block-indicator">
			<div class="indicator-ball" style="background-color:{{categoryCheck($block_category_id)->color}}!important;"></div>
			</div>
			<a href="{{ route('category', ['slug'=>categoryCheck($block_category_id)->slug,'id'=>categoryCheck($block_category_id)->id]) }}" class="all-button externallink" title="{{ categoryCheck($block_category_id)->title }}">Tümü</a>
			 </div>
			</div>
			
			@php $counter = 1; @endphp
			@foreach($global_posts as $key => $global_post)
			@if($global_post["category_id"]==$block_category_id)

				<div class="spot spotduz spotduz-{{ $counter }}">
				<a href="{{ route('post', ['categoryslug' => categoryCheck($global_post['category_id'])->slug, 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}" title="{{ $global_post['title'] }}">
				<b>{{ categoryCheck($global_post['category_id'])->title }}</b>
				<div class="spot-resim"><img src="{{ route('resizeImage', ["i_url" => imageCheck($global_post["images"]), "w" => 550, "h" => 307]) }}" alt="{{html_entity_decode($global_post["title"]) }}" alt="{{ html_entity_decode($global_post["title"]) }}" /></div>
				<p><span>{{html_entity_decode($global_post["title"]) }}</span></p>
				</a>
				</div>
	
			@php $counter++; @endphp
			@endif
			@if($counter==13) @break @endif
			@endforeach

			</div>
			</div>
		
		@else
		
			<div class="container">
			<div class="row">
			<!--@dump($block)-->
			<div class="alert alert-warning"> Seçilen kategori bulunamadığı için ilgili tasarıma haber getirilemedi. Panel > Anasayfa Sıralama altından kontrol ediniz.</div>
			</div>
			</div>
		
		@endif

    @else
	
		<div class="container">
		<div class="row">
		<div class="alert alert-warning"> Gelen tasarım değeri karşılığında tasarım bulunamadı !</div>
		</div>
		</div>
	
	@endif
    
	@else

		<div class="container">
		<div class="row">
		<div class="alert alert-warning"> {{ $block_id }} numaralı dizayn tanımlı değil !</div>
		</div>
		</div>

    @endif
	
@else
    
	<div class="container">
	<div class="row">
	<div class="alert alert-warning"> "Standart Haber" olarak eklenmiş haber türü bulunamadığı için tasarım gösterilemiyor.</div>
	</div>
	</div>
	
@endif