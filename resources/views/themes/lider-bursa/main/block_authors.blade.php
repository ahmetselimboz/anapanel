<div class="container">


@if($magicbox["apiservicestatus"]==0)
    @if($magicbox["currency_status"]==0)

        @if(isset($magicbox["parapiyasacolor"]))
            <style>
                .bgcolorcustom {
                    background-color: "{{ $magicbox["parapiyasacolor"] }}"   !important;
                }

                .currency-block:after {
                    border-right: 2px solid "{{ $magicbox["parapiyasacolor"] }}"   !important;
                }
            </style>
        @endif

        @if(isset($magicbox["parapiyasacolortext"]))
            <style>
                .currency-title {
                    color: "{{ $magicbox["parapiyasacolortext"] }}"   !important;
                }
            </style>
        @endif

        @if(\Illuminate\Support\Facades\Storage::disk('public')->exists('bist.json'))
            @php $bist = \Illuminate\Support\Facades\Storage::disk('public')->json('bist.json');@endphp
        @endif

        @if(\Illuminate\Support\Facades\Storage::disk('public')->exists('currency.json'))
            @php $currency = \Illuminate\Support\Facades\Storage::disk('public')->json('currency.json');@endphp
        @endif

        @if(\Illuminate\Support\Facades\Storage::disk('public')->exists('gold.json'))
            @php $gold = \Illuminate\Support\Facades\Storage::disk('public')->json('gold.json');@endphp
        @endif

        @if(\Illuminate\Support\Facades\Storage::disk('public')->exists('coin.json'))
            @php $coin = \Illuminate\Support\Facades\Storage::disk('public')->json('coin.json');@endphp
        @endif
          <div class="row mb-4">
                    <div class="col-12  position-relative">
                        <div class="currency-slider overflow-hidden d-flex justify-content-start flex-nowrap  bgcolorcustom"
                             id="currencySlider">


                            @if(isset($bist["result"]) and count($bist["result"])>0)
                                <div class="currency-block py-2 d-flex flex-nowrap position-relative px-3 ">
                                    <div class="currency-title d-flex flex-nowrap">
                                        BIST 100 <span class="icon-currency-down"></span>
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $bist["result"][0]["current"] }}

                                    </div>
                                </div>
                            @endif



                            @if(isset($currency["result"]) and count($currency["result"])>0)
                                <div class="currency-block py-2 d-flex flex-nowrap position-relative px-3 ">
                                    <div class="currency-title d-flex flex-nowrap">
                                        DOLAR
                                        @if(strpos($currency["result"][0]["rate"], "-") !== false)
                                            <span class="icon-currency-down"></span>
                                        @else
                                            <span class="icon-currency-up"></span>
                                        @endif
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $currency["result"][0]["buying"] }}

                                    </div>
                                </div>
                                <div class="currency-block py-2 d-flex flex-nowrap position-relative px-3 ">
                                    <div class="currency-title d-flex flex-nowrap">
                                        EURO
                                        @if(strpos($currency["result"][1]["rate"], "-") !== false)
                                            <span class="icon-currency-down"></span>
                                        @else
                                            <span class="icon-currency-up"></span>
                                        @endif
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $currency["result"][1]["buying"] }}

                                    </div>
                                </div>
                                <div class="currency-block py-2 d-flex flex-nowrap position-relative px-3 ">
                                    <div class="currency-title d-flex flex-nowrap">
                                        GBP
                                        @if(strpos($currency["result"][2]["rate"], "-") !== false)
                                            <span class="icon-currency-down"></span>
                                        @else
                                            <span class="icon-currency-up"></span>
                                        @endif
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $currency["result"][2]["buying"] }}

                                    </div>
                                </div>
                                <div class="currency-block py-2 d-flex flex-nowrap position-relative px-3 ">
                                    <div class="currency-title d-flex flex-nowrap">
                                        RUB
                                        @if(strpos($currency["result"][5]["rate"], "-") !== false)
                                            <span class="icon-currency-down"></span>
                                        @else
                                            <span class="icon-currency-up"></span>
                                        @endif
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $currency["result"][5]["buying"] }}

                                    </div>
                                </div>
                            @endif



                            @if(isset($gold["result"]) and count($gold["result"])>0)
                                <div class="currency-block py-2 d-flex flex-nowrap position-relative px-3 ">
                                    <div class="currency-title d-flex flex-nowrap">
                                        GRAM ALTIN
                                        @if(strpos($gold["result"][0]["rate"], "-") !== false)
                                            <span class="icon-currency-down"></span>
                                        @else
                                            <span class="icon-currency-up"></span>
                                        @endif
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $gold["result"][0]["buying"] }}

                                    </div>
                                </div>
                                <div class="currency-block py-2 d-flex flex-nowrap position-relative px-3 ">
                                    <div class="currency-title d-flex flex-nowrap">
                                        GÜMÜŞ
                                        @if(strpos($gold["result"][20]["rate"], "-") !== false)
                                            <span class="icon-currency-down"></span>
                                        @else
                                            <span class="icon-currency-up"></span>
                                        @endif
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $gold["result"][20]["buying"] }}

                                    </div>
                                </div>
                            @endif



                            @if(isset($coin["result"]))
                                <div class="currency-block py-2 d-flex flex-nowrap position-relative px-3 ">
                                    <div class="currency-title d-flex flex-nowrap">
                                        BTC
                                        @if(strpos($coin["result"][0]["changeDay"], "-") !== false)
                                            <span class="icon-currency-down"></span>
                                        @else
                                            <span class="icon-currency-up"></span>
                                        @endif
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $coin["result"][0]["pricestr"] }}

                                    </div>
                                </div>
                            @endif


                        </div>

                    </div>
                </div>
        <div class="navbar py-0 bg-navbar-black bgcolorcustom">
            <div class="container text-white">

            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#currencySlider').slick({
                    dots: false,
                    infinite: true,
                    speed: 300,
                    variableWidth: false,
                    nextArrow: '#currencyNext',
                    prevArrow: '#currencyPrev',
                    centerMode: false,
                    swipeToSlide: false,
                    slidesToShow: 6,   // Number of slides visible
                    slidesToScroll: 1, // Number of slides to scroll
                    autoplay: true,    // Enables auto sliding
                    autoplaySpeed: 2000, // Delay between slides (in ms)
                    responsive: [
                        {
                            breakpoint: 1000,
                            settings: {
                                slidesToShow: 3
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 320,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                });
            })
        </script>
    @else
        <div class="alert alert-warning d-none"> Para Piyasası modülü kapalı</div>
    @endif
