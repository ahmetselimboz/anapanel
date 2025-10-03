@extends('themes.' . $theme . '.frontend_layout')

@section('meta')
    <title>Yazarlar</title>
    <meta name="description" content="{{ $settings['title'] }} yazar ekibi">
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="category-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                    <h2 class="text-black">Yazarlar</h2>
                    <div class="headline-block-indicator">
                        <div class="indicator-ball" style="background-color:#975E64;"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8"><!-- (Kategori Manşet) -->
                <div class="col-12 bg-gray rounded-1 mb-4">
                    <div class="container d-none">
                        <div class="row py-1">
                            <div class="col-12 col-md-6 py-2 pe-md-2">
                                <label
                                    class="form-label select-label position-relative overflow-hidden author-select rounded-1 author-archive w-100 m-0">
                                    <select class="form-select" id="archive" aria-label="Arşiv">
                                        <option selected>Arşiv</option>
                                        <option value="1">Sevilay Canseli</option>
                                        <option value="2">Mehmet Aydın</option>
                                        <option value="3">Gülseren Buğdayoğlu</option>
                                    </select>
                                </label>
                            </div>
                            <div class="col-12 col-md-6 py-2 ps-md-2">
                                @if (count($authors) > 0)
                                    <label
                                        class="form-label select-label position-relative overflow-hidden author-select rounded-1 author-curret-down w-100 m-0">
                                        <select class="form-select rounded-0" id="authors" aria-label="Yazar Seçiniz">
                                            <option>Seçilmedi</option>
                                            @foreach ($authors as $authoritem)
                                                <option value="{{ $authoritem->id }}">{{ $authoritem->name }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                @else
                                    <span class="alert alert-warning">Yazar listesi bulunamadı</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($authors as $author)
                        <div class="col-12 mb-4">
                            <div class="author-block overflow-hidden rounded-1 border-0">
                                <div class="author-block-article">
                                    <h5 class="text-truncate-line-3">
                                        <a href="{{ route('article', ['slug' => $author->article->slug, 'id' => $author->article->id]) }}"
                                            class="externallink text-dark"
                                            title="{{ $author->article->title }}">{{ $author->article->title }}</a>
                                    </h5>
                                    <p class="text-truncate-line-2"><a
                                            href="{{ route('article', ['slug' => $author->article->slug, 'id' => $author->article->id]) }}"
                                            class="externallink text-dark"
                                            title="{{ $author->article->title }}">{!! \Illuminate\Support\Str::limit(strip_tags($author->article->detail), 150, $end = '...') !!}</a></p>
                                </div>
                                <div class="author-block-footer d-flex">
                                    <a href="#" title="{{ $author->name }} tüm yazıları" class="externallink">
                                        <img src="{{ route('resizeImage', ['i_url' => $author->avatar, 'w' => 100, 'h' => 100]) }}" class="img-thumbnail lazy" alt="{{ $author->name }}">
                                        <div>
                                            <h6 class="mb-0 text-truncate">{{ $author->name }}</h6>
                                            {{-- <small  class="text-opacity-50 text-truncate">{{ date('d/m/Y', strtotime($author->article->created_at)) }}</small> --}}
                                            <a href="{{ route('article', ['slug' => $author->article->slug, 'id' => $author->article->id]) }}"
                                                class="all-article-link externallink"
                                                title="{{ $author->name }} tüm yazıları"><i class="curret-right"></i> Tüm
                                                Yazıları</a>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{ $authors->links() }}

            </div>
            <div class="col-12 col-md-4"><!-- (Öne Çıkan Haberler) -->

                @if (adsCheck($ads17->id) && adsCheck($ads17->id)->publish == 0)
                    @if (adsCheck($ads17->id)->type == 1)
                        <div class="ad-block">{!! adsCheck($ads17->id)->code !!}</div>
                    @else
                        <div class="ad-block">
                            <a href="{{ adsCheck($ads17->id)->url }}" title="Reklam {{ $ads17->id }}"
                                class="externallink">
                                <img src="{{ asset('uploads/' . adsCheck($ads17->id)->images) }}"
                                    alt="Reklam {{ $ads17->id }}" class="img-fluid lazy"
                                    data-type="{{ adsCheck($ads17->id)->type }}"
                                    height="{{ adsCheck($ads17->id)->height }}" width="{{ adsCheck($ads17->id)->width }}">
                            </a>
                        </div>
                    @endif
                @endif

                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="mostly-block overflow-hidden rounded-1 shadow-sm">
                            <h2 class="mostly-block-headline mb-4 text-uppercase">Öne Çıkan Haberler</h2>

                            <div class="container-fluid">
                                <div class="row">
                                    @if (count($hit_popups) > 0)
                                        @foreach ($hit_popups as $hit_popup)
                                            <div class="col-12">
                                                <div class="card mostly-card position-relative">
                                                    <div class="mostly-thumb">
                                                        <a href="{{ route('post', ['categoryslug' => $hit_popup->category->title, 'slug' => $hit_popup->slug, 'id' => $hit_popup->id]) }}"
                                                            title="{{ $hit_popup->title }}" class="externallink">
                                                            <img src="
                                                            {{ route('resizeImage', ['i_url' => imageCheck($hit_popup->images), 'w' => 120, 'h' => 80]) }}
                                                                "
                                                                class="w-100 rounded-1 lazy" alt="{{ $hit_popup->title }}">
                                                        </a>
                                                    </div>
                                                    <div class="card-body py-2">
                                                        <a href="{{ route('post', ['categoryslug' => $hit_popup->category->title, 'slug' => $hit_popup->slug, 'id' => $hit_popup->id]) }}"
                                                            title="{{ $hit_popup->title }}" class="externallink">
                                                            <h5 class="mostly-title text-truncate-line-2">
                                                                {{ $hit_popup->title }}</h5>
                                                        </a>
                                                        <p class="card-text mostly-desc mb-0">
                                                            {{ date('d/m/Y', strtotime($hit_popup->created_at)) }}</p>
                                                    </div>
                                                </div>
                                            </div>
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


<style>

    .detail-content, .detail-content p, .detail-content span {
        font-size: 18px !important;
        font-weight: 300 !important;
        font-family: 'Inter' !important;
    }

    .detail-content strong {
        font-size: 18px !important;
        font-weight: 600 !important;
    }

    .detail-content h1 {
        font-size: 25px !important;
        font-weight: 600 !important;
    }

    .detail-content h2 {
        font-size: 23px !important;
        font-weight: 600 !important;
    }

    .detail-content h3 {
        font-size: 21px !important;
        font-weight: 600 !important;
    }

    .detail-content h4 {
        font-size: 20px !important;
        font-weight: 600 !important;
    }

    .detail-content h5 {
        font-size: 20px !important;
        font-weight: 600 !important;
    }

    .detail-content h6 {
        font-size: 20px !important;
        font-weight: 600 !important;
    }

    </style>
@endsection
