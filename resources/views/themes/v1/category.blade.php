@extends('themes.'.$theme.'.frontend_layout')

@section('meta')
    <title>{{ $category->title }} </title>
    <meta name="description" content="{{ $category->description }}">
    <meta name="keywords" content="{{ $category->keywords }}">

    <meta property="og:title" content="{{ $category->title }}" />
    <meta property="og:description" content="{{ $category->description }}" />
    <meta property="og:image" content="{{ imageCheck($category->images) }}" />
    <meta property="og:url" content="{{ route('category',['slug'=>$category->slug,'id'=>$category->id]) }}" />
    <meta property="og:type" content="category" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@if(isset($magicbox["tw_name"])) {{$magicbox["tw_name"]}} @endif" />
    <meta name="twitter:title" content="{{ $category->title }}" />
    <meta name="twitter:description" content="{{ $category->description }}" />
    <meta name="twitter:image" content="{{ imageCheck($category->images) }}" />
@endsection

@section('content')

    <!-- Headline (Ana Manşet) -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="category-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                    <h2 class="text-black">{{ $category->title }} Haberleri</h2>
                    <div class="headline-block-indicator"><div class="indicator-ball" style="background-color:{{ $category->color }}!important;"></div></div>
                </div>
            </div>
            <div class="col-12 col-md-8"><!-- (Kategori Manşet) -->
                <div class="headline-block bg-white overflow-hidden rounded-1 shadow-sm mb-4">

                    <div id="categoryHeadlineCarousel" class="carousel" data-bs-ride="carousel"  data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($posts_slider as $key => $post)
                                <a href="{{ route('post', ['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }}" class="externallink" title="{{ html_entity_decode($post->title) }}">
                                    <div class="carousel-item @if($key==0) active @endif">
                                        <div class="headline-item">
                                            <div class="headline-image">
                                                <img width="100%" height="510" src="{{ imageCheck($post->images) }}" class="lazy mobilserbest">
                                            </div>
                                            <div class="category-headline-title">
                                                @if($post->show_title_slide==0)
                                                    @if(isset($magicbox["mansetbaslik"]) and $magicbox["mansetbaslik"]==0)
                                                        <h1 class="text-uppercase text-truncate-line-2">{{ html_entity_decode($post->title) }}</h1>
                                                    @endif
                                                @endif
                                                @if($magicbox["mansetaciklama"]==0)

                                                    <p class="text-truncate-line-2 mt-3">{{ isset($post["description"]) ?   \Illuminate\Support\Str::limit(html_entity_decode($post["description"]), 95) : "..." }} </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @if($key==14) @break @endif
                            @endforeach
                        </div>


                        <div class="carousel-indicators">
                            @foreach($posts_slider as $key => $post)
                                <button type="button" data-bs-target="#categoryHeadlineCarousel" data-bs-slide-to="{{$key}}" @if($key==0) class="active" aria-current="true" @endif>{{$key+1}}</button>
                                @if($key==14) @break @endif
                            @endforeach
                        </div>
                    </div>

                </div>
                <div class="row flex-lg-nowrap">
                    @foreach($posts_slider as $key => $post)
                        @if($key>=15)
                        <div class="col-6 col-lg-3 mb-4"><!-- (Haber Kartı) -->
                            <div class="card overflow-hidden rounded-1 border-0 shadow-sm">
                                <a href="{{ route('post', ['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }}" class="externallink" title="{{ html_entity_decode($post->title) }}">
                                    <img height="100" src="{{ imageCheck($post->images) }}" class="card-img-top rounded-0 lazy" alt="{{ html_entity_decode($post->title) }}"></a>
                                <div class="card-body">
                                    <a href="{{ route('post', ['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }}" class="externallink" title="{{ html_entity_decode($post->title) }}">
                                        <h5 class="card-title category-card-title text-truncate-line-2">{{ html_entity_decode($post->title) }}</h5>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-12 col-md-4"><!-- (En çok okunanlar) -->

                @if($category->show_category_ads==0)
                    @if(adsCheck($ads6->id) && adsCheck($ads6->id)->publish==0)
                        @if(adsCheck($ads6->id)->type==1)
                            <div class="ad-block">{!! adsCheck($ads6->id)->code !!}</div>
                        @else
                            <div class="ad-block">
                                <a href="{{ adsCheck($ads6->id)->url }}" title="Reklam {{$ads6->id}}" class="externallink">
                                    <img src="{{ asset('uploads/'.adsCheck($ads6->id)->images) }}" alt="Reklam {{$ads6->id}}" class="img-fluid lazy" data-type="{{ adsCheck($ads6->id)->type }}" height="{{ adsCheck($ads6->id)->height }}" width="{{ adsCheck($ads6->id)->width }}">
                                </a>
                            </div>
                        @endif
                    @endif
                @endif


                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="mostly-block overflow-hidden rounded-1 shadow-sm">
                            <h2 class="mostly-block-headline mb-4 text-uppercase">En Çok Okunanlar</h2>

                            <div class="container-fluid">
                                <div class="row">
                                    @if(count($hit_popups)>0)
                                        @foreach($hit_popups as $key=>$hit_popup)
                                            <div class="col-12">
                                                <div class="card mostly-card position-relative">
                                                    <div class="mostly-thumb">
                                                        <a href="{{ route('post', ['categoryslug'=>$hit_popup->category->slug,'slug'=>$hit_popup->slug,'id'=>$hit_popup->id]) }}" title="{{ html_entity_decode($hit_popup->title) }}" class="externallink">
                                                            <img src="{{ imageCheck($hit_popup->images) }}" class="w-100 rounded-1 lazy" alt="{{ html_entity_decode($hit_popup->title) }}">
                                                        </a>
                                                    </div>
                                                    <div class="card-body py-2">
                                                        <a href="{{ route('post', ['categoryslug'=>$hit_popup->category->slug,'slug'=>$hit_popup->slug,'id'=>$hit_popup->id]) }}" title="{{ html_entity_decode($hit_popup->title) }}" class="externallink">
                                                            <h5 class="mostly-title text-truncate-line-2">{{ html_entity_decode($hit_popup->title) }}</h5>
                                                        </a>
                                                        <p class="card-text mostly-desc mb-0">{{ date('d/m/Y', strtotime($hit_popup->created_at)) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($key==3) @break @endif

                                        @endforeach
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Banner 06 -->
    @if($category->show_category_ads==0)
        @if(adsCheck($ads7->id) && adsCheck($ads7->id)->publish==0)
            <div class="container mb-4">
                @if(adsCheck($ads7->id)->type==1)
                    <div class="ad-block">{!! adsCheck($ads7->id)->code !!}</div>
                @else
                    <div class="ad-block">
                        <a href="{{ adsCheck($ads7->id)->url }}" title="Reklam {{$ads7->id}}" class="externallink">
                            <img src="{{ asset('uploads/'.adsCheck($ads7->id)->images) }}" alt="Reklam {{$ads7->id}}" class="img-fluid lazy" data-type="{{ adsCheck($ads7->id)->type }}" height="{{ adsCheck($ads7->id)->height }}" width="{{ adsCheck($ads7->id)->width }}">
                        </a>
                    </div>
                @endif
            </div>
        @endif
    @endif

    <!-- News (Haberler) -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                    <h2 class="text-black">Haberler</h2>
                    <div class="headline-block-indicator"><div class="indicator-ball" style="background-color:#EC0000;"></div></div>
                </div>
            </div>

            @foreach($posts_other as $post)
                <div class="col-6 col-lg-3 mb-4"><!-- (Haber Kartı) -->
                    <div class="card overflow-hidden rounded-1 border-0 shadow-sm">
                        <a href="{{ route('post', ['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }}" title="{{ html_entity_decode($post->title) }}" class="externallink">
                            <img src="
                                {{ route('resizeImage', ["i_url" => imageCheck($post->images), "w" => 279, "h" => 185]) }}
                                " class="card-img-top rounded-0 lazy" alt="{{ html_entity_decode($post->title) }}">
                        </a>
                        <div class="card-body">
                            <a href="{{ route('post', ['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }}" title="{{ html_entity_decode($post->title) }}" class="externallink">
                                <h5 class="card-title news-card-title">{{ html_entity_decode($post->title) }}</h5>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        {{ $posts_other->links() }}
    </div>



@endsection
