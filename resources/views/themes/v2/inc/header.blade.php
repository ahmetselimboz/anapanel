@if(auth()->check() and (auth()->user()->status==1 || auth()->user()->status==2) )
    <div class="bg-dark text-white">
        <div class="container">
            <div class="row">
                <ul class="list-group list-group-horizontal list-unstyled justify-content-center">
                    @if($routename=="post")
                        <li class="mx-3 my-1"><a href="{{ route('post.edit', $post->id) }}" style="color: white;">DÜZENLE</a></li>
                    @elseif($routename=="page")
                        <li class="mx-3 my-1"><a href="{{ route('page.edit', $page->id) }}" style="color: white;">DÜZENLE</a></li>
                    @endif

                    <li class="mx-3 my-1"><a href="{{ route('secure.index') }}" style="color: white;">YÖNETİM PANELİ</a></li>
                    <li class="mx-3 my-1"><a href="{{ route('userlogout') }}" style="color: white;">GÜVENLİ ÇIKIŞ</a></li>
                </ul>
            </div>
        </div>
    </div>
@endif

@if($magicbox["apiservicestatus"]==0)
    @if($magicbox["currency_status"]==0)

        @if(isset($magicbox["parapiyasacolor"]))
            <style>
                .bgcolorcustom {
                    background-color: {{ $magicbox["parapiyasacolor"] }}   !important;
                }

                .currency-block:after {
                    border-right: 2px solid {{ $magicbox["parapiyasacolor"] }}   !important;
                }
            </style>
        @endif

        @if(isset($magicbox["parapiyasacolortext"]))
            <style>
                .currency-title {
                    color: {{ $magicbox["parapiyasacolortext"] }}   !important;
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

        <div class="navbar py-0 bg-navbar-black bgcolorcustom">
            <div class="container text-white">
                <div class="row w-100">
                    <div class="col-12  position-relative">
                        <div class="currency-slider overflow-hidden d-flex justify-content-start flex-nowrap"
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
                        {{--                        <div class="light-pagination position-absolute">--}}
                        {{--                            <div class="light-prev" id="currencyPrev"></div>--}}
                        {{--                            <div class="light-next" id="currencyNext"></div>--}}
                        {{--                        </div>--}}
                    </div>
                </div>
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

@if($routename!="frontend.index")
    @if(\Illuminate\Support\Facades\Storage::disk('public')->exists('main/sortable_list.json'))
        @foreach(\Illuminate\Support\Facades\Storage::disk('public')->json('main/sortable_list.json') as $block)
            @if($block["type"]=="menu" and $block["design"]==0)
                @include($theme_path.'.main.block_main_menu', [ 'menu_id' => $block["menu"], 'design' => 0 ])
                @break
            @endif
        @endforeach
    @endif
@endif