@endif
</div>


@if ($magicbox['author_status'] == 0)

    @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/authors.json'))
        @php $authors = \Illuminate\Support\Facades\Storage::disk('public')->json('main/authors.json'); @endphp

        <div class="container">
            <div class="row">

                <div class="col-12">
                    <div class="news-headline-block justify-content-between p-3" style="background:#e19b06;">

                        <h2 class="text-white">Yazarlar</h2>

                        <div class="yazarok">
                            <div class="ok-prev" id="authorPrev"></div>
                            <div class="ok-next" id="authorNext"></div>
                            <a href="{{ route('authors') }}" class="all-button text-white">Tümü</a>
                        </div>

                    </div>
                </div>

                <div class="col-12 mb-4">
                    <div class="rounded-1 shadow-sm p-3" style="overflow:hidden;">
                        <div id="authors" class="yazarlar">
                            @foreach ($authors as $author)
                            @php
                                $article = $author['latest_article'];
                               if (blank($article)) {
                                    continue;
                                }
                            @endphp
                                <div class="firma1">
                                    <div class="firma1kapla">
                                        <a href="{{ route('article', ['slug' => $article['slug'], 'id' => $article['id']]) }}" title="{{ $article['title'] }}">
                                            <img src="{{ route('resizeImage', ['i_url' => $author['avatar'], 'w' => 100, 'h' => 100]) }}"
                                                alt="{{ $author['name'] }}" />
                                        </a>
                                        <p>
                                            <span>
                                                <b><a href="{{ route('article', ['slug' => $article['slug'], 'id' => $article['id']]) }}"
                                                        title="{{ $article['title'] }}">{{ $author['name'] }}</a></b>
                                                <i>
                                                    <a href="{{ route('article', ['slug' => $article['slug'], 'id' => $article['id']]) }}"
                                                        title="{{ $article['title'] }}">{{ $article['title'] }}</a>
                                                </i>
                                            </span>
                                        </p>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @else
        <div class="container d-none">
            <div class="row">
                <div class="alert alert-warning"> Yazarlar Bulunamadı </div>
            </div>
        </div>

    @endif
@else
    <div class="container d-none">
        <div class="row">
            <div class="alert alert-warning"> Yazarlar modülü kapalı </div>
        </div>
    </div>

@endif


