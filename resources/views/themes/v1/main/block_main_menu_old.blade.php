@if (isset($design))
    @if ($design == 'default' or $design == 0)
        @if ($routename == 'frontend.index' and isset($magicbox['headercolor'] ) )
            <style>
                .headercolor {
                    background-color: {{ $magicbox['headercolor'] }} !important;
                }

                header.headercolor .navbar-nav.ms-auto .nav-item:after {
                    border-right: 2px solid {{ $magicbox['headercolor'] }} !important;
                }
            </style>
        @endif

        @if ($routename == 'frontend.index' and isset($magicbox['headercolortext']))
            <style>
                header .nav-link,
                .categorycolor ul li button div {
                    color: {{ $magicbox['headercolortext'] }} !important;
                }
            </style>
        @endif

        @if ($routename == 'category')
            <style>
                .categorycolor {
                    background-color: {{ $category->color }} !important;
                }

                header.categorycolor .navbar-nav.ms-auto .nav-item:after {
                    border-right: 2px solid {{ $category->color }} !important;
                }

                .categorycolor a {
                    color: {{ $category->text_color }} !important;
                }
            </style>
        @endif

        @if ($routename == 'post')
            <style>
                .categorycolor {
                    background-color: {{ $post->category->color }} !important;
                }

                header.categorycolor .navbar-nav.ms-auto .nav-item:after {
                    border-right: 2px solid {{ $post->category->color }} !important;
                }
            </style>
        @endif

        @if (
            $routename == 'video_galleries' ||
                $routename == 'video_gallery' ||
                $routename == 'photo_galleries' ||
                $routename == 'photo_gallery' ||
                $routename == 'article')
            <style>
                .categorycolor {
                    background-color: #F7F7F7 !important;
                    background: #F7F7F7 !important;
                }

                header .navbar-nav.ms-auto .nav-item:after {
                    display: none !important;
                }

                #headerCategories a {
                    color: #000000 !important;
                }
            </style>
        @endif

        @if ($routename == 'search')
            <style>
                .categorycolor {
                    background-color: #F7F7F7 !important;
                    background: #F7F7F7 !important;
                }

                header .navbar-nav.ms-auto .nav-item:after {
                    display: none !important;
                }
            </style>
        @endif
        <style>
            .authors {
                --bs-bg-opacity: 1;
                background-color: rgba(var(--bs-light-rgb), var(--bs-bg-opacity)) !important;
            }

        </style>

        <header class="mb-4 sticky-top headercolor categorycolor {{$routename == 'authors' ?  'authors' : ""}}">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="navbar navbar-expand text-white py-0">
                            <a class="navbar-brand p-0" href="{{ route('frontend.index') }}"
                                title="{{ $magicbox['title'] }}">
                                <img src="{{ asset('uploads/' . $settings['logo']) }}" alt="{{ $magicbox['title'] }}"
                                    class="brand-logo" width="200">
                            </a>
                            <div class="navbar-nav me-auto mb-0 overflow-hidden">
                                <div id="headerCategories" class="h-scroll d-none d-md-block">
                                    <div class="h-scroll-contain">
                                        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                                            @php $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu'.$menu_id.'.json'); @endphp
                                            @foreach ($menus as $mkey => $menu)
                                                <div class="nav-item h-scroll-item">
                                                    <a class="nav-link externallink" href="{{ $menu['url'] }}"
                                                        title="{{ $menu['name'] }}">{{ $menu['name'] }}</a>
                                                </div>
                                                @if ($mkey == 8)
                                                @break
                                            @endif
                                        @endforeach
                                    @else
                                        Menü Bulunamadı
                                    @endif
                                </div>
                            </div>
                        </div>
                        <ul class="navbar-nav ms-auto mb-0 flex-nowrap flex-row">
                            <li class="nav-item position-relative">
                                <button class="nav-link text-white" id="searching-btn">
                                    <div class="icon-search"></div>
                                </button>
                            </li>
                            <li class="nav-item position-relative">
                                @if (auth()->check())
                                    <a href="{{ route('userprofile') }}" class="nav-link text-white"
                                        title="Profilim">
                                        <div class="icon-user"></div>
                                    </a>
                                @else
                                    <button class="nav-link text-white" id="signIn">
                                        <div class="icon-user"></div>
                                    </button>
                                @endif
                            </li>
                            <li class="nav-item position-relative">
                                <button class="nav-link text-white" id="menu-btn">
                                    <div class="icon-menu"></div>
                                </button>
                            </li>
                        </ul>
                        <div class="position-relative" id="burger-menu-contain">
                            <div class="before-overlay"></div>
                            <div class="big-menu rounded-1 pb-2 bg-white" id="burger-menu">
                                <div class="big-menu-header p-2 pb-0">
                                    <div class="menu-close-btn text-end" id="menu-close">
                                        <i class="icon-close2 d-inline-block"></i>
                                    </div>
                                </div>
                                @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                                    @php $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu'.$menu_id.'.json'); @endphp
                                    @foreach ($menus as $mkey => $menu)
                                        @if ($mkey > 8)
                                            <div class="nav-item">
                                                <a class="nav-link externallink" href="{{ $menu['url'] }}"
                                                    title="{{ $menu['name'] }}">{{ $menu['name'] }}</a>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    Menü Bulunamadı
                                @endif
                                <div class="nav-item">
                                    <a class="nav-link externallink" href="{{ route('authors') }}"
                                        title="Yazarlar">Yazarlar</a>
                                </div>
                                <div class="nav-item">
                                    <a class="nav-link externallink" href="{{ route('photo_galleries') }}"
                                        title="Foto Galeri">Foto Galeri</a>
                                </div>
                                <div class="nav-item">
                                    <a class="nav-link externallink" href="{{ route('video_galleries') }}"
                                        title="Video Galeri">Video Galeri</a>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="d-block d-md-none">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div id="headerCategories2" class="h-scroll">
                            <div class="h-scroll-contain">
                                @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('menu' . $menu_id . '.json'))
                                    @php $menus = \Illuminate\Support\Facades\Storage::disk('public')->json('menu'.$menu_id.'.json'); @endphp
                                    @foreach ($menus as $mkey => $menu)
                                        <div class="nav-item h-scroll-item">
                                            <a class="nav-link text-dark externallink" href="{{ $menu['url'] }}"
                                                title="{{ $menu['name'] }}">{{ $menu['name'] }}</a>
                                        </div>
                                        @if ($mkey == 8)
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
        </div>
    </div>
</header>
@elseif($design == 1)
<style>
    footer .nav-link:hover {
        color: #ffffff !important;
    }
</style>
<footer class="bg-dark-gradiant">
    <div class="container">
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
                        gösterilerek dahi iktibas edilemez. Kanuna aykırı ve izinsiz olarak kopyalanamaz, başka
                        yerde yayınlanamaz.
                    </small>
                </p>
            </div>
        </div>
    </div>
</footer>
@else
<h5 class="text-danger">Menü ayarlanmamış!</h5>
@endif
@endif
