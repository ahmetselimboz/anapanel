@if (isset($design))
    @if ($design == 'default' or $design == 0)

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

                        @if (auth()->check())
                            <a href="{{ route('userprofile') }}" title="Profilim"><i class="fa fa-user"></i></a>
                        @else
                            <button id="signIn"><i class="fa fa-user"></i></button>
                        @endif

                        <button id="searching-btn"><i class="fa fa-search"></i></button>

                        <button id="menuBtnu" onclick="menuac()"><i class="fa fa-list"></i></button>

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

        <div class="sondakika">
            <div class="container">

                <div class="sondakikalar">
                    <div class="marquee-content">

                        @php $ssay = 0; @endphp
                        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/sondakika_manset.json'))
                            @php $son_dakikalar = \Illuminate\Support\Facades\Storage::disk('public')->json('main/sondakika_manset.json'); @endphp
                            @foreach ($son_dakikalar as $son_dakika)
                                <a href="{{ route('post', ['categoryslug' => $son_dakika['categoryslug'], 'slug' => $son_dakika['slug'], 'id' => $son_dakika['id']]) }}"
                                    title="{{ $son_dakika['title'] }}">
                                    <span>{{ date('H:i', strtotime($son_dakika['created_at'])) }}</span>
                                    {{ \Illuminate\Support\Str::limit(html_entity_decode($son_dakika['title']), 58) }}
                                </a>
                                @php $ssay++; @endphp
                            @endforeach

                            <style>
                                .marquee-content {
                                    animation: sondakikalarmarquee {{ $ssay * 5 }}s linear infinite !important;
                                }

                                .sondakikalar:hover .marquee-content {
                                    animation-play-state: paused !important;
                                }
                            </style>

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

        <ul>
            @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                @php $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu'.$menu_id.'.json'); @endphp
                @foreach ($menus as $mkey => $menu)
                    <li><a href="{{ $menu['url'] }}" title="{{ $menu['name'] }}">{{ $menu['name'] }}</a></li>
                    @if ($mkey == 20)
                        @break
                    @endif
                @endforeach
            @else
                Menü Bulunamadı
            @endif
        </ul>

        <div class="tmz"></div>

        <div class="ekmenu">
            <a href="#"><i class="fa fa-clock-o"></i>Son Dakika</a>
            <a href="#"><i class="fa fa-whatsapp"></i>Haber Gönder</a>
            <a href="#"><i class="fa fa-camera"></i>Video</a>
            <a href="#"><i class="fa fa-users"></i>Yazarlar</a>
            <a href="#"><i class="fa fa-suitcase"></i>Künye</a>
            <a href="#"><i class="fa fa-envelope"></i>İletişim</a>
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
    @php$trend_news = collect(\Illuminate\Support\Facades\Storage::disk('public')->json('main/hit_news.json'));
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

    <div class="footerlogoalan">
        <div class="container">

            <div class="flogo">
                <a href="{{ route('frontend.index') }}" title="{{ $magicbox['title'] }}"><img
                        src="{{ asset('uploads/' . $settings['logo']) }}" alt="{{ $magicbox['title'] }}" /></a>
            </div>

            <div class="flogosag">

                <p><b>{{ $settings['title'] }}</b> {{ $settings['description'] }}</p>

                <div class="footer-social-icons">
                    <a href="{{ $magicbox['fb'] }}" style="background: #30487b;"><i class="fa fa-facebook"></i></a>
                    <a href="{{ $magicbox['tw'] }}" style="background: #2ba9e1;"><i class="fa fa-twitter"></i></a>
                    <a href="{{ $magicbox['in'] }}" style="background: #BD0082;"><i class="fa fa-instagram"></i></a>
                    <a href="{{ $magicbox['yt'] }}" style="background: #f74c53;"><i class="fa fa-youtube"></i></a>
                    <a href="{{ $magicbox['ln'] }}" style="background: #005f8d;"><i class="fa fa-linkedin"></i></a>
                </div>

            </div>

        </div>
    </div>

    <div class="tmz"></div>

    <footer class="new-footer">

        <div class="container">

            <div class="new-footer-top">
                <div class="new-footer-top-row row">

                    <div class="footerhaber">
                        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu2.json'))
                            @php $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu2.json'); @endphp
                            @foreach ($menus as $mkey => $menu)
                                <a href="{{ $menu['url'] }}" title="{{ $menu['name'] }}">{{ $menu['name'] }}</a>
                                @if ($mkey == 21)
                                    @break
                                @endif
                            @endforeach
                        @else
                            Menü Bulunamadı
                        @endif
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
                                <img src="{{ asset('uploads/' . $settings['logo']) }}" alt="{{ $magicbox['title'] }}"
                                    class="brand-logo" width="102">
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




    <a href="#" class="back-to-top"><i class="fa fa-arrow-circle-up"></i></a>

    <a href="javascript:history.back()" class="back-to-topx mobvar"><i class="fa fa-arrow-circle-left"></i></a>

    <style>
        a.back-to-top {
            background: #0A74B7;
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
            background: #0A74B7;
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
            color: #FFFFFF;
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
@else
    <h5 class="text-danger">Menü ayarlanmamış!</h5>

@endif

@endif
