@if($magicbox["author_status"]==0)
    @if(\Illuminate\Support\Facades\Storage::disk('public')->exists('main/authors.json'))
        @php $authors = \Illuminate\Support\Facades\Storage::disk('public')->json('main/authors.json'); @endphp

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="news-headline-block justify-content-between mb-4">
                        <h2 class="text-black">Yazarlar</h2>
                        <div class="headline-block-indicator"><div class="indicator-ball" style="background-color:#975E64;"></div></div>
                        <a href="{{ route('authors') }}" class="all-button">Tümü</a>
                    </div>
                </div>

                <div class="col-12">
                    <div class="h-scroll" id="authors">
                        <div class="top-headline h-scroll-contain">
                        @foreach ($authors as $author)
                            @php
                                $article = $author['latest_article'];
                                if (blank($article)) {
                                    continue;
                                }
                               // dd( $author);
                            @endphp
                                <div class="firma1">
                                    <div class="firma1kapla">
                                        <a href="{{ route('article', ['slug' => $article['slug'], 'id' => $article['id']]) }}" title="{{ $article['title'] }}">
                                            <img src="{{ route('resizeImage', ['i_url' => $author['avatar'], 'w' => 100, 'h' => 100]) }}"
                                                alt="{{ $author['name'] }}" />
                                        </a>
                                        <p>
                                            <span>
                                                <b><a href="{{ route('article', ['slug' => $article['slug'], 'id' => $article['id']]) }}"
                                                        title="{{ $article['title'] }}">{{ $author['name'] }}</a></b>
                                                <i>
                                                    <a href="{{ route('article', ['slug' => $article['slug'], 'id' => $article['id']]) }}"
                                                        title="{{ $article['title'] }}">{!! $article['title'] !!}</a>
                                                </i>
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


