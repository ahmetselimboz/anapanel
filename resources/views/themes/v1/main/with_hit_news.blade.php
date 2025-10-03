@if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/hit_news.json'))

    <style>
        .trend-gallery-block .trend-gallery-block-inner-list {
            gap: 1rem;
            -moz-columns: 3;
            column-count: 3;
        }

        .trend-gallery-block-inner-list > div {
            height: 91px;
            padding: 6px 0;
        }

        .trend-gallery-block-inner-list > div > a {
            width: 100%;
            height: 100%;
            display: flex;
            position: relative;
        }

        .trend-gallery-block-inner-list p {
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            position: relative;
            padding: 0.875rem;
            display: flex;
            align-items: center;
            border: 1px solid rgb(234 236 241);
            border-right: none;
            margin-bottom: 0;
            font-weight: 700;
            color: #222;
        }

        .trend-gallery-block-inner-list .trend-inner-left-side {
            font-weight: 400;
            font-size: 24px;
            place-items: center;
            flex-shrink: 0;
            width: 2.75rem;
            display: grid;
            margin-top: -5px;
            z-index: 20;
            position: relative;
            box-shadow: 0 0 #0000, 0 0 #0000, 3px 0px 3px 0px #00000035;
            background-color: cadetblue;
            color: #fff;
            transition: 300ms;
        }

        .trend-gallery-block-inner-list .trend-inner-left-side:before {
            content: "";
            position: absolute;
            left: 0;
            top: 5px;
            border-right: 8px solid cadetblue;
            border-top: 5px solid transparent;
            transform: translateX(-100%) translateY(-100%);
            z-index: 40;
            transition-duration: inherit;
        }

        .trend-gallery-block-inner-list> div:hover .trend-inner-left-side {
            margin-top: -.5rem;
            background-color: rgb(255 12 12);
        }

        .trend-gallery-block-inner-list> div:hover .trend-inner-left-side:before {
            border-right-color: rgb(255 12 12);
            border-top-width: 8px;
            top: 8px;
        }

        .trend-gallery-block-inner-list> div:hover p {
            text-decoration: underline;
        }

        .trend-gallery-block-inner-list .trend-inner-left-side span {
            letter-spacing: .05em;
        }

        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        @media(max-width: 767px) {
            .trend-gallery-block-inner-list p{
                padding: .75rem;
            }
            .trend-gallery-block .trend-gallery-block-inner-list {
                -moz-columns: 1;
                column-count: 1;
            }
        }

    </style>

    @php $trend_news = collect(\Illuminate\Support\Facades\Storage::disk('public')->json('main/hit_news.json'));@endphp

    <div class="container">
        <div class="col-12">

            <div class="position-relative rounded-1">


                <div class="trend-gallery-block">

                    <h2 class="text-black">Trend Haberler</h2>
                    <div class="mb-4 mt-5">
                        <div class="trend-gallery-block-inner-list">
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($trend_news->take(15) as $trend)
                                <div>
                                    <a href="{{ route('post', ['categoryslug' => categoryCheck($trend['category_id'])->slug, 'slug' => $trend['slug'], 'id' => $trend['id']]) }}">
                                        <p>
                                           <span class="line-clamp-2">
                                               {!! $trend['title'] !!}
                                           </span>
                                        </p>
                                        <span class="trend-inner-left-side">
                                            <span>{{str_pad((int)$counter, 2, 0, STR_PAD_LEFT)}}</span>
                                        </span>
                                    </a>
                                </div>
                                @php
                                    $counter++;
                                @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="container">
        <div class="row">
            <div class="alert alert-warning"> Trend haberler bulunamadı</div>
        </div>
    </div>
@endif
