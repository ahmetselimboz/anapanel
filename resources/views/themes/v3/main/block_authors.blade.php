@if($magicbox["author_status"]==0)

@if(\Illuminate\Support\Facades\Storage::disk('public')->exists('main/authors.json'))
@php $authors = \Illuminate\Support\Facades\Storage::disk('public')->json('main/authors.json'); @endphp



<div class="container mb-4">
    <div class="row">

        <div class="col-12">
            <div class="news-headline-block justify-content-between p-3" style="background: #0A74B7;">

                <h2 class="text-white">Yazarlar</h2>

                <div class="yazarok">
                    <div class="ok-prev" id="authorPrev"></div>
                    <div class="ok-next" id="authorNext"></div>
                    <a href="{{ route('authors') }}" class="all-button text-white">Tümü</a>
                </div>

            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="rounded-1 shadow-sm p-3" style="overflow:hidden; background:#FFF;">
                <div id="authors" class="yazarlar">

                    @foreach ($authors as $author)
                    @php
                    $article = $author['latest_article'];
                    if (blank($article)) {
                    continue;
                    }
                    @endphp
                    <div class="firma1">
                        <div class="firma1kapla">
                            <a href="{{ route('frontend_article', ['author'=>slug_format($author['name']),'slug' => $article['slug']]) }}" title="{{ $article['title'] }}">
                                <img src="{{ route('resizeImage', ['i_url' => $author['avatar'], 'w' => 100, 'h' => 100]) }}"
                                    alt="{{ $author['name'] }}" data-name="{{$author['avatar']}}" />
                                </a>
                                <p>
                                    <span>
                                        <i><a href="{{ route('frontend_article', ['author'=>slug_format($author['name']),'slug' => $article['slug']]) }}">{{ $article['title'] }}</a></i>
                                        <b><a href="{{ route('frontend_article', ['author'=>slug_format($author['name']),'slug' => $article['slug']]) }}" title="{{ $article['title'] }}">{{ $author["name"] }}</a></b>
                                    </span>
                                </p>
                                </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>

@else

<div class="container d-none">
    <div class="row">
        <div class="alert alert-warning"> Yazarlar Bulunamadı </div>
    </div>
</div>

@endif

@else

<div class="container d-none">
    <div class="row">
        <div class="alert alert-warning"> Yazarlar modülü kapalı </div>
    </div>
</div>

@endif
