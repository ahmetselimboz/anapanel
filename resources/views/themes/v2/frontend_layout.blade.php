<!doctype html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (isset($magicbox['refresh']))
    <meta http-equiv="refresh" content="{{ $magicbox['refresh'] }}">
    @endif

    @if ($magicbox['noindex'] == 1)
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    @endif

    <link rel="icon" type="image/png"
        href="{{ asset($settings["favicon"]) }}" />

    @yield('meta')

    <!-- Frontend Tema -->
    <link rel="stylesheet" href="{{ asset('v2_public/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('v2_public/assets/css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('v2_public/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('v2_public/assets/css/style-media.css') }}">
    <link rel="stylesheet" href="{{ asset('v2_public/assets/css/icons.css') }}">
    <link rel="stylesheet" href="{{ asset('v2_public/assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('v2_public/assets/css/slick-theme.css') }}">

    <!-- Tasarım Tema -->

    <link rel="stylesheet" href="{{ asset('v2_public/assets/css/yeni.css') }}">
    <link rel="stylesheet" href="{{ asset('v2_public/assets/css/font/css/font-awesome.min.css') }}" />

    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet"> -->
        @if($magicbox["generalfont"]!=0)

            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family={{$magicbox['generalfont']}}:wght@300;400&display=swap" rel="stylesheet">

            <style>
                /* Genel font ayarı */
                body :not(.fa):not([class*="fa-"]) {
                    font-family: "{{$magicbox["generalfont"] }}",sans-serif;
                }
            </style>
        @endif
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Tasarım Tema -->


    {!! $magicbox['headcode'] !!}

    <!-- özel css -->
    @yield('custom_css')

    @if (isset($magicbox['rightclick']) && $magicbox['rightclick'] === '0')
    <style>
        .popup-overlay {
            position: fixed;
            z-index: 100;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;

        }

        .popup-content {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 24px;
            width: 90%;
            max-width: 400px;
            text-align: center;
        }

        .popup-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 16px;
        }

        .popup-text {
            color: #555;
            margin-bottom: 16px;
        }

        .popup-button {
            width: 100%;
            background: #e3342f;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .popup-button:hover {
            background: #cc1f1a;
        }

        .hidden {
            display: none;
        }

        @media (max-width: 480px) {
            .popup-content {
                padding: 16px;
                max-width: 90%;
            }

            .popup-title {
                font-size: 1.25rem;
            }

            .popup-text {
                font-size: 0.9rem;
            }

            .popup-button {
                font-size: 0.9rem;
                padding: 10px;
            }
        }
    </style>
    @endif
</head>

