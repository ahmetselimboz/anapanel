@if(\Illuminate\Support\Facades\Storage::disk('public')->exists('main/standart_haberler.json'))
    @php $global_posts = \Illuminate\Support\Facades\Storage::disk('public')->json('main/standart_haberler.json'); $csay=0; @endphp

    @if(isset($design))
        @if($design=="default")
            <!-- MANŞET ALTINDAKİ HABER BLOK TASARIMI News (Haberler) -->
            @if(categoryCheck($block_category_id)!=false)
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                                <h2 class="text-black">{{ isset($block_category_id) ? categoryCheck($block_category_id)->title : "Kategori ismi bulunamadı" }}</h2>
                                <div class="headline-block-indicator">
                                    <div class="indicator-ball" style="background-color:{{ categoryCheck($block_category_id)->color }}!important;"></div>
                                </div>
                                <a href="{{ route('category', ['slug'=>categoryCheck($block_category_id)->slug,'id'=>categoryCheck($block_category_id)->id]) }}" class="all-button externallink" title="{{ categoryCheck($block_category_id)->title }}">Tümü</a>
                            </div>
                        </div>
                        @foreach($global_posts as $key => $global_post)
                        @php
                            $link = "";
                            if(isset($global_post['redirect_link'])&& $global_post['redirect_link']!="" ) {
                                $link = $global_post['redirect_link'];
                            }else{
                                $link = route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]);
                            }
                        @endphp
                            @if($global_post["category_id"]==$block_category_id)
                                <div class="col-6 col-lg-3 mb-4">
                                    <div class="card overflow-hidden rounded-1 border-0 shadow-sm">
                                        <a href="{{ $link }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                            <img width="100%" height="160" src="{{ imageCheck($global_post["images"]) }}" alt="{{ html_entity_decode($global_post["title"]) }}" class="lazy card-img-top rounded-0 mobilserbest" style="height:160px !important;" >
                                        </a>
                                        <div class="card-body">
                                            <a href="{{ $link }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                <h5 class="card-title news-card-title">{{ \Illuminate\Support\Str::limit(html_entity_decode($global_post["title"]), 58) }}</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @php $csay++; @endphp
                            @endif
                                @if($limit!=null)
                                    @if($limit==$csay) @break @endif
                                @endif
                        @endforeach
                    </div>
                </div>
            @else
                <div class="container">
                    <div class="row">
                        <div class="alert alert-warning"> Seçilen kategori bulunamadığı için ilgili tasarıma haber getirilemedi. Panel > Anasayfa Sıralama altından kontrol ediniz. </div>
                    </div>
                </div>
            @endif
        @elseif($design==1)
            <!-- EKONOMİ bölümü tasarımı Category News (Kategori) -->
            @if(categoryCheck($block_category_id)!=false)
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="news-headline-block justify-content-between mb-4">
                                <h2 class="text-black">{{ isset($block_category_id) ? categoryCheck($block_category_id)->title : "Kategori ismi bulunamadı" }}</h2>
                                <div class="headline-block-indicator">
                                    <div class="indicator-ball" style="background-color:{{categoryCheck($block_category_id)->color}}!important;"></div>
                                </div>
                                <a href="{{ route('category', ['slug'=>categoryCheck($block_category_id)->slug,'id'=>categoryCheck($block_category_id)->id]) }}" class="all-button externallink" title="{{ categoryCheck($block_category_id)->title }}">Tümü</a>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="category-block">
                                <div class="container overflow-hidden rounded-1 mb-4" style="background-color:{{categoryCheck($block_category_id)->color}}!important;">
                                    <div class="row">
                                        @foreach($global_posts as $key => $global_post)
                                            @if($global_post["category_id"]==$block_category_id and $key==0)
                                                <div class="col-12 col-lg-7">
                                                    <div class="category-thumbnail">
                                                        <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                            <img src="{{ imageCheck($global_post["images"]) }}" alt="{{ html_entity_decode($global_post["title"]) }}" class="lazy w-100">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-5 position-relative d-none d-lg-block">
                                                    <div class="px-4 vertical-center">
                                                        <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                            <h1 class="horizontal-card-title text-white">{{ \Illuminate\Support\Str::limit(html_entity_decode($global_post["title"]), 58) }}</h1>
                                                            <p class="horizontal-card-desc text-white mb-0">{{ \Illuminate\Support\Str::limit(html_entity_decode($global_post["title"]), 178) }}</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-5 d-block d-lg-none">
                                                    <div class="p-4">
                                                        <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                            <h1 class="horizontal-card-title text-white">{{ \Illuminate\Support\Str::limit(html_entity_decode($global_post["title"]), 58) }}</h1>
                                                            <p class="horizontal-card-desc text-white mb-0">{{ \Illuminate\Support\Str::limit(html_entity_decode($global_post["title"]), 178) }}</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                @php $csay++; @endphp
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($global_posts as $key => $global_post)
                            @if($global_post["category_id"]==$block_category_id and $key>=1)
                                <div class="col-6 col-lg-3 mb-4">
                                    <div class="card overflow-hidden rounded-1 border-0 shadow-sm">
                                        <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                            <img src="{{ imageCheck($global_post["images"]) }}" alt="{{ html_entity_decode($global_post["title"]) }}" class="lazy card-img-top rounded-0">
                                        </a>
                                        <div class="card-body">
                                            <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                <h5 class="card-title news-card-title">{{ \Illuminate\Support\Str::limit(html_entity_decode($global_post["title"]), 58) }}</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @php $csay++; @endphp
                            @endif

                            @if($limit!=null)
                                @if(($limit)==$csay)
                                    @break
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                <div class="container">
                    <!--@dump($block)-->
                    <div class="row">
                        <div class="alert alert-warning"> Seçilen kategori bulunamadığı için ilgili tasarıma haber getirilemedi. Panel > Anasayfa Sıralama altından kontrol ediniz.</div>
                    </div>
                </div>
            @endif
        @elseif($design==2)
            @if(categoryCheck($block_category_id)!=false)
                <!-- GÜNDEM HABERLERİ TASARIMI Category News (Kategori) & Most Reading (En Çok Okunanalar)-->
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="news-headline-block justify-content-between mb-4">
                                <h2 class="text-black">{{ isset($block_category_id) ? categoryCheck($block_category_id)->title : "Kategori ismi bulunamadı" }}</h2>
                                <div class="headline-block-indicator">
                                    <div class="indicator-ball" style="background-color:{{categoryCheck($block_category_id)->color}}!important;"></div>
                                </div>
                                <a href="{{ route('category', ['slug'=>categoryCheck($block_category_id)->slug,'id'=>categoryCheck($block_category_id)->id]) }}" class="all-button externallink" title="{{ categoryCheck($block_category_id)->title }}">Tümü</a>
                            </div>
                            <div class="row">

                                <div class="col-12 col-md-4 col-lg-3"> <!-- (Left) -->
                                    @php $pre_key = 0; $lsay = 0; @endphp
                                    @foreach($global_posts as $key => $global_post)
                                        @if($global_post["category_id"]==$block_category_id)
                                            <div class="card overflow-hidden rounded-1 border-0 shadow-sm mb-4">
                                                <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                    <img src="{{ imageCheck($global_post["images"]) }}" alt="{{ html_entity_decode($global_post["title"]) }}" class="lazy card-img-top rounded-0">
                                                </a>
                                                <div class="card-body category-card-body py-3">
                                                    <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                        <h5 class="card-title category-card-title text-truncate-line-2">{{ \Illuminate\Support\Str::limit(html_entity_decode($global_post["title"]), 58) }}</h5>
                                                    </a>
                                                </div>
                                            </div>
                                            @php $lsay++; $pre_key=$key; @endphp
                                        @endif
                                        @if($lsay==2)
                                            @break
                                        @endif
                                    @endforeach
                                </div>

                                <div class="col-12 col-md-8 col-lg-6"><!-- (Middle) -->
                                    @php $osay = 0; @endphp
                                    @foreach($global_posts as $key => $global_post)
                                        @if($global_post["category_id"]==$block_category_id and $key>$pre_key)
                                            <div class="card overflow-hidden rounded-1 border-0 shadow-sm mb-4">
                                                <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                    <img src="{{ imageCheck($global_post["images"]) }}" alt="{{ html_entity_decode($global_post["title"]) }}" class="lazy card-img-top rounded-0">
                                                </a>
                                                <div class="card-body category-card-body">
                                                    <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                        <h1 class="card-title big-category-card-title text-truncate-line-2 mt-2">{{ \Illuminate\Support\Str::limit(html_entity_decode($global_post["title"]), 58) }}</h1>
                                                        <p class="card-text-mb-0 big-category-card-desc text-truncate-line-2 mb-0 mt-2">
                                                            {{ isset($global_post["description"]) ? $global_post["description"] : "..." }}
                                                        </p>
                                                    </a>
                                                </div>
                                            </div>
                                            @php $osay++; $pre_key=$key; @endphp
                                        @endif
                                        @if($osay==1)
                                            @break
                                        @endif
                                    @endforeach
                                </div>

                                <div class="col-12 col-md-4 col-lg-3"> <!-- (Right) -->
                                    @php $lsay = 0; @endphp
                                    @foreach($global_posts as $key => $global_post)
                                        @if($global_post["category_id"]==$block_category_id and $key>$pre_key)
                                            <div class="card overflow-hidden rounded-1 border-0 shadow-sm mb-4">
                                                <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                    <img src="{{ imageCheck($global_post["images"]) }}" alt="{{ html_entity_decode($global_post["title"]) }}" class="lazy card-img-top rounded-0">
                                                </a>
                                                <div class="card-body category-card-body py-3">
                                                    <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                        <h5 class="card-title category-card-title text-truncate-line-2">{{ \Illuminate\Support\Str::limit(html_entity_decode($global_post["title"]), 58) }}</h5>
                                                    </a>
                                                </div>
                                            </div>
                                            @php $lsay++; $pre_key=$key; @endphp
                                        @endif
                                        @if($lsay==2)
                                            @break
                                        @endif
                                    @endforeach
                                </div>

                                @if(false)
                                    <div class="col-12 col-lg-3"><!-- (Right) -->
                                        <!-- (diğer haberler) -->
                                        <div class="other-news mb-4">
                                            @php $rsay = 0; @endphp
                                            @foreach($global_posts as $key => $global_post)
                                                @if($global_post["category_id"]==$block_category_id and $key>$pre_key )
                                                    <div class="card border-0 m-0">
                                                        <div class="card-body">
                                                            <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                                <h5 class="card-title other-card-title text-truncate-line-3">{{ \Illuminate\Support\Str::limit(html_entity_decode($global_post["title"]), 58) }}</h5>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    @php $rsay++; $pre_key=$key; @endphp
                                                @endif
                                                @if($rsay==10)
                                                    @break
                                                @endif
                                            @endforeach
                                        </div>
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
                        <div class="alert alert-warning"> Seçilen kategori bulunamadığı için ilgili tasarıma haber getirilemedi. Panel > Anasayfa Sıralama altından kontrol ediniz.</div>
                    </div>
                </div>
            @endif
        @elseif($design==3)
            <!-- Category News (Kategori) & Most Reading (En Çok Okunanalar)-->
            @if(categoryCheck($block_category_id)!=false)
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <div class="row">
                                <div class="col-12">
                                    <div class="news-headline-block justify-content-between mb-4">
                                        <h2 class="text-black">{{ isset($block_category_id) ? categoryCheck($block_category_id)->title : "Kategori ismi bulunamadı" }}</h2>
                                        <div class="headline-block-indicator">
                                            <div class="indicator-ball" style="background-color:{{categoryCheck($block_category_id)->color}}!important;"></div>
                                        </div>
                                        <a href="{{ route('category', ['slug'=>categoryCheck($block_category_id)->slug,'id'=>categoryCheck($block_category_id)->id]) }}" class="all-button externallink" title="{{ categoryCheck($block_category_id)->title }}">Tümü</a>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="category-block">
                                        <div class="container overflow-hidden rounded-1 mb-4" style="background-color:{{categoryCheck($block_category_id)->color}}!important;">
                                            @php $spre_key = 0; $ssay = 0; @endphp
                                            @foreach($global_posts as $key => $global_post)
                                                @if($global_post["category_id"]==$block_category_id)
                                                    <div class="row">
                                                        <div class="col-12 col-lg-7">
                                                            <div class="category-thumbnail">
                                                                <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                                    <img src="{{ imageCheck($global_post["images"]) }}" alt="{{ html_entity_decode($global_post["title"]) }}" class="lazy w-100">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-lg-5 position-relative d-none d-lg-block">
                                                            <div class="px-4 vertical-center">
                                                                <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                                    <h1 class="horizontal-card-title text-white">
                                                                        {{ \Illuminate\Support\Str::limit(html_entity_decode($global_post["title"]), 58) }}
                                                                    </h1>
                                                                    <p class="horizontal-card-desc text-white mb-0">
                                                                        {!! \Illuminate\Support\Str::limit($global_post["description"], 158) !!}
                                                                    </p>
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 col-lg-5 position-relative d-block d-lg-none">
                                                            <div class="p-4">
                                                                <a href="#">
                                                                    <h1 class="horizontal-card-title text-white">
                                                                        {{ \Illuminate\Support\Str::limit(html_entity_decode($global_post["title"]), 58) }}
                                                                    </h1>
                                                                    <p class="horizontal-card-desc text-white mb-0">
                                                                        {!! \Illuminate\Support\Str::limit($global_post["description"], 158) !!}
                                                                    </p>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php $ssay++; $spre_key=$key; @endphp
                                                @endif
                                                @if($ssay==1)
                                                    @break
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                @foreach($global_posts as $key => $global_post)
                                    @if($global_post["category_id"]==$block_category_id and $key>$spre_key )
                                        <div class="col-6 col-lg-3 mb-4"><!-- (Haber Kartı) -->
                                            <div class="card overflow-hidden rounded-1 border-0 shadow-sm">
                                                <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                    <img src="{{ imageCheck($global_post["images"]) }}" alt="{{ html_entity_decode($global_post["title"]) }}" class="lazy card-img-top rounded-0">
                                                </a>
                                                <div class="card-body category-card-body">
                                                    <a href="{{ route('post', ['categoryslug'=>$global_post["categoryslug"],'slug'=>$global_post["slug"],'id'=>$global_post["id"]]) }}" class="externallink" title="{{ html_entity_decode($global_post["title"]) }}">
                                                        <h5 class="card-title category-card-title text-truncate-line-2">{{ \Illuminate\Support\Str::limit(html_entity_decode($global_post["title"]), 58) }}</h5>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @php $ssay++; @endphp
                                    @endif
                                    @if($limit!=null)
                                        @if($limit==$ssay)
                                            @break
                                        @endif
                                    @endif
                                @endforeach


                            </div>
                        </div>

                        <div class="col-12 col-lg-4">
                            <div class="mostly-block overflow-hidden rounded-1 shadow-sm">
                                <h2 class="mostly-block-headline mb-4 text-uppercase">En Çok Okunanlar</h2>

                                <div class="container-fluid">
                                    @if(\Illuminate\Support\Facades\Storage::disk('public')->exists('main/hit_news.json'))
                                        <div class="row">
                                            @foreach(\Illuminate\Support\Facades\Storage::disk('public')->json('main/hit_news.json') as $ekey => $hn_post)

                                                @if(categoryCheck($hn_post["category_id"])!=false)
                                                    <div class="col-12">
                                                        <div class="card mostly-card position-relative">
                                                            <div class="mostly-thumb">
                                                                <a href="{{ route('post', ['categoryslug'=> categoryCheck($hn_post["category_id"])->slug,'slug'=>$hn_post["slug"],'id'=>$hn_post["id"]]) }}" class="externallink" title="{{ $hn_post["title"] }}">
                                                                    <img src="{{ imageCheck($hn_post["images"]) }}" alt="{{ $hn_post["title"] }}" class="lazy w-100 rounded-1">
                                                                </a>
                                                            </div>
                                                            <div class="card-body py-2">
                                                                <a href="{{ route('post', ['categoryslug'=>categoryCheck($hn_post["category_id"])->slug,'slug'=>$hn_post["slug"],'id'=>$hn_post["id"]]) }}" class="externallink" title="{{ $hn_post["title"] }}">
                                                                    <h5 class="mostly-title text-truncate-line-2">{{ \Illuminate\Support\Str::limit($hn_post["title"], 58) }}</h5>
                                                                </a>
                                                                <p class="card-text mostly-desc mb-0">{{ date('d/m/Y', strtotime($hn_post["created_at"])) }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($ekey==4)
                                                    @break
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-warning"> Json dosyası bulunamadı veya anasayfa yapısı kurulamadı</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @else
                <div class="container">
                    <div class="row">
                        <!--@dump($block)-->
                        <div class="alert alert-warning"> Seçilen kategori bulunamadığı için ilgili tasarıma haber getirilemedi. Panel > Anasayfa Sıralama altından kontrol ediniz.</div>
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
            <div class="alert alert-warning"> "Standart Haber" olarak eklenmiş haber türü bulunamadığı için tasarım gösterilemiyor.</div>
        </div>
    </div>
@endif