<div class="container">
    @php
        $social_media_link1 = isset($magicbox['social_media_link1']) ? $magicbox['social_media_link1'] : null;
        $social_media_link2 = isset($magicbox['social_media_link2']) ? $magicbox['social_media_link2'] : null;

    @endphp
    @if (!blank($social_media_link1)||!blank($social_media_link2))
    <div class="row">
        <div class="col-12">
            <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                <h2 class="text-black">Youtube Öne Çıkanlar</h2>
                <div class="headline-block-indicator">
                    <div class="indicator-ball" style="background-color:#EC0000;"></div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="row mb-4">
        @if (!blank($social_media_link1))
            <div class="col-12 col-md-6">
                <div class="pt-2 pb-4 px-1 border-bottom border-info overflow-hidden video-link">
                    {!!$social_media_link1!!}
                </div>
            </div>
        @endif

        @if (!blank($social_media_link2))
            <div class="col-12 col-md-6">
                <div class="pt-2 pb-4 px-1 border-bottom border-warning overflow-hidden video-link">
                    {!!$social_media_link2!!}
                </div>
            </div>
        @endif





    </div>
</div>


<div class="container">
    <div class="row">
        	<div class="tmz"></div>

	<div class="sondakika">
		<div class="container">
			@if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/sondakika_manset.json'))
				<div class="sondakikaok">
					<div class="ok-prev" id="lastminutePrev"></div>
					<div class="ok-next" id="lastminuteNext"></div>
				</div>
				<div id="lastminuteSlider">
					@php $son_dakikalar = \Illuminate\Support\Facades\Storage::disk('public')->json('main/sondakika_manset.json'); @endphp
					@foreach ($son_dakikalar as $son_dakika)
						<a href="{{ route('post', ['categoryslug' => $son_dakika['categoryslug'], 'slug' => $son_dakika['slug'], 'id' => $son_dakika['id']]) }}" title="{{ $son_dakika['title'] }}">
							<span>{{ date('H:i', strtotime($son_dakika['created_at'])) }}</span>
							{{ \Illuminate\Support\Str::limit(html_entity_decode($son_dakika['title']), 80) }}
						</a>
					@endforeach
				</div>
			@else
				<div class="alert alert-warning d-none">
					Son Dakika Bandı Bulunamadı
				</div>
			@endif
		</div>
	</div>
    </div>
</div>



<div class="container">
    <div class="row mt-0 mb-4">
        @php
            $weather_link = isset($magicbox['weather_link']) ? $magicbox['weather_link'] : null;
            $pharmacy_link = isset($magicbox['pharmacy_link']) ? $magicbox['pharmacy_link'] : null;
            $deceased_link = isset($magicbox['deceased_link']) ? $magicbox['deceased_link'] : null;
            $traffic_link = isset($magicbox['traffic_link']) ? $magicbox['traffic_link'] : null;
        @endphp
        @if (!blank($weather_link))
            <div class="col-6 col-md-3 mb-2 mb-md-0 px-1 px-md-2">
                <a href="{{ $weather_link }}" class="btn btn-info w-100 text-white" target="_blank">

                    <h6 class="d-block d-md-none m-0" style="font-size: 12px">
                        <i class="fa fa-cloud text-white"></i>
                        Hava Durumu
                    </h6>
                    <h6 class="d-none d-md-block m-0">
                        <i class="fa fa-cloud text-white"></i>
                        Hava Durumu
                    </h6>
                </a>
            </div>
        @endif

        @if (!blank($pharmacy_link))
            <div class="col-6 col-md-3  mb-2 mb-md-0 px-1 px-md-2">
                <a href="{{ $pharmacy_link }}" class="btn btn-danger w-100" target="_blank">
                    <h6 class="d-block d-md-none m-0" style="font-size: 12px">
                        <i class="fa fa-heartbeat"></i>
                        Nöbetçi Eczaneler
                    </h6>
                    <h6 class="d-none d-md-block m-0">
                        <i class="fa fa-heartbeat"></i>
                        Nöbetçi Eczaneler
                    </h6>
                </a>
            </div>
        @endif

        @if (!blank($deceased_link))
            <div class="col-6 col-md-3  mb-2 mb-md-0 px-1 px-md-2">
                <a href="{{ $deceased_link }}" class="btn btn-dark w-100" target="_blank">
                    <h6 class="d-block d-md-none m-0" style="font-size: 12px">
                        <i class="fa fa-frown-o"></i>
                        Vefat Edenler
                    </h6>
                    <h6 class="d-none d-md-block m-0">
                        <i class="fa fa-frown-o"></i>
                        Vefat Edenler
                    </h6>
                </a>
            </div>
        @endif

        @if (!blank($traffic_link))
            <div class="col-6 col-md-3  mb-2 mb-md-0 px-1 px-md-2">
                <a href="{{ $traffic_link }}" class="btn btn-success w-100" target="_blank">
                    <h6 class="d-block d-md-none m-0" style="font-size: 12px">
                        <i class="fa fa-car"></i>
                        Trafik Yol Durumu
                    </h6>
                    <h6 class="d-none d-md-block m-0">
                        <i class="fa fa-car"></i>
                        Trafik Yol Durumu
                    </h6>
                </a>
            </div>
        @endif
    </div>
</div>