<style>
    .eczane-block {
        position: relative;
        width: 100%;
        float: left;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: nowrap;
        height: 60px;
        padding: 10px;
        gap: 10px;
    }

    .eczane-block >div {
        flex: 0 0 auto;
        display: flex;
        align-items: center;;
        gap: 10px;
    }

    .eczane-icon {
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;
        background-image: url(/frontend/assets/images/eczane.png);
        width: 25px;
        height: 25px;
        cursor: pointer;
    }

    .vefat-icon {
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;
        background-image: url(/frontend/assets/images/siyah_kurdele.png);
        width: 25px;
        height: 25px;
        cursor: pointer;
    }

    .eczane-time , .vefat-time {
        font-weight: bolder;
        color: #009000;
        font-size: 11px;
    }

    .weather-block {
        height: 100%;
    }
</style>
<div class="container d-none">
    <div class="row">
        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/sondakika_manset.json'))
            <div class="col-12 col-xl-8 mb-4"><!-- (Son Dakika) -->
                <div class="position-relative bg-last-minute rounded-1 overflow-hidden">
                    <div class="block-headline bg-black text-white d-none d-lg-block pt-3 pb-3">
                        <div class="pt-1 pb-1">SON DAKİKA</div>
                    </div>
                    <div class="last-minute-block overflow-hidden mt-0" id="lastminuteSlider">
                        @php $son_dakikalar = \Illuminate\Support\Facades\Storage::disk('public')->json('main/sondakika_manset.json'); @endphp
                        @foreach ($son_dakikalar as $son_dakika)
                            <div class="last-block-item text-truncate">
                                <a href="{{ route('post', ['categoryslug' => $son_dakika['categoryslug'], 'slug' => $son_dakika['slug'], 'id' => $son_dakika['id']]) }}"
                                   class="m-0 text-truncate text-uppercase text-dark externallink"
                                   title="{{ $son_dakika['title'] }}">
                                    <span
                                            class="px-1 bg-black me-2 text-white mt-1">{{ date('H:i', strtotime($son_dakika['created_at'])) }}</span>
                                    {{ \Illuminate\Support\Str::limit(html_entity_decode($son_dakika['title']), 58) }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="dark-pagination position-absolute">
                        <div class="dark-prev" id="lastminutePrev"></div>
                        <div class="dark-next" id="lastminuteNext"></div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning  d-none">
                Son Dakika Bandı Bulunamadı
            </div>
        @endif

        @if ($magicbox['apiservicestatus'] == 0)
            <div class="col-12 col-xl-4 mb-4"><!-- (Hava Durumu) -->
                <div class="bg-weather overflow-auto rounded-1">

                    @if ($magicbox['weather_status'] == 0)
                        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('weather.json'))
                            @php $weather = \Illuminate\Support\Facades\Storage::disk('public')->json('weather.json'); @endphp
                            @if (isset($weather['result']))
                                <div class="weather-block position-relative">
                                    <img src="{{ $weather['result'][0]['icon'] }}"
                                         style="width: 28px;height: 28px; position: absolute;cursor: pointer;left: 14px;top: 14px;"
                                         alt="{{ $weather['city'] }} hava durumu">
                                    <div class="weather-selector">
                                        <div class="weather-form pt-1">
                                            <span style="text-transform: uppercase;">{{ $weather['city'] }}</span>
                                        </div>
                                        <div class="weather-desc">
                                            {{ $weather['result'][0]['degree'] }}° <span
                                                    style="text-transform: uppercase;">{{ $weather['result'][0]['description'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    Veri Yok
                                </div>
                            @endif
                        @else
                            <div class="alert alert-warning  d-none">
                                Hava Durumu Bulunamadı
                            </div>
                        @endif
                    @else
                        {{-- <div class="alert alert-warning d-none">
                            Hava Durumu modülü kapalı
                        </div> --}}
                    @endif

                    @if ($magicbox['prayer_status'] == 0)
                        <div class="eczane-block position-relative justify-content-start">
                            <div>
                                <div class="eczane-icon"></div>
                                <div class="eczane-time">
                                    <a class="text-danger text-uppercase" target="_blank"
                                       href="https://www.eczaneler.gen.tr/nobetci-rize" style="font-weight: bold;">Rize Nöbetçi Eczaneler </a></div>
                            </div>
                            <div>
                                <div class="vefat-icon"></div>
                                <div class="vefat-time">
                                    <a class="text-danger text-uppercase" target="_blank" href="/vefat" style="font-weight: bold;">Vefat Haberleri </a></div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning d-none">
                            Eczane modülü kapalı
                        </div>
                    @endif

                </div>
            </div>
        @else
            <div class="alert alert-warning">
                API servisi kapalı
            </div>
        @endif

    </div>
</div>


@if (isset($magicbox['sondakikacolor']))
    <style>
        .bg-last-minute {
            background-color: {{ $magicbox['sondakikacolor'] }}  !important;
        }
    </style>
@endif

@if (isset($magicbox['sondakikacolortext']))
    <style>
        .last-block-item > a {
            color: {{ $magicbox['sondakikacolortext'] }}  !important;
        }
    </style>
@endif
