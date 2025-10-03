<header class="main-header">
    <div class="d-flex align-items-center logo-box justify-content-start">
        <!-- Logo -->
        <a href="" class="logo">
            <!-- logo-->
            <div class="logo-mini w-100">
                <span class="light-logo"><img src="{{ asset('backend/assets/ersdanismanliklogo.png') }}"
                        alt="logo"></span>
                <span class="dark-logo"><img src="{{ asset('backend/assets/ersdanismanliklogo.png') }}"
                        alt="logo"></span>
            </div>
            <div class="logo-lg d-none d-lg-block">
                <span class="light-logo"><img src="{{ asset('backend/assets/ersdanismanliklogo.png') }}"
                        alt="logo"></span>
                <span class="dark-logo"><img src="{{ asset('backend/assets/ersdanismanliklogo.png') }}"
                        alt="logo"></span>
            </div>
        </a>
    </div>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <div class="app-menu">
            <ul class="header-megamenu nav">
                <li class="btn-group nav-item">
                    <a href="#"
                        class="waves-effect waves-light nav-link push-btn btn-outline no-border btn-primary-light"
                        data-toggle="push-menu" role="button">
                        <i data-feather="align-left"></i>
                    </a>
                </li>
                <!-- Arama alanı geçici olarak kapatıldı  -->
                <li class="btn-group  d-none">
                    <div class="app-menu">
                        <div class="search-bx mx-5">
                            <form>
                                <div class="input-group">
                                    <input type="search" class="form-control" placeholder="Search" aria-label="Search"
                                        aria-describedby="button-addon2">
                                    <div class="input-group-append">
                                        <button class="btn" type="submit" id="button-addon3"><i
                                                data-feather="search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </li>
                <li class="btn-group nav-item  d-xl-inline-block">
                    <a href="{{ route('frontend.index') }}" target="_blank"
                        class="waves-effect waves-light nav-link btn-outline no-border svg-bt-icon btn-info-light"
                        title="Anasayfa Görüntüle">
                        <i data-feather="monitor"></i>
                    </a>
                </li>
                <li class="btn-group nav-item  d-xl-inline-block">
                    <a href="{{ route('post.create') }}"
                        class="waves-effect waves-light nav-link btn-outline no-border svg-bt-icon btn-danger-light"
                        title="Haber Ekle">
                        <i data-feather="plus-square"></i>
                    </a>
                </li>
                <li class="btn-group nav-item  d-xl-inline-block">
                    <a href="{{ route('optimize') }}"
                        class="waves-effect waves-light btn-outline no-border nav-link svg-bt-icon btn-success-light"
                        title="Önbellek Temizle">
                        <i data-feather="refresh-ccw"></i>
                    </a>
                </li>
                <li class="btn-group nav-item d-none d-xl-inline-block">
                    <a href="{{ route('MainJsonFileUpdateButton') }}"
                        class="waves-effect waves-light btn-outline no-border nav-link svg-bt-icon btn-success-light"
                        title="Anasayfa Haber Görünmeme Hatası Onar">
                        <i class="fa fa-regular fa-bug"></i>
                    </a>
                </li>
                @if (Auth::check() and Auth::user()->status == 1)
                    <li class="btn-group nav-item d-none d-xl-inline-block">
                        <a href="{{ route('jsonsystemcreate') }}"
                            class="waves-effect waves-light btn-outline no-border nav-link svg-bt-icon btn-success-light"
                            title="Sistem Yapısı Oluşturma ve Temizleme">
                            <i class="fa fa-regular fa-magic"></i>
                        </a>
                    </li>
                    <li class="btn-group nav-item d-none d-xl-inline-block">
                        <a href="{{ route('apiupdate') }}"
                            class="waves-effect waves-light btn-outline no-border nav-link svg-bt-icon btn-success-light"
                            title="API sistemindeki dosyaları güncelle (Namaz vakti, hava durumu ve kurlar)">
                            <i class="fa fa-regular fa-book"></i>
                        </a>
                    </li>
                @endif
                @if (Route::currentRouteName() == 'post.index' ||
                        Route::currentRouteName() == 'post.search' ||
                        Route::currentRouteName() == 'post_archive')
                    <li class="btn-group d-lg-inline-flex d-none">
                        <div class="app-menu">
                            <div class="search-bx mx-5">
                                <form method="post" action="{{ route('post.search') }}">
                                    @csrf
                                    <div class="input-group">
                                        <input type="search" class="form-control" placeholder="Haber ara"
                                            aria-label="Haber ara" aria-describedby="button-addon2" name="search"
                                            value="{{ $search ?? '' }}">
                                        <input type="hidden" name="isArchived" value="{{ $archive }}">
                                        <div class="input-group-append">
                                            <button class="btn" type="submit" id="button-addon3"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-search">
                                                    <circle cx="11" cy="11" r="8"></circle>
                                                    <line x1="21" y1="21" x2="16.65"
                                                        y2="16.65"></line>
                                                </svg></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </li>
                @endif

            </ul>
        </div>

        <div class="navbar-custom-menu r-side">
            <ul class="nav navbar-nav">
                <li class="btn-group nav-item d-lg-inline-flex d-none">
                    <a href="#" data-provide="fullscreen"
                        class="waves-effect waves-light nav-link btn-outline no-border full-screen btn-warning-light"
                        title="Tam Ekran">
                        <i data-feather="maximize"></i>
                    </a>
                </li>
                <!-- Notifications -->
                <li class="dropdown notifications-menu d-none">
                    <a href="#"
                        class="waves-effect waves-light dropdown-toggle btn-outline no-border btn-info-light"
                        data-bs-toggle="dropdown" title="Bildirimler">
                        <i data-feather="bell"></i>
                    </a>
                    <ul class="dropdown-menu animated bounceIn">
                        <li class="header">
                            <div class="p-20">
                                <div class="flexbox">
                                    <div>
                                        <h4 class="mb-0 mt-0">Bildirimler</h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <ul class="menu sm-scrol">
                                <li> <a href="#"> <i class="fa fa-users text-info"></i> Yorum geldi </a> </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="dropdown user user-menu">
                    <a href="" class="waves-effect waves-light dropdown-toggle no-border p-5"
                        data-bs-toggle="dropdown" title="User">
                        <i class="fa fa-user text-info mt-1"></i>
                    </a>
                    <ul class="dropdown-menu animated flipInX">
                        <li class="user-body">
                            <a class="dropdown-item" href="{{ route('users.edit', Auth::user()->id) }}"><i
                                    class="ti-user text-muted me-2"></i> Profilim</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ti-lock text-muted me-2"></i> Çıkış
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf </form>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                @if (Auth::check() and Auth::user()->status == 1)
                    <li>
                        <a href="{{ route('settings') }}" title="Setting"
                            class="waves-effect waves-light btn-outline no-border btn-danger-light">
                            <i data-feather="settings"></i>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
</header>
