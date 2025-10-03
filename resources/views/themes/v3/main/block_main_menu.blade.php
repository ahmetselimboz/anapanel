@if (isset($design))
    @if ($design == 'default' or $design == 0)


        @if (isset($magicbox['thintopbar']))
            @if ($magicbox['thintopbar'] == 1)
                <style>
                    .top-bar-menu {
                        opacity: 1;
                        display: flex;
                        align-items: center;
                        height: auto;
                    }

                    .top-bar-menu:hover {
                        opacity: 0.8;
                    }

                    @media (max-width: 768px) {
                        .top-bar-menu {
                            height: 16px;
                        }
                    }

                    .top-bar-ul {
                        list-style: none;
                        font-size: 14px;
                        font-weight: 600
                    }

                    @media (max-width: 768px) {
                        .top-bar-ul {
                            font-size: 9px;
                        }
                    }

                    .blinking-circle {

                        font-size: 16px;
                        animation: blink 1s infinite;
                    }

                    @keyframes blink {

                        0%,
                        100% {
                            opacity: 1;
                        }

                        50% {
                            opacity: 0.5;
                        }
                    }

                    .menu {
                        list-style: none;
                        padding-left: 0;
                        margin: 0;
                    }

                    .menu-item {
                        margin-bottom: 8px;
                    }

                    .menu-link {
                        display: block;
                        padding: 8px 12px;

                        color: #333;
                        text-decoration: none;
                        transition: background-color 0.3s ease;
                        cursor: pointer;
                    }

                    .menu-link:hover {
                        background-color: #eee;
                    }


                    .submenu {
                        display: none;
                        /* Başta gizli */
                        list-style: none;
                        padding-left: 15px;
                        margin-top: 5px !important;
                    }

                    .submenu-item {
                        margin-bottom: 5px;
                    }

                    .submenu-link {
                        display: block;
                        padding: 6px 10px !important;
                        color: #555;
                        text-decoration: none;
                        transition: background-color 0.2s ease;
                        display: flex;

                        align-items: center;
                    }

                    .submenu-link:hover {
                        background-color: #eee;
                    }

                    .menu-empty {
                        color: #999;
                        font-style: italic;
                    }

                    .has-children {
                        width: 50%;
                    }

                    .click-menu {

                        cursor: pointer;

                    }
                </style>

                <div style="background-color:#0A74B7;">
                    <div class="container">
                        <div class="row flex-row justify-content-between">
                            <div style="width:fit-content" class="px-md-2 p-0 d-flex align-items-center">
                                <ul class="d-flex my-2 p-0" style="list-style: none;font-size:14px;">
                                    <li>
                                        <a href="{{ $magicbox['fb'] }}" target="_blank"
                                            class="text-white px-2 border-end"><i class="fa fa-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a href="{{ $magicbox['tw'] }}" target="_blank"
                                            class="text-white px-2 border-end"><i class="fa fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a href="{{ $magicbox['in'] }}" target="_blank"
                                            class="text-white px-2 border-end"><i class="fa fa-instagram"></i></a>
                                    </li>
                                    <li>
                                        <a href="{{ $magicbox['yt'] }}" target="_blank"
                                            class="text-white px-2 border-end"><i class="fa fa-youtube"></i></a>
                                    </li>
                                    <li>
                                        <a href="{{ $magicbox['ln'] }}" target="_blank" class="text-white px-2"><i
                                                class="fa fa-linkedin"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <div style="width:fit-content " class="px-md-2 p-0 d-flex align-items-center">
                                <ul class="d-flex m-0 p-0 top-bar-ul">
                                    <li class="d-flex align-items-center">
                                        <a href="{{ route('video_galleries') }}" target="_blank"
                                            class="text-white text-uppercase px-2 top-bar-menu border-end">
                                            VİDEO GALERİ
                                        </a>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <a href="{{ route('authors') }}" target="_blank"
                                            class="text-white text-uppercase px-2 top-bar-menu border-end">
                                            YAZARLAR
                                        </a>
                                    </li>
                                    @if (isset($magicbox['live_stream_name']) && isset($magicbox['live_stream_link']))
                                        <li class="d-flex align-items-center">
                                            <a href="{{ $magicbox['live_stream_link'] }} " target="_blank"
                                                class="text-white text-uppercase px-2 top-bar-menu">
                                                <i class="fa fa-play-circle text-danger blinking-circle me-1"></i>
                                                {{ $magicbox['live_stream_name'] }}
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            @endif
        @endif

        <div class="header" id="site">
            <div class="container">

                <div class="logo">
                    <a href="{{ route('frontend.index') }}" title="{{ $magicbox['title'] }}"><img
                            src="{{ asset('uploads/' . $settings['logo']) }}" alt="{{ $magicbox['title'] }}" /></a>
                </div>

                <div class="logosag">

                    <div class="ustmenu mobyok">
                        <ul>
                            @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                                @php $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu'.$menu_id.'.json'); @endphp
                                @foreach ($menus as $mkey => $menu)
                                    <li><a href="{{ $menu['url'] }}"
                                            title="{{ $menu['name'] }}">{{ $menu['name'] }}</a></li>
                                    @if ($mkey == 12)
                                        @break
                                    @endif
                                @endforeach
                            @else
                                Menü Bulunamadı
                            @endif
                        </ul>
                    </div>

                    <div class="eklink">

                        <button id="menuBtnu" onclick="menuac()"><i class="fa fa-list"></i></button>

                        <button id="searching-btn"><i class="fa fa-search"></i></button>

                        @if (auth()->check())
                            <a href="{{ route('userprofile') }}" title="Profilim"><i class="fa fa-user"></i></a>
                        @else
                            <button id="signIn"><i class="fa fa-user"></i></button>
                        @endif

                    </div>

                </div>

            </div>
        </div>

        <div class="tmz"></div>

        <div class="mobilustmenu">
            <ul>
                @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                    @php $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu'.$menu_id.'.json'); @endphp
                    @foreach ($menus as $mkey => $menu)
                        <li><a href="{{ $menu['url'] }}" title="{{ $menu['name'] }}">{{ $menu['name'] }}</a></li>
                        @if ($mkey == 14)
                            @break
                        @endif
                    @endforeach
                @else
                    Menü Bulunamadı
                @endif
            </ul>
        </div>

        <div class="tmz"></div>

        <div class="sondakika d-flex align-items-center">
            <div class="container">

                <div class="sondakikalar mt-0">
                    <div class="marquee-content">

                        @php $ssay = 0; @endphp
                        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/sondakika_manset.json'))
                            @php $son_dakikalar = \Illuminate\Support\Facades\Storage::disk('public')->json('main/sondakika_manset.json'); @endphp
                            @foreach ($son_dakikalar as $mkey => $son_dakika)
                                <a href="{{ route('post', ['categoryslug' => $son_dakika['categoryslug'], 'slug' => $son_dakika['slug'], 'id' => $son_dakika['id']]) }}"
                                    title="{{ $son_dakika['title'] }}" class="d-md-flex">
                                    <span
                                        class="d-md-block d-none">{{ date('H:i', strtotime($son_dakika['created_at'])) }}</span>
                                    <p class="d-md-block d-none m-0">
                                        {{ \Illuminate\Support\Str::limit(html_entity_decode($son_dakika['title']), 70) }}
                                    </p>
                                    <p class="d-md-none d-block m-0">
                                        {{ \Illuminate\Support\Str::limit(html_entity_decode($son_dakika['title']), 40) }}
                                    </p>
                                </a>
                                @php $ssay++; @endphp
                                @if ($mkey >= 3)
                                    @break
                                @endif
                            @endforeach



                    </div>
                </div>
            @else
                <div class="alert alert-warning  d-none"> Son Dakika Bandı Bulunamadı</div>

    @endif

    </div>

    </div>

    <div class="tmz"></div>

    @if ($magicbox['apiservicestatus'] == 0)
        @if ($magicbox['currency_status'] == 0)

            <div class="doviz">
                <div class="container">

                    @if (isset($magicbox['parapiyasacolor']))
                        <style>
                            .bgcolorcustom {
                                background-color: {{ $magicbox['parapiyasacolor'] }} !important;
                            }

                            .currency-block:after {
                                border-right: 2px solid {{ $magicbox['parapiyasacolor'] }} !important;
                            }
                        </style>
                    @endif

                    @if (isset($magicbox['parapiyasacolortext']))
                        <style>
                            .currency-title {
                                color: {{ $magicbox['parapiyasacolortext'] }} !important;
                            }
                        </style>
                    @endif

                    @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('bist.json'))
                        @php $bist = \Illuminate\Support\Facades\Storage::disk('public')->json('bist.json');@endphp
                    @endif

                    @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('currency.json'))
                        @php $currency = \Illuminate\Support\Facades\Storage::disk('public')->json('currency.json');@endphp
                    @endif

                    @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('gold.json'))
                        @php $gold = \Illuminate\Support\Facades\Storage::disk('public')->json('gold.json');@endphp
                    @endif

                    @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('coin.json'))
                        @php $coin = \Illuminate\Support\Facades\Storage::disk('public')->json('coin.json');@endphp
                    @endif

                    <div class="dovizalan">

                        <div class="currency-slider overflow-hidden d-flex justify-content-start flex-nowrap"
                            id="currencySlider">

                            @if (isset($bist['result']) and count($bist['result']) > 0)
                                <div class="currency-block">
                                    <div class="currency-title d-flex flex-nowrap">
                                        BIST 100 <span class="icon-currency-down"></span>
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $bist['result'][0]['current'] }}
                                    </div>
                                </div>
                            @endif

                            @if (isset($currency['result']) and count($currency['result']) > 0)

                                <div class="currency-block">
                                    <div class="currency-title d-flex flex-nowrap">
                                        DOLAR
                                        @if (strpos($currency['result'][0]['rate'], '-') !== false)
                                            <span class="icon-currency-down"></span>
                                        @else
                                            <span class="icon-currency-up"></span>
                                        @endif
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $currency['result'][0]['buying'] }}
                                    </div>
                                </div>

                                <div class="currency-block">
                                    <div class="currency-title d-flex flex-nowrap">
                                        EURO
                                        @if (strpos($currency['result'][1]['rate'], '-') !== false)
                                            <span class="icon-currency-down"></span>
                                        @else
                                            <span class="icon-currency-up"></span>
                                        @endif
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $currency['result'][1]['buying'] }}
                                    </div>
                                </div>

                                <div class="currency-block">
                                    <div class="currency-title d-flex flex-nowrap">
                                        GBP
                                        @if (strpos($currency['result'][2]['rate'], '-') !== false)
                                            <span class="icon-currency-down"></span>
                                        @else
                                            <span class="icon-currency-up"></span>
                                        @endif
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $currency['result'][2]['buying'] }}
                                    </div>
                                </div>

                                <div class="currency-block">
                                    <div class="currency-title d-flex flex-nowrap">
                                        RUB
                                        @if (strpos($currency['result'][5]['rate'], '-') !== false)
                                            <span class="icon-currency-down"></span>
                                        @else
                                            <span class="icon-currency-up"></span>
                                        @endif
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $currency['result'][5]['buying'] }}
                                    </div>
                                </div>

                            @endif

                            @if (isset($gold['result']) and count($gold['result']) > 0)

                                <div class="currency-block">
                                    <div class="currency-title d-flex flex-nowrap">
                                        GRAM ALTIN
                                        @if (strpos($gold['result'][0]['rate'], '-') !== false)
                                            <span class="icon-currency-down"></span>
                                        @else
                                            <span class="icon-currency-up"></span>
                                        @endif
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $gold['result'][0]['buying'] }}
                                    </div>
                                </div>

                                <div class="currency-block">
                                    <div class="currency-title d-flex flex-nowrap">
                                        GÜMÜŞ
                                        @if (strpos($gold['result'][20]['rate'], '-') !== false)
                                            <span class="icon-currency-down"></span>
                                        @else
                                            <span class="icon-currency-up"></span>
                                        @endif
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $gold['result'][20]['buying'] }}
                                    </div>
                                </div>

                            @endif

                            @if (isset($coin['result']))
                                <div class="currency-block">
                                    <div class="currency-title d-flex flex-nowrap">
                                        BTC
                                        @if (strpos($coin['result'][0]['changeDay'], '-') !== false)
                                            <span class="icon-currency-down"></span>
                                        @else
                                            <span class="icon-currency-up"></span>
                                        @endif
                                    </div>
                                    <div class="currency-parameter">
                                        {{ $coin['result'][0]['pricestr'] }}
                                    </div>
                                </div>
                            @endif

                        </div>

                        {{-- <div class="light-pagination position-absolute"> --}}
                        {{-- <div class="light-prev" id="currencyPrev"></div> --}}
                        {{-- <div class="light-next" id="currencyNext"></div> --}}
                        {{-- </div> --}}

                    </div>

                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

                    <script>
                        $(document).ready(function() {
                            $('#currencySlider').slick({
                                dots: false,
                                infinite: true,
                                speed: 300,
                                variableWidth: false,
                                nextArrow: '#currencyNext',
                                prevArrow: '#currencyPrev',
                                centerMode: false,
                                swipeToSlide: false,
                                slidesToShow: 3, // Number of slides visible
                                slidesToScroll: 1, // Number of slides to scroll
                                autoplay: true, // Enables auto sliding
                                autoplaySpeed: 2000, // Delay between slides (in ms)
                                responsive: [{
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

        </div>
        </div>

    @endif

    <div class="tmz"></div>
    <div class="bos30"></div>
    <div class="tmz"></div>

    <div class="position-relative" id="burger-menu-contain">
        <div class="before-overlay"></div>
        <div class="big-menu rounded-1 pb-2 bg-white" id="burger-menu">
            <div class="big-menu-header p-2 pb-0">
                <div class="menu-close-btn text-end" id="menu-close"><i class="icon-close2 d-inline-block"></i>
                </div>
            </div>
            @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                @php $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu'.$menu_id.'.json'); @endphp @foreach ($menus as $mkey => $menu)
                    @if ($mkey > 8)
                        <div class="nav-item"><a class="nav-link externallink" href="{{ $menu['url'] }}"
                                title="{{ $menu['name'] }}">{{ $menu['name'] }}</a></div>
                    @endif
                @endforeach
            @else
                Menü Bulunamadı
            @endif
            <div class="nav-item"><a class="nav-link externallink" href="{{ route('authors') }}"
                    title="Yazarlar">Yazarlar</a></div>
            <div class="nav-item"><a class="nav-link externallink" href="{{ route('photo_galleries') }}"
                    title="Foto Galeri">Foto Galeri</a></div>
            <div class="nav-item"><a class="nav-link externallink" href="{{ route('video_galleries') }}"
                    title="Video Galeri">Video Galeri</a></div>
        </div>
    </div>

    <div class="tmz"></div>

    <div id="anamenu">

        <div class="mlogo">
            <a href="{{ route('frontend.index') }}" title="{{ $magicbox['title'] }}"><img
                    src="{{ asset('uploads/' . $settings['logo']) }}" alt="{{ $magicbox['title'] }}" /></a>
        </div>

        <div class="kapat"><a onclick="menukapat()" href="#">x</a></div>

        <div class="tmz"></div>

        <div class="sekmenu">
            <p><a href="{{ $magicbox['fb'] }}" style="background: #30487b;"><i class="fa fa-facebook"></i></a></p>
            <p><a href="{{ $magicbox['tw'] }}" style="background: #2ba9e1;"><i class="fa fa-twitter"></i></a></p>
            <p><a href="{{ $magicbox['in'] }}" style="background: #BD0082;"><i class="fa fa-instagram"></i></a></p>
            <p><a href="{{ $magicbox['yt'] }}" style="background: #f74c53;"><i class="fa fa-youtube"></i></a></p>
            <p><a href="{{ $magicbox['ln'] }}" style="background: #005f8d;"><i class="fa fa-linkedin"></i></a></p>
        </div>

        <div class="tmz"></div>

        <ul class="menu">
            @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                @php
                    $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu' . $menu_id . '.json');
                @endphp

                @foreach ($menus as $mkey => $menu)
                    <li class="menu-item">
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="{{ $menu['url'] }}"
                                class="menu-link {{ isset($menu['children']) ? 'has-children' : '' }}"
                                title="{{ $menu['name'] }}">
                                {{ $menu['name'] }}

                            </a>
                            @if (isset($menu['children']) && is_array($menu['children']) && count($menu['children']) > 0)
                                <i
                                    class="fa fa-chevron-down click-menu d-flex justify-content-end {{ isset($menu['children']) ? 'has-children' : '' }}"></i>
                            @endif
                        </div>


                        @if (isset($menu['children']) && is_array($menu['children']) && count($menu['children']) > 0)
                            <ul class="submenu">
                                @foreach ($menu['children'] as $child)
                                    <li class="submenu-item">
                                        <a href="{{ $child['url'] }}" class="submenu-link"
                                            title="{{ $child['name'] }}">
                                            <i class="fa fa-caret-right mx-4"></i>
                                            {{ $child['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>

                    @if ($mkey == 20)
                        @break
                    @endif
                @endforeach
            @else
                <li class="menu-empty">Menü Bulunamadı</li>
            @endif
        </ul>


        <div class="tmz"></div>

        <div class="ekmenu">
            <a href="/"><i class="fa fa-clock-o"></i>Son Dakika</a>
            <a href="{{ 'https://wa.me/' . $magicbox['phone'] }}"><i class="fa fa-whatsapp"></i>Haber Gönder</a>
            <a href="/video-galeriler"><i class="fa fa-camera"></i>Video</a>
            <a href="/yazarlar"><i class="fa fa-users"></i>Yazarlar</a>
            <a href="/sayfa/kunye"><i class="fa fa-suitcase"></i>Künye</a>
            <a href="/sayfa/iletisim"><i class="fa fa-envelope"></i>İletişim</a>
        </div>

    </div>

    <script type="text/javascript">
        function menuac() {
            document.getElementById('anamenu').style.display = "block";
        }

        function menukapat() {
            document.getElementById('anamenu').style.display = "none";
        }


        document.addEventListener('click', function(e) {
            const menu = document.getElementById('anamenu');
            const button = document.getElementById('menuBtnu');
            if (!menu.contains(e.target) && !button.contains(e.target)) {
                menukapat();
            }
        });

        document.getElementById('anamenu').addEventListener('click', function(e) {
            e.stopPropagation();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
                menukapat();
            }
        });
    </script>

    <div class="tmz"></div>
@elseif($design == 1)
    @php
        $trend_news = collect(\Illuminate\Support\Facades\Storage::disk('public')->json('main/hit_news.json'));
        $remaining_news = $trend_news->slice(15);
        $news_groups = $remaining_news->chunk(6)->take(4);
    @endphp

    <style>
        footer .nav-link:hover {
            color: #ffffff !important;
        }

        .new-footer-list-elem a:hover {
            color: #b6bbbf !important;
            text-decoration: underline;
        }
    </style>

    <div class="tmz"></div>



    <div class="tmz"></div>

    <footer class="new-footer">

        <div class="container">

            <div class="new-footer-top">

                <style>
                    .footer-head-container {
                        margin-bottom: 80px;
                        font-size: 16px;
                        width: fit-content;
                    }

                    .footer-head-tag {
                        background-color: #97979780;

                    }

                    .footer-head-tag:hover {
                        background-color: #a5a5a5cf;
                    }
                </style>


                <div class="container mx-0 px-md-4 footer-head-container">
                    <div class="d-flex flex-wrap gap-2" style="font-weight: 600;">
                        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                            @php $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu'.$menu_id.'.json'); @endphp
                            @foreach ($menus as $mkey => $menu)
                                <a href="{{ $menu['url'] }}" class="text-white px-2 py-1 mx-1 footer-head-tag"
                                    title="{{ $menu['name'] }}">{{ $menu['name'] }}</a>
                                @if ($mkey == 10)
                                    @break
                                @endif
                            @endforeach
                        @else
                            Menü Bulunamadı
                        @endif
                    </div>
                </div>


                <div class="row my-4">
                    <style>
                        .footer-body-logo {
                            border-right: 1px solid #4f4f4f;

                        }

                        @media (max-width: 768px) {
                            .footer-body-logo {
                                border-right: 0px solid #4f4f4f;
                                border-bottom: 1px solid #4f4f4f;
                                padding-bottom: 20px;
                                text-align: center;
                                display: flex;
                                flex-direction: column;
                                align-items: center
                            }
                        }
                    </style>
                    <div class="col-md-5 col-12 px-4">
                        <div class="px-md-4 footer-body-logo">
                            <div class="footer-logo mb-3">
                                <a href="{{ route('frontend.index') }}" title="{{ $magicbox['title'] }}">
                                    <img src="{{ asset('uploads/' . $settings['logo']) }}"
                                        alt="{{ $magicbox['title'] }}" class="img-fluid" />
                                </a>
                            </div>
                            <div class="footer-description text-white">
                                <p>{{ $settings['description'] }}</p>
                            </div>
                            <div class="d-flex justify-content-start  pt-lg-3 mt-3 mb-5">
                                <div class="px-1">
                                    <a href="{{ $magicbox['tw'] }}" class="btn social-rounded-button externallink"
                                        target="_blank">
                                        <div class="icon-social-x"></div>
                                    </a>
                                </div>
                                <div class="px-1">
                                    <a href="{{ $magicbox['fb'] }}" class="btn social-rounded-button externallink"
                                        target="_blank">
                                        <div class="icon-social-facebook"></div>
                                    </a>
                                </div>
                                <div class="px-1">
                                    <a href="{{ $magicbox['in'] }}" class="btn social-rounded-button externallink"
                                        target="_blank">
                                        <div class="icon-social-instagram"></div>
                                    </a>
                                </div>
                                <div class="px-1">
                                    <a href="{{ $magicbox['yt'] }}" class="btn social-rounded-button externallink"
                                        target="_blank">
                                        <div class="icon-social-youtube"></div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="px-md-4">
                            <div class="text-start text-white mb-2 mt-md-0 mt-5">
                                <h5>
                                    Kurumsal
                                    <hr style="width: 40%;" />
                                </h5>
                            </div>
                            <div>
                                <ul>
                                    <li class="mb-2">
                                        <a href="/sayfa/iletisim" title="Künye" class="footer-body-news"
                                            style="font-size:16px;">
                                            İletişim
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/sayfa/kunye" title="Künye" class="footer-body-news"
                                            style="font-size:16px;">
                                            Künye
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/sayfa/gizlilik-politikasi" title="Künye" class="footer-body-news"
                                            style="font-size:16px;">
                                            Gizlilik Politikası
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>


                    </div>

                    <div class="col-md-7 col-12 ">
                        <div class="row">
                            <div class="col-md-6 col-12 px-4 my-md-0 my-4">
                                <style>
                                    .footer-body-news {
                                        color: white;
                                        text-decoration: none;
                                        font-size: 14px;
                                    }

                                    .footer-body-news:hover {
                                        text-decoration: underline;
                                    }
                                </style>


                                <div class="text-start text-white mb-2">
                                    <h5>
                                        En Çok Okunan Haberler
                                        <hr style="width: 40%;" />
                                        </h4>
                                </div>
                                <div>
                                    <ul>
                                        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/sondakika_manset.json'))
                                            @php $son_dakikalar = \Illuminate\Support\Facades\Storage::disk('public')->json('main/sondakika_manset.json'); @endphp
                                            @foreach ($son_dakikalar as $mkey => $son_dakika)
                                                @if ($mkey > 3 && $mkey <= 11)
                                                    <li class="mb-2">
                                                        <a href="{{ route('post', ['categoryslug' => $son_dakika['categoryslug'], 'slug' => $son_dakika['slug'], 'id' => $son_dakika['id']]) }}"
                                                            title="{{ $son_dakika['title'] }}"
                                                            class="footer-body-news">
                                                            {{ $son_dakika['title'] }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="text-white"> Haberlere Erişilemedi</div>
                                        @endif
                                    </ul>
                                </div>





                            </div>
                            <div class="col-md-6 col-12 px-4">

                                <div class="text-start text-white mb-2 invisible d-md-block d-none">
                                    <h5>
                                        Haberler
                                        <hr style="width: 40%;" />
                                        </h4>
                                </div>
                                <div>
                                    <ul>
                                        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/sondakika_manset.json'))
                                            @php $son_dakikalar = \Illuminate\Support\Facades\Storage::disk('public')->json('main/sondakika_manset.json'); @endphp
                                            @foreach ($son_dakikalar as $mkey => $son_dakika)
                                                @if ($mkey > 11)
                                                    <li class="mb-2">
                                                        <a href="{{ route('post', ['categoryslug' => $son_dakika['categoryslug'], 'slug' => $son_dakika['slug'], 'id' => $son_dakika['id']]) }}"
                                                            title="{{ $son_dakika['title'] }}"
                                                            class="footer-body-news">
                                                            {{ $son_dakika['title'] }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="text-white"> Haberlere Erişilemedi</div>
                                        @endif
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="new-footer-bottom" style="background:#000;">
            <div class="container">
                <div class="new-footer-bottom-flex">

                    <div class="new-footer-copyright-text">Tüm Hakları Saklıdır - 2025 - İzinsiz İçerikler
                        Kullanılamaz.</div>

                    <p class="mb-0 new-footer-copyright-text" style="cursor:default;">Yazılım: <a
                            href="https://vmgmedya.com/" target="_blank" class=" text-white new-footer-link"
                            rel="dofollow">VMG MEDYA</a></p>

                </div>
            </div>
        </div>

        <div class="container" style="display: none !important">

            <div class="row mb-4">
                <div class="col-12 col-lg-4">
                    <div class="d-flex flex-lg-nowrap justify-content-center justify-content-lg-start py-4">
                        <div>
                            <a class="" href="{{ route('frontend.index') }}"
                                title="{{ $magicbox['title'] }}">
                                <img src="{{ asset('uploads/' . $settings['logo']) }}"
                                    alt="{{ $magicbox['title'] }}" class="brand-logo" width="102">
                            </a>
                        </div>
                        <div class="footer-logo-capital ps-3">
                            <div class="fw-bold footer-logo-capital-title text-dark">{{ $magicbox['title'] }}</div>
                            <div class="fw-medium footer-logo-capital-desc text-dark">
                                © {{ $magicbox['copyright'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-8 social-icons-new">
                    <div class="d-flex justify-content-center justify-content-lg-end mb-4 mb-lg-0 pt-lg-3 mt-3">
                        <div class="px-1">
                            <a href="{{ $magicbox['tw'] }}" class="btn social-rounded-button externallink"
                                target="_blank">
                                <div class="icon-social-x"></div>
                            </a>
                        </div>
                        <div class="px-1">
                            <a href="{{ $magicbox['fb'] }}" class="btn social-rounded-button externallink"
                                target="_blank">
                                <div class="icon-social-facebook"></div>
                            </a>
                        </div>
                        <div class="px-1">
                            <a href="{{ $magicbox['in'] }}" class="btn social-rounded-button externallink"
                                target="_blank">
                                <div class="icon-social-instagram"></div>
                            </a>
                        </div>
                        <div class="px-1">
                            <a href="{{ $magicbox['yt'] }}" class="btn social-rounded-button externallink"
                                target="_blank">
                                <div class="icon-social-youtube"></div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <hr class="border-gray m-0">
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-12">
                    <div class="d-lg-flex justify-content-lg-between footer-categories">
                        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                            @php
                                $menus = \Illuminate\Support\Facades\Storage::disk('public')->json(
                                    'menu' . $menu_id . '.json',
                                );
                                $collection = collect($menus);
                                $chunks = $collection->chunk(6);
                                $menus = $chunks->toArray();
                            @endphp
                            @foreach ($menus as $six)
                                <div class="pe-2 float-start float-lg-none">
                                    <div class="row">
                                        @foreach ($six as $sie)
                                            <div class="col-6 col-lg-12">
                                                <a class="nav-link px-0 text-white" href="{{ $sie['url'] }}"
                                                    title="{{ $sie['name'] }}">{{ $sie['name'] }}</a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            Menü Bulunamadı
                        @endif
                    </div>
                </div>
            </div>
        @elseif($design == 2)
            <div class="row">
                <div class="col-12">
                    <hr class="border-gray m-0 mb-3">
                </div>
                <div class="col-12">
                    <nav class="navbar navbar-expand-md">
                        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                            @php $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu'.$menu_id.'.json'); @endphp
                            <div class="navbar-nav">
                                @foreach ($menus as $mkey => $menu)
                                    <a class="nav-link px-0 text-white externallink" href="{{ $menu['url'] }}"
                                        title="{{ $menu['name'] }}">{{ $menu['name'] }}</a>
                                @endforeach
                            </div>
                        @else
                            Menü Bulunamadı
                        @endif
                    </nav>
                    <p class="mb-4">
                        <small class="text-white-50">
                            Türkiye'den ve Dünya’dan son dakika haberleri, köşe yazıları, magazinden siyasete,
                            spordan seyahate bütün konuların tek adresi haber içerikleri izin alınmadan, kaynak
                            gösterilerek dahi iktibas edilemez. Kanuna aykırı ve izinsiz olarak kopyalanamaz,
                            başka yerde yayınlanamaz.
                        </small>
                    </p>
                </div>
            </div>

        </div>

    </footer>

    <div class="bosver" style="display:none !important;"></div>

    <div class="altsabit" style="display:none !important;">

        @if (isset($magicbox['live_stream_link']) ?? !blank($magicbox['live_stream_link']))
            <a style="width:33.3%; background:#ef0000;color:#FFFFFF;" href="{{ route('frontend.index') }}"><i
                    class="fa fa-home" style="font-size: 20px;"></i> Anasayfa</a>
            <a style="width:33.3%; background:#2a3e81;color:#FFFFFF;" onclick="menuac()"><i class="fa fa-list"></i>
                Kategoriler</a>
            <a style="width:33.3%; background:#ef0000;color:#FFFFFF;" href="{{ $magicbox['live_stream_link'] }}"
                title="link" target="_blank"><i class="fa fa-video-camera"></i>
                {{ $magicbox['live_stream_name'] }}</a>
        @else
            <a style="width:50%; background:#ef0000;color:#FFFFFF;" href="{{ route('frontend.index') }}"><i
                    class="fa fa-home" style="font-size: 20px;"></i> Anasayfa</a>
            <a style="width:50%; background:#2a3e81;color:#FFFFFF;" onclick="menuac()"><i class="fa fa-list"></i>
                Kategoriler</a>
        @endif

    </div>
@else
    <h5 class="text-danger">Menü ayarlanmamış!</h5>

@endif

@endif <a href="#" class="back-to-top"><i class="fa fa-arrow-circle-up"></i></a> <a
    href="javascript:history.back()" class="back-to-topx mobvar"><i class="fa fa-arrow-circle-left"></i></a>
<style>
    a.back-to-top {
        background: #93C225;
        color: #FFFFFF;
        z-index: 9999;
        border: 0px;
        position: fixed;
        display: none;
        bottom: 10px;
        right: 10px;
        width: 44px;
        height: 44px !important;
        text-align: center;
        overflow: hidden;
        border-radius: 100%;
        padding: 0px 0px 0px 0px !important;
    }

    a.back-to-topx {
        background: #93C225;
        color: #FFFFFF;
        z-index: 9999;
        border: 0px;
        position: fixed;
        display: none;
        bottom: 50%;
        left: 5px;
        width: 44px;
        height: 44px !important;
        text-align: center;
        overflow: hidden;
        border-radius: 100%;
        padding: 0px 0px 0px 0px !important;
    }

    a.back-to-top i,
    a.back-to-topx i {
        float: left;
        width: 100%;
        height: auto;
        overflow: hidden;
        margin: 0px 0px 0px 0px;
        padding: 10px 0px 0px 0px;
        border: 0px;
        text-align: center;
        color: #1d222e;
        font-size: 24px;
        text-decoration: none;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        -ms-box-sizing: border-box;
        box-sizing: border-box;
    }

    a.back-to-top:hover,
    a.back-to-topx:hover {
        text-decoration: none;
    }

    body.scrolled a.back-to-top {
        display: block;
    }

    body.scrolled .header {
        position: fixed;
        top: 0;
    }

    @media only screen and (max-width: 1000px) {
        body.scrolled a.back-to-topx {
            display: block;
        }
    }
</style>
<script>
    window.addEventListener('scroll', function() {
        if (window.scrollY > 350) {
            document.body.classList.add('scrolled');
        } else {
            document.body.classList.remove('scrolled');
        }
    });
</script>
