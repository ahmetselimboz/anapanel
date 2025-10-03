@extends('themes.'.$theme.'.frontend_layout')

@section('meta')
    <title>Arama sayfası</title>
    <meta name="description" content="Arama sayfası">
    <style>
        .card-img-field{
            height:159px;
            overflow:hidden;
        }

        @media (max-width: 768px) {
            .card-img-field{
                height:97px;

            }
        }

    </style>
@endsection


@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                <h2 class="text-black">Haberler</h2>
            </div>
        </div>

        @forelse($posts as $post)
        <div class="col-6 col-lg-3 mb-4"><!-- (Haber Kartı) -->
            <div class="card overflow-hidden rounded-1 border-0 shadow-sm">
                <a href="{{ route('post', ['categoryslug'=>$post->category->title,'slug'=>$post->slug,'id'=>$post->id]) }}" title="{{ $post->title }}" class="externallink card-img-field">
                    <img src="{{ imageCheck($post->images) }}" class="card-img-top rounded-0 lazy" alt="{{ $post->title }}">
                </a>
                <div class="card-body">
                    <a href="{{ route('post', ['categoryslug'=>$post->category->title,'slug'=>$post->slug,'id'=>$post->id]) }}" title="{{ $post->title }}" class="externallink">
                        <h5 class="card-title news-card-title">{{ $post->title }}</h5>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center my-5">
            <h5 class="text-muted">Aradığınız kriterlere uygun haber bulunamadı</h5>
        </div>
    @endforelse


    </div>
</div>

{{ $posts->links() }}

@endsection
