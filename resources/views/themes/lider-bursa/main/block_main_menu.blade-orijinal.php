            <style>
                #cookie-banner {
                    width: 100%;
                    position: fixed;
                    z-index: 99999999;
                    bottom: 0;
                    left: 0%;
                    right: 0%;
                    background-color: #333;
                    color: #fff;
                    padding: 20px;
                    text-align: center;
                    display: none;
                }

                #cookie-banner button {
                    background-color: #E52020;
                    color: white;
                    padding: 10px;
                    border: none;
                    cursor: pointer;
                }

                #cookie-banner a {
                    background-color: #E52020;
                    color: white;
                    padding: 10px 10px;
                    border: none;
                    cursor: pointer;
                }

                .cookie-btn-area{
                    width: 100%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin-top: 10px;
                }

                .live-icon {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    margin: 0 !important;
                    padding-top: 0 !important;
                    color: #d90429 !important; /* Başlangıç rengi kırmızı */

                }

                .dot {
                    width: 12px;
                    height: 12px;
                    background-color: #d90429; /* Kırmızı canlı yayın rengi */
                    border-radius: 50%;
                    box-shadow: 0 0 10px rgba(217, 4, 41, 0.8); /* İlk parlama efekti */
                    animation: redPulse 1.5s infinite alternate;
                }

                @keyframes redPulse {
                    0% {
                        opacity: 1;
                        box-shadow: 0 0 5px rgba(217, 4, 41, 0.6);
                    }
                    50% {
                        opacity: 0.5;
                        box-shadow: 0 0 15px rgba(217, 4, 41, 1);
                    }
                    100% {
                        opacity: 1;
                        box-shadow: 0 0 5px rgba(217, 4, 41, 0.6);
                    }
                }


            </style>

            <div id="cookie-banner">
                Sizlere daha iyi hizmet sunabilmek adına sitemizde çerez konumlandırmaktayız. Kişisel verileriniz, KVKK ve GDPR
                kapsamında toplanıp işlenir. Detaylı bilgi almak için Aydınlatma Metni'ni inceleyebilirsiniz.
                <div class="cookie-btn-area">
                    <button onclick="acceptCookies()" class="me-2">Çerezleri Kabul Et</button>
                    <a href="{{route('page','gizlilik-politikasi')}}">Gizlilik Politikamız</a>
                </div>

            </div>

            <script>

                function checkCookies() {
                    if (document.cookie.indexOf('cookies_accepted=true') === -1) {
                        document.getElementById('cookie-banner').style.display = 'block';
                    }
                }

                function acceptCookies() {
                    document.cookie = "cookies_accepted=true; path=/; max-age=" + 60 * 60 * 24 * 365;
                    document.getElementById('cookie-banner').style.display = 'none';
                }

                window.onload = function () {
                    checkCookies();
                };

            </script>


            @if (isset($design))
                @if ($design == 'default' or $design == 0)
                    <div class="container">
                        <div class="header">
                            <div class="mobilbuton2">
                                <button onclick="sosyalmenuac()"><i class="fa fa-list"></i></button>
                            </div>
                            <div class="logo"><a href="{{ route('frontend.index') }}" title="{{ $magicbox['title'] }}"><img
                                            src="{{ asset('uploads/' . $settings['logo']) }}" alt="{{ $magicbox['title'] }}"/></a>
                            </div>
                            <div class="mobilbuton">
                                <button id="searching-btn2"><i class="fa fa-search"></i></button>
                                @if (auth()->check())
                                    <a href="{{ route('userprofile') }}" title="Profilim"><i class="fa fa-user"></i></a>
                                @else
                                    <button id="signIn2"><i class="fa fa-user"></i></button>
                                @endif
                            </div>
                            @php $ads23 = adsCheck(23); @endphp
                            @if ($ads23)
                                <div class="logosag">
                                    @if($ads23->type == 1)
                                        {!!$ads23->code!!}
                                    @else
                                        <a href="{{$ads23->url}}" class="externallink" title="Reklam 23">
                                            <img src="{{asset($ads23->images) }}" alt="Reklam 1"
                                                class="lazy" data-type="{{$ads23->type}}"
                                                height="{{$ads23->height ?? '70'}}" width="{{$ads23->width ?? '100%'}}">
                                        </a>
                                    @endif
                                </div>

                            @endif
                        </div>
                        <div class="tmz"></div>
                        <div class="ustmenu mobyok">
                            <ul>
                                @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                                    @php $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu'.$menu_id.'.json');
                                    $live_stream = isset($magicbox['live_stream']) ? $magicbox['live_stream'] : null;

                                    @endphp
                                    @foreach ($menus as $mkey => $menu)
                                        <li><a href="{{ $menu['url'] }}" title="{{ $menu['name'] }}">{{ $menu['name'] }}</a></li>
                                        @if (!blank($live_stream) && $mkey == 7 || blank($live_stream) && $mkey == 8)
                                            @break
                                        @endif
                                    @endforeach
                                    @if (!blank($live_stream))
                                            <li><a href="{{ $live_stream }}" title="CANLI YAYIN" class="live-icon"
                                                target="_blank">
                                                <span class="dot"></span>
                                                CANLI YAYIN</a>
                                            </li>
                                    @endif
                                @else
                                    Menü Bulunamadı
                                @endif

                            </ul>
                            <div class="ustsosyal">
                                <button onclick="menuac2()"><i class="fa fa-list"></i></button>
                                <a href="{{ $magicbox['fb'] }}" style="background: #30487b;"><i class="fa fa-facebook"></i></a>
                                <a href="{{ $magicbox['tw'] }}" style="background: #2ba9e1;"><i class="fa fa-twitter"></i></a>
                                <a href="{{ $magicbox['in'] }}" style="background: #BD0082;"><i class="fa fa-instagram"></i></a>
                                <a href="{{ $magicbox['yt'] }}" style="background: #f74c53;"><i class="fa fa-youtube"></i></a>

                            </div>
                        </div>
                    </div>
                    <div class="tmz"></div>
                    <div class="mobilustmenu">
                        <ul>
                            @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                                @php $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu'.$menu_id.'.json'); @endphp @foreach ($menus as $mkey => $menu)
                                    <li><a href="{{ $menu['url'] }}" title="{{ $menu['name'] }}">{{ $menu['name'] }}</a></li>
                                    @if ($mkey == 4)
                                        @break
                                    @endif
                                @endforeach
                            @else
                                Menü Bulunamadı
                            @endif
                        </ul>
                        <button onclick="menuac()"><i class="fa fa-list"></i></button>
                    </div>
                    <div class="tmz"></div>
                    <div class="sondakika">
                        <div class="container">
                            @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/sondakika_manset.json'))
                                <div class="sondakikaok">
                                    <div class="ok-prev" id="lastminutePrev"></div>
                                    <div class="ok-next" id="lastminuteNext"></div>
                                </div>
                                <div id="lastminuteSlider"> @php $son_dakikalar = \Illuminate\Support\Facades\Storage::disk('public')->json('main/sondakika_manset.json'); @endphp
                                    @foreach ($son_dakikalar as $son_dakika)
                                        <a href="{{ route('post', ['categoryslug' => $son_dakika['categoryslug'], 'slug' => $son_dakika['slug'], 'id' => $son_dakika['id']]) }}"
                                        title="{{ $son_dakika['title'] }}">
                                            <span>{{ date('H:i', strtotime($son_dakika['created_at'])) }}</span>
                                            {{--                       <p id="lastMinuteTitle">{{ \Illuminate\Support\Str::limit(html_entity_decode($son_dakika['title']), 215) }}</p>  --}}
                                            <p id="lastMinuteTitle_{{ $son_dakika['id'] }}">{{ \Illuminate\Support\Str::limit(html_entity_decode($son_dakika['title']), 215) }}</p>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning  d-none"> Son Dakika Bandı Bulunamadı</div>
                            @endif
                            <div class="arama">
                                <button id="searching-btn"><i class="fa fa-search"></i>
                                    <p>Site İçi Arama Yapabilirsiniz</p>
                                </button>
                            </div>
                            <div class="eklink">
                                @if (auth()->check())
                                    <a href="{{ route('userprofile') }}" title="Profilim"><i class="fa fa-user"></i></a>
                                @else
                                    <button id="signIn"><i class="fa fa-user"></i>Üye Girişi</button>
                                @endif
                                {{-- <a href="" title=""><i class="fa fa-whatsapp"></i></a> --}}
                            </div>
                        </div>
                    </div>
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
                    <div id="sosyalmenu">
                        <div class="mlogo"><a href="{{ route('frontend.index') }}" title="{{ $magicbox['title'] }}"><img
                                        src="{{ asset('uploads/' . $settings['logo']) }}" alt="{{ $magicbox['title'] }}"/></a>
                        </div>
                        <div class="kapat"><a onclick="sosyalmenukapat()" href="#">x</a></div>
                        <div class="tmz"></div>
                        <br/><br/>
                        <div class="tmz"></div>
                        <p><a href="{{ $magicbox['fb'] }}" style="background: #30487b;"><i class="fa fa-facebook"></i></a></p>
                        <p><a href="{{ $magicbox['tw'] }}" style="background: #2ba9e1;"><i class="fa fa-twitter"></i></a></p>
                        <p><a href="{{ $magicbox['in'] }}" style="background: #BD0082;"><i class="fa fa-instagram"></i></a></p>
                        <p><a href="{{ $magicbox['yt'] }}" style="background: #f74c53;"><i class="fa fa-youtube"></i></a></p>
                        <p><a href="{{ $magicbox['ln'] }}" style="background: #005f8d;"><i class="fa fa-linkedin"></i></a></p>
                    </div>
                    <div id="anamenu">
                        <div class="mlogo"><a href="{{ route('frontend.index') }}" title="{{ $magicbox['title'] }}"><img
                                        src="{{ asset('uploads/' . $settings['logo']) }}" alt="{{ $magicbox['title'] }}"/></a>
                        </div>
                        <div class="kapat"><a onclick="menukapat()" href="#">x</a></div>
                        <div class="tmz"></div>
                        <ul>
                            @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                                @php $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu'.$menu_id.'.json'); @endphp @foreach ($menus as $mkey => $menu)
                                    <li><a href="{{ $menu['url'] }}" title="{{ $menu['name'] }}">{{ $menu['name'] }}</a>
                                    </li>
                                    @if ($mkey == 20)
                                        @break
                                    @endif
                                @endforeach
                            @else
                                Menü Bulunamadı
                            @endif
                        </ul>
                        <div class="tmz"></div>
                        {{--<div class="ekmenu">--}}
                        {{--    <a href="#"><i class="fa fa-clock-o"></i>Son Dakika</a>--}}
                        {{--     <a href="#"><i class="fa fa-send"></i>Haber Gönder</a>--}}
                        {{--     <a href="#"><i class="fa fa-camera"></i>Video</a>--}}
                        {{--     <a href="#"><i class="fa fa-users"></i>Yazarlar</a>--}}
                        {{--    <a href="#"><i class="fa fa-suitcase"></i>Künye</a>--}}
                        {{--    <a href="#"><i class="fa fa-envelope"></i>İletişim</a>--}}
                        {{--</div>--}}
                    </div>
                    <div id="anamenu2">
                        <div class="mlogo"><a href="{{ route('frontend.index') }}" title="{{ $magicbox['title'] }}"><img
                                        src="{{ asset('uploads/' . $settings['logo']) }}" alt="{{ $magicbox['title'] }}"/></a>
                        </div>
                        <div class="kapat2"><a onclick="menukapat2()" href="#">x</a></div>
                        <div class="tmz"></div>
                        <ul>
                            @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                                @php $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu'.$menu_id.'.json'); @endphp @foreach ($menus as $mkey => $menu)
                                    <li><a href="{{ $menu['url'] }}" title="{{ $menu['name'] }}">{{ $menu['name'] }}</a>
                                    </li>
                                    @if ($mkey == 20)
                                        @break
                                    @endif
                                @endforeach
                            @else
                                Menü Bulunamadı
                            @endif
                        </ul>
                    </div>
                    <script type="text/javascript">
                        function sosyalmenuac() {
                            document.getElementById('sosyalmenu').style.display = "block";
                        }

                        function sosyalmenukapat() {
                            document.getElementById('sosyalmenu').style.display = "none";
                        }

                        function menuac() {
                            document.getElementById('anamenu').style.display = "block";
                        }

                        function menukapat() {
                            document.getElementById('anamenu').style.display = "none";
                        }

                        function menuac2() {
                            document.getElementById('anamenu2').style.display = "block";
                        }

                        function menukapat2() {
                            document.getElementById('anamenu2').style.display = "none";
                        }
                    </script>
                    <div class="tmz"></div>
                @elseif($design == 1)
                    @php $trend_news = collect(\Illuminate\Support\Facades\Storage::disk('public')->json('main/hit_news.json'));
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
                    <div class="altmenu">
                        <div class="container">
                            @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                                @php $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu'.$menu_id.'.json'); @endphp @foreach ($menus as $mkey => $menu)
                                    <a href="{{ $menu['url'] }}" title="{{ $menu['name'] }}">{{ $menu['name'] }}</a>
                                    @if ($mkey == 8)
                                        @break
                                    @endif
                                @endforeach
                            @else
                                Menü Bulunamadı
                            @endif
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <footer class="new-footer">
                        <div class="container">
                            <div class="new-footer-top">
                                <div class="new-footer-top-row row">
                                    <div class="fsol">
                                        <div class="fbaslik">Hoşgeldiniz</div>
                                        <p>{{ $settings['title'] }}</p> <span>{{ $settings['description'] }}</span>
                                    </div>
                                    <div class="fsag">
                                        <div class="fbaslik">Kurumsal</div>
                                        <div class="footerhaber">
                                            @if (!blank(config('pages')))
                                                    @php $pages = config('pages'); @endphp
                                                    @foreach ($pages as $page)
                                                        <a href="{{  route('page', $page->slug) }}">{{ $page->title}}</a>
                                                    @endforeach

                                            @endif
                                        </div>
                                    </div>
                                    <div class="ftam">
                                        <div class="fbaslik">Keşfet</div>
                                        @php
                                            $counter = 1;

                                        @endphp
                                        @foreach ($news_groups as $group)
                                            @if ($counter < 3)
                                                <div class="footerhaber">
                                                    @php
                                                        $counter2 = 1;

                                                    @endphp
                                                    @foreach ($group as $news)
                                                        @if ($counter2 < 4)
                                                            <a
                                                                    href="{{ route('post', ['categoryslug' => categoryCheck($news['category_id'])->slug, 'slug' => $news['slug'], 'id' => $news['id']]) }}">{{ $news['title'] }}</a>
                                                        @endif
                                                        @php
                                                            $counter2++;
                                                        @endphp
                                                    @endforeach
                                                </div>
                                            @endif
                                            @php
                                                $counter++;
                                            @endphp
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>


                        <a href="javascript:history.back()" class="back-to-topx mobvar"><i class="fa fa-arrow-circle-left"></i></a>

                        <style>

                            a.back-to-topx {
                                background: #E52020;
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

                            a.back-to-topx:hover {
                                text-decoration: underline;
                            }

                            @media only screen and (max-width: 1000px) {

                                body.scrolled a.back-to-topx {
                                    display: block;
                                }

                            }

                        </style>

                        <script>
                            window.addEventListener('scroll', function () {
                                if (window.scrollY > 500) {
                                    document.body.classList.add('scrolled');
                                } else {
                                    document.body.classList.remove('scrolled');
                                }
                            });

                            document.addEventListener('DOMContentLoaded', function () {
                                // Tüm başlıkları seç
                                const titles = document.querySelectorAll('[id^="lastMinuteTitle_"]'); // 'lastMinuteTitle_' ile başlayan id'lere sahip elementler

                                titles.forEach(function (titleElement) {
                                    const isMobile = window.innerWidth <= 768; // Mobil cihaz tespiti
                                    let title = titleElement.textContent;

                                    // Eğer cihaz mobilse, başlık karakter sayısını 100'e çek
                                    if (isMobile && title.length > 60) {
                                        title = title.substring(0, 60) + '...';
                                    }

                                    // Başlık değerini güncelle
                                    titleElement.textContent = title;
                                });
                            });


                        </script>


                        <div class="new-footer-bottom" style="background:#000;">
                            <div class="container">
                                <div class="new-footer-bottom-flex">
                                    <div class="new-footer-copyright-text">Tüm Hakları Saklıdır - 2025 - İzinsiz İçerikler
                                        Kullanılamaz
                                    </div>
                                    <div class="new-footer-social-icons">
                                        <div><a href="{{ $magicbox['tw'] }}" class="btn social-rounded-button externallink"
                                                target="_blank">
                                                <div class="icon-social-x"></div>
                                            </a></div>
                                        <div><a href="{{ $magicbox['fb'] }}" class="btn social-rounded-button externallink"
                                                target="_blank">
                                                <div class="icon-social-facebook"></div>
                                            </a></div>
                                        <div><a href="{{ $magicbox['in'] }}" class="btn social-rounded-button externallink"
                                                target="_blank">
                                                <div class="icon-social-instagram"></div>
                                            </a></div>
                                        <div><a href="{{ $magicbox['yt'] }}" class="btn social-rounded-button externallink"
                                                target="_blank">
                                                <div class="icon-social-youtube"></div>
                                            </a></div>
                                    </div>
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
                                            <div class="fw-bold footer-logo-capital-title text-dark">{{ $magicbox['title'] }}
                                            </div>
                                            <div class="fw-medium footer-logo-capital-desc text-dark">©
                                                {{ $magicbox['copyright'] }}</div>
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
                                                $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu' . $menu_id . '.json');
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
                                                başka
                                                yerde yayınlanamaz.
                                            </small>
                                        </p>
                                    </div>
                                </div>
                        </div>
                    </footer>
                    <div class="bosver mobvar"></div>
                    <div class="altsabit mobvar">
                        <a style="background:#ef0000;color:#FFFFFF;" href="{{ route('frontend.index') }}">
                            <i class="fa fa-home" style="font-size: 20px;"></i>
                            Anasayfa
                        </a>
                        <a style="background:#2a3e81;color:#FFFFFF;" onclick="menuac()">
                            <i class="fa fa-list"></i>
                            Kategoriler
                        </a>
                        @if (isset($magicbox['live_stream_link']) ?? !blank($magicbox['live_stream_link']))
                                <a href="{{ $magicbox['live_stream_link'] }}" title="link" style="background:#ef0000;color:#FFFFFF;"
                                target="_blank">
                                <i class="fa fa-video-camera"></i>
                                {{ $magicbox['live_stream_name'] }}
                            </a>
                        @endif

                    </div>
                @else
                    <h5 class="text-danger">Menü ayarlanmamış!</h5>
                @endif
            @endif

