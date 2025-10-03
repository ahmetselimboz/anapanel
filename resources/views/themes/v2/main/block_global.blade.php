@if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/standart_haberler.json'))
    @php
        $globalAllPosts = \Illuminate\Support\Facades\Storage::disk('public')->json('main/standart_haberler.json');
        $global_posts = categoryCheck($block_category_id)
            ? collect($globalAllPosts)->where('category_id', $block_category_id)
            : $globalAllPosts;
        $csay = 0;
        $c2say = 0;
    @endphp

    @if (isset($design))

        @if ($design == 'default')

            <!-- MANŞET ALTINDAKİ HABER BLOK TASARIMI News (Haberler) -->
            @if (categoryCheck($block_category_id) != false)

                <div class="container desing_{{ $design }}">
                    <div class="spotlar">

                        <div class="col-12">
                            <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                                <h2 class="text-black">
                                    {{ isset($block_category_id) ? categoryCheck($block_category_id)->title : 'Kategori ismi bulunamadı' }}
                                </h2>
                                <div class="headline-block-indicator">
                                    <div class="indicator-ball"
                                        style="background-color:{{ categoryCheck($block_category_id)->color }}!important;">
                                    </div>
                                </div>
                                <a href="{{ route('category', ['slug' => categoryCheck($block_category_id)->slug, 'id' => categoryCheck($block_category_id)->id]) }}"
                                    class="all-button externallink"
                                    title="{{ categoryCheck($block_category_id)->title }}">Tümü</a>
                            </div>
                        </div>

                        <div class="solkutu">

                            @php
                                $pre_key = 0;
                                $lsay = 0;
                            @endphp
                            @foreach ($global_posts as $key => $global_post)
                                {{-- @if ($global_post['category_id'] == $block_category_id) --}}
                                    <div class="spot-sabit spot-8">
                                        <a href="{{ route('post', ['categoryslug' => $global_post['categoryslug'], 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}"
                                            title="{{ html_entity_decode($global_post['title']) }}">
                                            <p><b>{{ $global_post['categorytitle'] }}</b>
                                                <span>{{ html_entity_decode($global_post['title']) }}</span>
                                            </p>
                                            <img src="{{ route('resizeImage', ['i_url' => imageCheck($global_post['images']), 'w' => 377, 'h' => 250]) }}"
                                                alt="{{ html_entity_decode($global_post['title']) }}" />
                                        </a>
                                    </div>

                                    @php
                                        $lsay++;
                                        $pre_key = $key;
                                    @endphp
                                {{-- @endif --}}
                                @if ($lsay == 1)
                                    @break
                                @endif
                            @endforeach

                            <div class="spotlistesi">
                                @php $osay = 0; @endphp
                                @foreach ($global_posts as $key => $global_post)
                                    @if ($global_post['category_id'] == $block_category_id and $key > $pre_key)
                                        <div class="spot-list">
                                            <a href="{{ route('post', ['categoryslug' => $global_post['categoryslug'], 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}"
                                                title="{{ html_entity_decode($global_post['title']) }}">
                                                <img src="{{ route('resizeImage', ['i_url' => imageCheck($global_post['images']), 'w' => 140, 'h' => 92]) }}"
                                                    alt="{{ html_entity_decode($global_post['title']) }}" />
                                                <p><span>{{ html_entity_decode($global_post['title']) }}</span><b>{{ $global_post['categorytitle'] }}</b>
                                                </p>
                                            </a>
                                        </div>

                                        @php
                                            $osay++;
                                            $pre_key = $key;
                                        @endphp
                                    @endif
                                    @if ($osay == 3)
                                        @break
                                    @endif
                                @endforeach
                            </div>

                        </div>

                        <div class="solkutu">

                            @php $osay = 0; @endphp
                            @foreach ($global_posts as $key => $global_post)
                                @if ($global_post['category_id'] == $block_category_id and $key > $pre_key)
                                    <div class="spot-sabit spot-8">
                                        <a href="{{ route('post', ['categoryslug' => $global_post['categoryslug'], 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}"
                                            title="{{ html_entity_decode($global_post['title']) }}">
                                            <p><b>{{ $global_post['categorytitle'] }}</b><span>{{ html_entity_decode($global_post['title']) }}</span>
                                            </p>
                                            <img src="{{ route('resizeImage', ['i_url' => imageCheck($global_post['images']), 'w' => 377, 'h' => 250]) }}"
                                                alt="{{ html_entity_decode($global_post['title']) }}" />
                                        </a>
                                    </div>

                                    @php
                                        $osay++;
                                        $pre_key = $key;
                                    @endphp
                                @endif
                                @if ($osay == 1)
                                    @break
                                @endif
                            @endforeach

                            <div class="spotlistesi">
                                @php $osay = 0; @endphp
                                @foreach ($global_posts as $key => $global_post)
                                    @if ($global_post['category_id'] == $block_category_id and $key > $pre_key)
                                        <div class="spot-list">
                                            <a href="{{ route('post', ['categoryslug' => $global_post['categoryslug'], 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}"
                                                title="{{ html_entity_decode($global_post['title']) }}">
                                                <img src="{{ route('resizeImage', ['i_url' => imageCheck($global_post['images']), 'w' => 140, 'h' => 92]) }}"
                                                    alt="{{ html_entity_decode($global_post['title']) }}" />
                                                <p><span>{{ html_entity_decode($global_post['title']) }}</span><b>{{ $global_post['categorytitle'] }}</b>
                                                </p>
                                            </a>
                                        </div>

                                        @php
                                            $osay++;
                                            $pre_key = $key;
                                        @endphp
                                    @endif
                                    @if ($osay == 3)
                                        @break
                                    @endif
                                @endforeach
                            </div>

                        </div>

                        <div class="solkutu sagkutu">

                            @php $osay = 0; @endphp
                            @foreach ($global_posts as $key => $global_post)
                                @if ($global_post['category_id'] == $block_category_id and $key > $pre_key)
                                    <div class="spot-sabit spot-8">
                                        <a href="{{ route('post', ['categoryslug' => $global_post['categoryslug'], 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}"
                                            title="{{ html_entity_decode($global_post['title']) }}">
                                            <p><b>{{ $global_post['categorytitle'] }}</b><span>{{ html_entity_decode($global_post['title']) }}</span>
                                            </p>
                                            <img src="{{ route('resizeImage', ['i_url' => imageCheck($global_post['images']), 'w' => 377, 'h' => 250]) }}"
                                                alt="{{ html_entity_decode($global_post['title']) }}" />
                                        </a>
                                    </div>

                                    @php
                                        $osay++;
                                        $pre_key = $key;
                                    @endphp
                                @endif
                                @if ($osay == 1)
                                    @break
                                @endif
                            @endforeach

                            <div class="spotlistesi">
                                @php $osay = 0; @endphp
                                @foreach ($global_posts as $key => $global_post)
                                    @if ($global_post['category_id'] == $block_category_id and $key > $pre_key)
                                        <div class="spot-list">
                                            <a href="{{ route('post', ['categoryslug' => $global_post['categoryslug'], 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}"
                                                title="{{ html_entity_decode($global_post['title']) }}">
                                                <img src="{{ route('resizeImage', ['i_url' => imageCheck($global_post['images']), 'w' => 140, 'h' => 92]) }}"
                                                    alt="{{ html_entity_decode($global_post['title']) }}" />
                                                <p><span>{{ html_entity_decode($global_post['title']) }}</span><b>{{ $global_post['categorytitle'] }}</b>
                                                </p>
                                            </a>
                                        </div>

                                        @php
                                            $osay++;
                                            $pre_key = $key;
                                        @endphp
                                    @endif
                                    @if ($osay == 3)
                                        @break
                                    @endif
                                @endforeach
                            </div>

                        </div>

                    </div>
                </div>
            @else
                <div class="container desing_{{ $design }}">
                    <div class="row">
                        <!--@dump($block)-->
                        <div class="alert alert-warning"> Seçilen kategori bulunamadığı için ilgili tasarıma haber
                            getirilemedi. Panel > Anasayfa Sıralama altından kontrol ediniz.</div>
                    </div>
                </div>

            @endif
        @elseif($design == 1)
            <!-- EKONOMİ bölümü tasarımı Category News (Kategori) -->

            @if (categoryCheck($block_category_id) != false)

                <div class="container desing_{{ $design }}">
                    <div class="spotlar">

                        <div class="col-12">
                            <div class="news-headline-block justify-content-between mb-4">
                                <h2 class="text-black">
                                    {{ isset($block_category_id) ? categoryCheck($block_category_id)->title : 'Kategori ismi bulunamadı' }}
                                </h2>
                                <div class="headline-block-indicator">
                                    <div class="indicator-ball"
                                        style="background-color:{{ categoryCheck($block_category_id)->color }}!important;">
                                    </div>
                                </div>
                                <a href="{{ route('category', ['slug' => categoryCheck($block_category_id)->slug, 'id' => categoryCheck($block_category_id)->id]) }}"
                                    class="all-button externallink"
                                    title="{{ categoryCheck($block_category_id)->title }}">Tümü</a>
                            </div>
                        </div>

                        @foreach ($global_posts as $key => $global_post)
                            @if ($global_post['category_id'] == $block_category_id and $key >= 0)

                                <div class="spot-3 spot-3-{{ $csay }}">
                                    <a href="{{ route('post', ['categoryslug' => $global_post['categoryslug'], 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}"
                                        title="{{ html_entity_decode($global_post['title']) }}">
                                        <p><b>{{ $global_post['categorytitle'] }}</b><span>{{ html_entity_decode($global_post['title']) }}</span>
                                        </p>
                                        <img src="{{ route('resizeImage', ['i_url' => imageCheck($global_post['images']), 'w' => 577, 'h' => 400]) }}"
                                            alt="{{ html_entity_decode($global_post['title']) }}" />
                                    </a>
                                </div>

                                @if ($csay == 1)
                                    @break
                                @endif

                                @php $csay++; @endphp
                            @endif
                        @endforeach

                        @foreach ($global_posts as $key => $global_post)
                            @if ($global_post['category_id'] == $block_category_id and $key >= 0)

                                @if ($csay < 3)
                                @else
                                    <div class="spot-4 spot-4-{{ $c2say }}">
                                        <a href="{{ route('post', ['categoryslug' => $global_post['categoryslug'], 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}"
                                            title="{{ html_entity_decode($global_post['title']) }}">
                                            <p><b>{{ $global_post['categorytitle'] }}</b><span>{{ html_entity_decode($global_post['title']) }}</span>
                                            </p>
                                            <img src="{{ route('resizeImage', ['i_url' => imageCheck($global_post['images']), 'w' => 377, 'h' => 300]) }}"
                                                alt="{{ html_entity_decode($global_post['title']) }}" />
                                        </a>
                                    </div>
                                @endif

                                @if ($csay == 5)
                                    @break
                                @endif

                                @php
                                    $csay++;
                                    $c2say++;
                                @endphp
                            @endif
                        @endforeach

                    </div>
                </div>
            @else
                <div class="container desing_{{ $design }}">
                    <!--@dump($block)-->
                    <div class="row">
                        <div class="alert alert-warning">Seçilen kategori bulunamadığı için ilgili tasarıma haber
                            getirilemedi. Panel > Anasayfa Sıralama altından kontrol ediniz.</div>
                    </div>
                </div>

            @endif
        @elseif($design == 2)
            @if (categoryCheck($block_category_id) != false)

                <!-- GÜNDEM HABERLERİ TASARIMI Category News (Kategori) & Most Reading (En Çok Okunanalar)-->
                <div class="container desing_{{ $design }}">

                    <div class="row">

                        <div class="col-12">

                            <div class="news-headline-block justify-content-between mb-4">
                                <h2 class="text-black">
                                    {{ isset($block_category_id) ? categoryCheck($block_category_id)->title : 'Kategori ismi bulunamadı' }}
                                </h2>
                                <div class="headline-block-indicator">
                                    <div class="indicator-ball"
                                        style="background-color:{{ categoryCheck($block_category_id)->color }}!important;">
                                    </div>
                                </div>
                                <a href="{{ route('category', ['slug' => categoryCheck($block_category_id)->slug, 'id' => categoryCheck($block_category_id)->id]) }}"
                                    class="all-button externallink"
                                    title="{{ categoryCheck($block_category_id)->title }}">Tümü</a>
                            </div>

                            <div class="spotlar">

                                @php
                                    $pre_key = 0;
                                    $lsay = 0;
                                @endphp
                                @foreach ($global_posts as $key => $global_post)
                                    @if ($global_post['category_id'] == $block_category_id)
                                        <div class="spot-sabit spot-5 spot-5-{{ $lsay }}">
                                            <a href="{{ route('post', ['categoryslug' => $global_post['categoryslug'], 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}"
                                                title="{{ html_entity_decode($global_post['title']) }}">
                                                <p><b>{{ $global_post['categorytitle'] }}</b><span>{{ html_entity_decode($global_post['title']) }}</span>
                                                </p>
                                                <img src="{{ route('resizeImage', ['i_url' => imageCheck($global_post['images']), 'w' => 277, 'h' => 250]) }}"
                                                    alt="{{ html_entity_decode($global_post['title']) }}" />
                                            </a>
                                        </div>

                                        @php
                                            $lsay++;
                                            $pre_key = $key;
                                        @endphp
                                    @endif
                                    @if ($lsay == 4)
                                        @break
                                    @endif
                                @endforeach

                            </div>

                            <div class="sol">

                                @php $osay = 0; @endphp
                                @foreach ($global_posts as $key => $global_post)
                                    @if ($global_post['category_id'] == $block_category_id and $key > $pre_key)
                                        <div class="spot-sabit spot-6 spot-6-{{ $osay }}">
                                            <a href="{{ route('post', ['categoryslug' => $global_post['categoryslug'], 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}"
                                                title="{{ html_entity_decode($global_post['title']) }}">
                                                <p><b>{{ $global_post['categorytitle'] }}</b><span>{{ html_entity_decode($global_post['title']) }}</span>
                                                </p>
                                                <img src="{{ route('resizeImage', ['i_url' => imageCheck($global_post['images']), 'w' => 277, 'h' => 250]) }}"
                                                    alt="{{ html_entity_decode($global_post['title']) }}" />
                                            </a>
                                        </div>

                                        @php
                                            $osay++;
                                            $pre_key = $key;
                                        @endphp
                                    @endif
                                    @if ($osay == 2)
                                        @break
                                    @endif
                                @endforeach

                            </div>

                            <div class="sag">

                                <div class="sag1">

                                    @php $rsay = 0; @endphp
                                    @foreach ($global_posts as $key => $global_post)
                                        @if ($global_post['category_id'] == $block_category_id and $key > $pre_key)
                                            <div class="spot-sabit spot-7 spot-7-{{ $rsay }}">
                                                <a href="{{ route('post', ['categoryslug' => $global_post['categoryslug'], 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}"
                                                    title="{{ html_entity_decode($global_post['title']) }}">
                                                    <p><b>{{ $global_post['categorytitle'] }}</b><span>{{ html_entity_decode($global_post['title']) }}</span>
                                                    </p>
                                                    <img src="{{ route('resizeImage', ['i_url' => imageCheck($global_post['images']), 'w' => 580, 'h' => 530]) }}"
                                                        alt="{{ html_entity_decode($global_post['title']) }}" />
                                                </a>
                                            </div>

                                            @php
                                                $rsay++;
                                                $pre_key = $key;
                                            @endphp
                                        @endif
                                        @if ($rsay == 1)
                                            @break
                                        @endif
                                    @endforeach

                                </div>

                                <div class="sag2">

                                    @php $rsay = 0; @endphp
                                    @foreach ($global_posts as $key => $global_post)
                                        @if ($global_post['category_id'] == $block_category_id and $key > $pre_key)
                                            <div class="spot-sabit spot-6 spot-6-{{ $osay }}">
                                                <a href="{{ route('post', ['categoryslug' => $global_post['categoryslug'], 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}"
                                                    title="{{ html_entity_decode($global_post['title']) }}">
                                                    <p><b>{{ $global_post['categorytitle'] }}</b><span>{{ html_entity_decode($global_post['title']) }}</span>
                                                    </p>
                                                    <img src="{{ route('resizeImage', ['i_url' => imageCheck($global_post['images']), 'w' => 277, 'h' => 250]) }}"
                                                        alt="{{ html_entity_decode($global_post['title']) }}" />
                                                </a>
                                            </div>

                                            @php
                                                $rsay++;
                                                $pre_key = $key;
                                            @endphp
                                        @endif
                                        @if ($rsay == 2)
                                            @break
                                        @endif
                                    @endforeach

                                </div>

                            </div>

                            @if (false)

                                <div class="spotlar">
                                    <!-- (diğer haberler) -->
                                    @php $rsay = 0; @endphp
                                    @foreach ($global_posts as $key => $global_post)
                                        @if ($global_post['category_id'] == $block_category_id)
                                            <div class="spot-sabit spot-5 spot-5-{{ $rsay }}">
                                                <a href="{{ route('post', ['categoryslug' => $global_post['categoryslug'], 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}"
                                                    title="{{ html_entity_decode($global_post['title']) }}">
                                                    <p><b>{{ $global_post['categorytitle'] }}</b><span>{{ html_entity_decode($global_post['title']) }}</span>
                                                    </p>
                                                    <img src="{{ route('resizeImage', ['i_url' => imageCheck($global_post['images']), 'w' => 277, 'h' => 250]) }}"
                                                        alt="{{ html_entity_decode($global_post['title']) }}" />
                                                </a>
                                            </div>

                                            @php
                                                $rsay++;
                                                $pre_key = $key;
                                            @endphp
                                        @endif
                                        @if ($rsay == 4)
                                            @break
                                        @endif
                                    @endforeach
                                </div>

                            @endif

                        </div>

                    </div>

                </div>

                </div>
            @else
                <div class="container">
                    <!--@dump($block)-->
                    <div class="row">
                        <div class="alert alert-warning">Seçilen kategori bulunamadığı için ilgili tasarıma haber
                            getirilemedi. Panel > Anasayfa Sıralama altından kontrol ediniz.</div>
                    </div>
                </div>

            @endif
        @elseif($design == 3)
            <!-- Category News (Kategori) & Most Reading (En Çok Okunanalar)-->
            @if (categoryCheck($block_category_id) != false)

                <div class="container mb-5">
                    <div class="row">

                        <div class="col-12 col-lg-8">
                            <div class="spotlar">

                                <div class="col-12">
                                    <div class="news-headline-block justify-content-between mb-4">
                                        <h2 class="text-black">
                                            {{ isset($block_category_id) ? categoryCheck($block_category_id)->title : 'Kategori ismi bulunamadı' }}
                                        </h2>
                                        <div class="headline-block-indicator">
                                            <div class="indicator-ball"
                                                style="background-color:{{ categoryCheck($block_category_id)->color }}!important;">
                                            </div>
                                        </div>
                                        <a href="{{ route('category', ['slug' => categoryCheck($block_category_id)->slug, 'id' => categoryCheck($block_category_id)->id]) }}"
                                            class="all-button externallink"
                                            title="{{ categoryCheck($block_category_id)->title }}">Tümü</a>
                                    </div>
                                </div>

                                @php
                                    $spre_key = 0;
                                    $ssay = 0;
                                @endphp
                                @foreach ($global_posts as $key => $global_post)
                                    @if ($global_post['category_id'] == $block_category_id)
                                        <div class="spot spotduz spotduz2 spotduz2-{{ $ssay }}">
                                            <a href="{{ route('post', ['categoryslug' => $global_post['categoryslug'], 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}"
                                                title="{{ html_entity_decode($global_post['title']) }}">
                                                {{-- <b>{{ categoryCheck($global_post['category_id'])->title }}</b> --}}
                                                <img src="{{ route('resizeImage', ['i_url' => imageCheck($global_post['images']), 'w' => 377, 'h' => 247]) }}"
                                                    alt="{{ html_entity_decode($global_post['title']) }}"
                                                    alt="{{ html_entity_decode($global_post['title']) }}" />
                                                <p><span>{{ html_entity_decode($global_post['title']) }}</span></p>
                                            </a>
                                        </div>

                                        @php
                                            $ssay++;
                                            $spre_key = $key;
                                        @endphp
                                    @endif
                                    @if ($ssay == 6)
                                        @break
                                    @endif
                                @endforeach

                            </div>
                        </div>

                        <div class="col-12 col-lg-4">
                            <div class="mostly-block overflow-hidden rounded-1 shadow-sm">

                                <h2 class="mostly-block-headline mb-4" style="background-color:#ef0000;">Diğer
                                    Haberler</h2>

                                <div class="container-fluid">

                                    @php
                                        $spre_key = 0;
                                        $ssay = 0;
                                    @endphp
                                    @foreach ($global_posts as $key => $global_post)
                                        @if ($global_post['category_id'] == $block_category_id)
                                            @if ($ssay < 6)
                                            @else
                                                <div class="tekli">
                                                    <a href="{{ route('post', ['categoryslug' => categoryCheck($global_post['category_id'])->slug, 'slug' => $global_post['slug'], 'id' => $global_post['id']]) }}"
                                                        title="{{ $global_post['title'] }}">
                                                        <i class="fa fa-dot-circle-o"></i>
                                                        <p>
                                                            <b>{{ date('H:i', strtotime($global_post['created_at'])) }}</b>
                                                            <span>{{ html_entity_decode($global_post['title']) }}</span>
                                                        </p>
                                                    </a>
                                                </div>
                                            @endif

                                            @php
                                                $ssay++;
                                                $spre_key = $key;
                                            @endphp
                                        @endif
                                        @if ($ssay == 10)
                                            @break
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @else
                <div class="container">
                    <div class="row">
                        <!--@dump($block)-->
                        <div class="alert alert-warning"> Seçilen kategori bulunamadığı için ilgili tasarıma haber
                            getirilemedi. Panel > Anasayfa Sıralama altından kontrol ediniz.</div>
                    </div>
                </div>

            @endif
        @else
            <div class="container">
                <div class="row">
                    <div class="alert alert-warning"> Gelen tasarım değeri karşılığında tasarım bulunamadı !</div>
                </div>
            </div>

        @endif
    @else
        <div class="container">
            <div class="row">
                <div class="alert alert-warning"> {{ $block_id }} numaralı dizayn tanımlı değil !</div>
            </div>
        </div>

    @endif
@else
    <div class="container">
        <div class="row">
            <div class="alert alert-warning"> "Standart Haber" olarak eklenmiş haber türü bulunamadığı için tasarım
                gösterilemiyor.</div>
        </div>
    </div>

@endif