<body>
    @if (isset($magicbox['rightclick']) && $magicbox['rightclick'] === '0')
    <div id="popup" class="popup-overlay hidden">
        <div class="popup-content">
            <h2 class="popup-title">Dikkat!</h2>
            <p class="popup-text">
                Sistemimizde verilerin güvenliği nedeni ile her türlü kopyalama ve sağ tık gibi işlemler
                tarafımızca engellenmiştir.
            </p>
            <button id="closePopup" class="popup-button">Tamam</button>
        </div>
    </div>
    @endif
    {!! $magicbox['bodycode'] !!}
    @include('themes.' . $theme . '.inc.header')
    @yield('content')
    @include('themes.' . $theme . '.inc.footer')
    <button id="popupModalButton" type="button" class="d-none" data-bs-toggle="modal"
        data-bs-target="#popupModal"></button>

    <!-- Frontend Tema -->
    <script type="text/javascript" src="{{ asset('v2_public/assets/js/jquery-3.4.1.js') }}"></script>
    <script type="text/javascript" src="{{ asset('v2_public/assets/js/jquery.lazyload.js') }}"></script>
    <script type="text/javascript" src="{{ asset('v2_public/assets/js/bootstrap.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ asset('v2_public/assets/js/jquery.touchSwipe.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('v2_public/assets/js/slick.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('v2_public/assets/js/script.js') }}"></script>
    <script type="text/javascript" src="{{ asset('v2_public/assets/js/script-home.js') }}"></script>
    <script type="text/javascript" src="{{ asset('v2_public/assets/js/jquery_cookie.js') }}"></script>

    @if (adsCheck(1) && adsCheck(1)->publish == 0)
    <!-- Popup reklam -->
    <div class="modal fade" id="popupModal" tabindex="-1" aria-labelledby="popupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0"><button type="button" class="btn-close" id="checkButton"
                        data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body">
                    @if (adsCheck(1)->type == 1)
                    {!! adsCheck(1)->code !!}
                    @else
                    <a href="{{ adsCheck(1)->url }}" class="externallink" title="Reklam 1">
                        <img src="{{ asset(adsCheck(1)->images) }}" alt="Reklam 1" class="img-fluid lazy"
                            data-type="{{ adsCheck(1)->type }}" height="{{ adsCheck(1)->height }}"
                            width="{{ adsCheck(1)->width }}">
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        @if(adsCheck(1) && adsCheck(1)->publish == 0)
        $(document).ready(function() {
            if ($.cookie('popupkapat') !== "true") {
                $("#popupModalButton").click();
            }
            $("#checkButton").click(function() {
                $.cookie("popupkapat", "true", {
                    path: '/',
                    expires: 1
                });
                $('#popupModal').modal('hide');
            });
        });
        @endif
    </script>
    @endif

    <script type="text/javascript">
        @if($magicbox['externallink'] == 0)
        $(".externallink").attr("target", "_blank");
        @endif

        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#userlogin").on("submit", function(e) {
            $.ajax({
                url: "{{ route('userloginfrontend') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    usermail: $("input[name=usermail]").val(),
                    userpassword: $("input[name=userpassword]").val()
                },
                success: function(response) {
                    if (response === "ok") {
                        $(".loginerror").addClass("d-none");
                        $(".loginsuccess").removeClass("d-none");
                        window.location.href = "{{ route('userprofile') }}";
                    } else {
                        $(".loginerror").removeClass("d-none");
                        $(".loginsuccess").addClass("d-none");
                    }
                },
                error: function(response) {},
            });
            e.preventDefault();
        });
        $("#userregister").on("submit", function(e) {
            $.ajax({
                url: "{{ route('userregisterfrontend') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    usernamereg: $("input[name=usernamereg]").val(),
                    usermailreg: $("input[name=usermailreg]").val(),
                    userpasswordreg: $("input[name=userpasswordreg]").val()
                },
                success: function(response) {
                    if (response === "ok") {
                        $(".loginerrorreg").addClass("d-none");
                        $(".loginsuccessreg").removeClass("d-none");
                        window.location.href = "{{ route('userprofile') }}";
                    } else {
                        $(".loginerrorreg").removeClass("d-none");
                        $(".loginsuccessreg").addClass("d-none");
                    }
                },
                error: function(response) {},
            });
            e.preventDefault();
        });

        // Namaz vaktine kalan süre
        @if($routename == 'frontend.index')
            @if(\Illuminate\Support\Facades\Storage::disk('public')->exists('prayer.json'))
                @php
                    $prayer = \Illuminate\Support\Facades\Storage::disk('public')->json('prayer.json');
                @endphp
                @if(isset($prayer['result']))
                    @php
                        $now = date("H:i");
                    @endphp
                    @foreach($prayer['result'] as $item)
                        @if(strtotime($item['saat']) > strtotime($now))
                            @php
                                $fark = strtotime($item['saat']) - strtotime($now);
                                $saat = $fark / 60 / 60;
                                $dakika_farki = floor($fark / 60 - floor($saat) * 60);
                                $gun = $saat / 24;
                                $saat_farki = floor($saat - floor($gun) * 24);
                            @endphp
                            $(".namaz-time").html({
                                {$saat_farki}} + ":" + {{$dakika_farki}});
                            $(".vakit").html("{{ $item['vakit '] }}");
                        @break
                        @else
                            $(".namaz-time").html("00:00");
                        @endif
                    @endforeach
                @endif
            @endif
        @endif
        // Namaz vaktine kalan süre

        @if($magicbox['rightclick'] == 0)
        var isNS = (navigator.appName == "Netscape") ? 1 : 0;
        var EnableRightClick = 0;
        if (isNS) document.captureEvents(Event.MOUSEDOWN || Event.MOUSEUP);

        function mischandler() {
            if (EnableRightClick == 1) {
                return true;
            } else {
                return false;
            }
        }

        function mousehandler(e) {
            if (EnableRightClick == 1) {
                return true;
            }
            var myevent = (isNS) ? e : event;
            var eventbutton = (isNS) ? myevent.which : myevent.button;
            if ((eventbutton == 2) || (eventbutton == 3)) return false;
        }

        function keyhandler(e) {
            var myevent = (isNS) ? e : window.event;
            if (myevent.keyCode == 96) EnableRightClick = 1;
            return;
        }
        document.oncontextmenu = mischandler;
        document.onkeypress = keyhandler;
        document.onmousedown = mousehandler;
        document.onmouseup = mousehandler;

        document.addEventListener("DOMContentLoaded", function() {
            const popup = document.getElementById("popup");
            const closePopup = document.getElementById("closePopup");

            document.addEventListener("copy", async (event) => {
                event.preventDefault();
                const copiedText = "Bu metin kopyalanamaz!";
                event.clipboardData.setData("text/plain", copiedText);
                popup.classList.remove("hidden");
            });

            closePopup.addEventListener("click", async () => {
                popup.classList.add("hidden");
            });

            document.addEventListener("contextmenu", function(event) {
                event.preventDefault();
                popup.classList.remove("hidden");
            });
            document.addEventListener("dragstart", (e) => e.preventDefault());

            document.addEventListener("keydown", function(e) {
                if (e.key === "F12" || (e.ctrlKey && e.shiftKey && e.key === "I") || (e.ctrlKey && e.key === "u")) {
                    e.preventDefault();
                }
            });

        });
        @endif
    </script>

    @if (isset($magicbox['googleanalytics']))
    {!! $magicbox['googleanalytics'] !!}
    @endif

    @if (isset($magicbox['yandexanalytics']))
    {!! $magicbox['yandexanalytics'] !!}
    @endif

    @if (isset($magicbox['yandexmetricaid']))
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function(m, e, t, r, i, k, a) {
            m[i] = m[i] || function() {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            for (var j = 0; j < document.scripts.length; j++) {
                if (document.scripts[j].src === r) {
                    return;
                }
            }
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
        })(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym("{{ $magicbox['yandexmetricaid'] }}", "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true,
            webvisor: true
        });
    </script>
    <noscript>
        <div>
            <img src="https://mc.yandex.ru/watch/{{ $magicbox['yandexmetricaid'] }}"
                style="position:absolute; left:-9999px;" alt="" />
        </div>
    </noscript>
    <!-- /Yandex.Metrika counter -->
    @endif

    @if (isset($magicbox['rightclick']) && $magicbox['rightclick'] === '0')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const popup = document.getElementById("popup");
            const closePopup = document.getElementById("closePopup");

            document.addEventListener("copy", async (event) => {
                event.preventDefault();
                const copiedText = "Bu metin kopyalanamaz!";
                event.clipboardData.setData("text/plain", copiedText);
                popup.classList.remove("hidden");
            });

            closePopup.addEventListener("click", async () => {
                popup.classList.add("hidden");
            });

            document.addEventListener("contextmenu", function(event) {
                event.preventDefault();
                popup.classList.remove("hidden");
            });
            document.addEventListener("dragstart", (e) => e.preventDefault());

            document.addEventListener("keydown", function(e) {
                if (e.key === "F12" || (e.ctrlKey && e.shiftKey && e.key === "I") || (e.ctrlKey && e.key ===
                        "u")) {
                    e.preventDefault();
                }
            });

        });



    </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bar = document.querySelector('.altsabit');

            if (!bar) return; // .altsabit yoksa işlem yapma

            window.addEventListener('scroll', function () {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                if (scrollTop > 200) {
                    bar.classList.add('show');
                } else {
                    bar.classList.remove('show');
                }
            });
        });
    </script>

    <!-- Özel js -->
    @yield('custom_js')


</body>
{!! $magicbox['footercode'] !!}







</html>