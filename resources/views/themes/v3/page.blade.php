@extends('themes.'.$theme.'.frontend_layout')

@section('meta')
    <title>{{ $page->title }}</title>
    <meta name="description" content="{!! \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($page->detail))) !!}">

    <meta property="og:title" content="{{ $page->title }}" />
    <meta property="og:description" content="{!! \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($page->detail))) !!}" />
    <meta property="og:image" content="{{ imageCheck($page->images) }}" />
    <meta property="og:url" content="{{ route('page', ['slug'=>$page->slug]) }}" />
    <meta property="og:type" content="news" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@if(isset($magicbox["tw_name"])) {{$magicbox["tw_name"]}} @endif" />
    <meta name="twitter:title" content="{{ $page->title }}" />
    <meta name="twitter:description" content="{!! \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($page->detail))) !!}" />
    <meta name="twitter:image" content="{{ imageCheck($page->images) }}" />

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsArticle",
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ route('page', ['slug'=>$page->slug]) }}"
      },
      "headline": "{{ $page->title }}",
      "description": "{!! \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($page->detail))) !!}",
      "image": "{{ imageCheck($page->images) }}",
      "author": {
        "@type": "Organization",
        "name": "@if(isset($extra->author)) {{ $extra->author }} @else Yazar @endif",
      },
      "publisher": {
        "@type": "Organization",
        "name": "{{ $settings['title'] }}",
        "logo": {
          "@type": "ImageObject",
          "url": "{{ imageCheck($settings['logo']) }}"
        }
      },
      "datePublished": "{{ date('Y-m-d', strtotime($page->created_at)) }}",
      "dateModified": "{{ date('Y-m-d', strtotime($page->updated_at)) }}"
    }
    </script>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Sayfalar",
        "item": ""
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "{{ $page->title }}",
        "item": "{{ route('page', ['slug'=>$page->slug]) }}"
      }]
    }
    </script>
@endsection

@section('content')

    <div class="container">
	<div class="row">
	
		<div class="col-12">
		<div class="category-headline-block justify-content-between mb-4"> <!--Block Başlık-->
		<h2 class="text-black">{{ $page->title }}</h2>
		<div class="headline-block-indicator"><div class="indicator-ball" style="background-color:#975E64;"></div></div>
		</div>
		</div>
		
		<div class="col-12 col-md-8">
		<div class="col-12 bg-gray rounded-1 mb-4">
		<div class="container ">
		<div class="row py-1 justify-content-center">
		<div class="col-12 col-md-6 py-2 pe-md-2">
		{!! $page->detail !!}
		</div>
		</div>
		</div>
		</div>
		</div>
            
		<div class="col-12 col-md-4">
		<div class="spotlar">

				@php $counter = 1; @endphp

				@if(count($hit_popups)>0)
				@foreach($hit_popups as $hit_popup)
				@if($counter<=2)
					
				<div class="spot spotduz spotduztek spotduz-{{ $counter }}">
				<a href="{{ route('post', ['categoryslug'=>$hit_popup->category->slug,'slug'=>$hit_popup->slug,'id'=>$hit_popup->id]) }}" title="{{ $hit_popup['title'] }}">
				<b>{{ categoryCheck($hit_popup['category_id'])->title }}</b>
				<div class="spot-resim"><img src="{{ route('resizeImage', ["i_url" => imageCheck($hit_popup["images"]), "w" => 379, "h" => 210]) }}" alt="{{html_entity_decode($hit_popup["title"]) }}" alt="{{ html_entity_decode($hit_popup["title"]) }}" /></div>
				<p><span>{{html_entity_decode($hit_popup["title"]) }}</span></p>
				</a>
				</div>

				@php $counter++; @endphp
				
				@endif
				@endforeach
				@endif
				
		</div>
		</div>
				
	</div>
	</div>

@endsection