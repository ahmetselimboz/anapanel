@extends($theme_path . '.frontend_layout')

@section('meta')
    <title>{{ $settings['title'] }}</title>
    <meta name="description" content="{{ $settings['description'] }}">
    <meta name="keywords" content="{{ $magicbox['keywords'] }}">

    <meta property="og:title" content="{{ $settings['title'] }}"/>
    <meta property="og:description" content="{{ $settings['description'] }}"/>
    <meta property="og:image" content="{{ asset('uploads/' . $settings['logo']) }}"/>
    <meta property="og:url" content="{{ 'https://' . $_SERVER['HTTP_HOST'] }}"/>
    <meta property="og:type" content="website"/>

    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:site" content="@if (isset($magicbox['tw_name'])) {{ $magicbox['tw_name'] }} @endif"/>
    <meta name="twitter:title" content="{{ $settings['title'] }}"/>
    <meta name="twitter:description" content="{{ $settings['description'] }}"/>
    <meta name="twitter:image" content="{{ asset('uploads/' . $settings['logo']) }}"/>

    <script type="application/ld+json">{"@context": "https://schema.org/", "@type": "WebPage","@id": "#WebPage", "url": "{{ 'https://'.$_SERVER['HTTP_HOST'] }}", "name": "{{ $settings['title'] }}"}</script>
@endsection

@section('custom_css')
@endsection

<style>

    #loading-screen {
		position: fixed;
		width: 100vw;
		height: 100vh;
		background: #FFFFFF;
		z-index: 9999;
		font-size: 20px;
    }

	#loading-screen a {
		float: left;
		width: 100%;
		height: auto;
		overflow: hidden;
		margin: 200px 0px 20px 0px;
		padding: 0px 0px 0px 0px;
		border: 0px;
		text-align:center;
	}

	#loading-screen a img {
		float: none;
		width: auto;
		max-width: 90%;
		height: auto;
		overflow: hidden;
		margin: 0px auto 0px;
		padding: 0px 0px 0px 0px;
		border: 0px;
	}

	#loading-screen p {
		float: left;
		width: 100%;
		height: auto;
		overflow: hidden;
		margin: 0px 0px 0px 0px;
		padding: 0px 0px 0px 0px;
		border: 0px;
		text-align:center;
	}

    .dot {
		animation: blink 1s infinite;
    }

    .dot:nth-child(2) { animation-delay: 0.2s; }
    .dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes blink {
      0%, 100% { opacity: 0; }
      50% { opacity: 1; }
    }
	
</style>

<div id="loading-screen">

	<a href="{{ route('frontend.index') }}" title="{{ $magicbox["title"] }}">
	<img src="{{ asset('uploads/'.$settings['logo']) }}" alt="{{ $magicbox["title"] }}"/>
	</a>
	
	<p>
	Yükleniyor<span class="dot">.</span><span class="dot">.</span><span class="dot">.</span>
	</p>
	
</div>

<div id="habercontent" style="display:none;">
	
	@section('content')

    @php
        $sortabeJson = \Illuminate\Support\Facades\Storage::disk('public')->json('main/sortable_list.json');
    @endphp


    @if(!blank($sortabeJson))
        @foreach($sortabeJson as $block)

            @if($block["type"]=='main')
                @if($block["design"]!=NULL)

                    @if ($block["file"] == 'block_ana_manset')
                        @include($theme_path.'.main.block_ana_manset', ['design' => $block["design"]])
                        @include($theme_path.'.main.block_altili_manset', ['design' => $block["design"]]) <!-- buraya ekleme yapıldı (altılı manşet) -->
                        @continue
                    @endif

                    @if(\Illuminate\Support\Facades\View::exists($theme_path.'.main.block_global'))
                        @include($theme_path.'.main.block_global', ['design' => $block["design"],'block_id' => $block["id"],'block_category_id' => $block["category"],'limit'=>$block["limit"]])
                    @else
                        <div class="container">
                            <div class="row">
                                <div class="alert alert-warning"> Tasarım seçili ancak {{ $block["design"] }} dosyası bulunamadı.</div>
                            </div>
                        </div>
                    @endif

                @else

                    @if(\Illuminate\Support\Facades\View::exists($theme_path.'.main.'.$block["file"]))
                        @include($theme_path.'.main.'.$block["file"])

                        {{-- @include($theme_path.'.main.'.$block["file"], ['design' => $block["design"],'block_id' => $block["id"],'block_category_id' => $block["category"] ]) --}}

                    @else
                        <div class="container">
                            <div class="row">
                                <div class="alert alert-warning"> {{ $block["file"] }} dosyası bulunamadı.</div>
                            </div>
                        </div>
                    @endif

                @endif

            @endif

            @if($block["type"]=='ads')
                @if($block["ads"]!=NULL || $block["ads"]!=0)
                    @include($theme_path.'.main.'.$block["file"], [ 'ads_id' => $block["ads"] ])
                @else
                    <div class="container d-none">
                        <div class="row">
                            <div class="alert alert-warning"> {{ $block["title"] }} için hangi reklamın geleceği belirlenmemiş.</div>
                        </div>
                    </div>
                @endif
            @endif

            @if($block["type"]=='menu')
                @if($block["menu"]!=NULL || $block["menu"]!=0)
                    @include($theme_path.'.main.'.$block["file"], [ 'menu_id' => $block["menu"], 'design' => $block["design"], ])
                @else

                    @if ($block['file'] == "with_hit_news")
                        @include($theme_path.'.main.'.$block["file"], [ 'menu_id' => $block["menu"]])
                    @else
                        <div class="container">
                            <div class="row">
                                <div class="alert alert-warning"> {{ $block["title"] }} için hangi menü geleceği belirlenmemiş.</div>
                            </div>
                        </div>
                    @endif

                @endif
            @endif

        @endforeach
    
	@else
        <div class="container">
            <div class="row my-3">
                <div class="alert alert-warning">
                    Anasayfa Json Dosyası Bulunamadı / Yapılandırılamadı !
                    <br> Lütfen Sıralama Yönetimi > Anasayfa Sıralama bölümünden günceleme yapın.
                </div>
            </div>
        </div>
    @endif

</div>

<script>
window.addEventListener('load', function () {
document.getElementById('loading-screen').style.display = 'none';
document.getElementById('habercontent').style.display = 'block';
});
</script>

<style>
	
	.sondakikaok {
		padding: 15px 0px 0px 0px !important;
	}

</style>

@endsection