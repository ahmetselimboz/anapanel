@extends('backend.layout')

@section('custom_css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
    <style>
        .card-img-top { height: 200px; object-fit: cover; border-radius: 4px; }
        .img-hover-zoom { overflow: hidden; border-radius: 8px; transition: transform 0.3s ease; }
        .img-hover-zoom img { transition: transform 0.3s ease; }
        .img-hover-zoom:hover img { transform: scale(1.1); }
        .image-wrapper { position: relative; width: 100%; padding-top: 75%; background-color: #f3f3f3; overflow: hidden; border-radius: 8px; }
        .image-wrapper img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; display: none; }
        .spinner-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; z-index: 1; }
        .pagination-wrapper { max-width: 100%; overflow-x: auto; padding: 10px 0; white-space: nowrap; }
    </style>
@endsection

@section('content')
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-12 pt-4">
                <div class="box">
                    <div class="box-header with-border">
                        <div class="row align-items-center">
                            <div class="col-md-2 mb-3 mb-md-0">
                                <h4 class="box-title m-0">Resim Galerisi</h4>
                            </div>
                            <div class="col-md-4">
                                <form method="GET" action="{{ route('image-gallery') }}">
                                    <div class="row g-2 align-items-center">
                                        <input type="hidden" name="path" value="{{ $path }}">
                                        <div class="col-sm-8">
                                            <input type="text" name="search" class="form-control" placeholder="Resim adına göre ara..." value="{{ request('search') }}">
                                        </div>
                                        <div class="col-sm-4 text-center text-md-start">
                                            <button type="submit" class="btn btn-primary py-1">Ara</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="box-body px-3">
                        {{-- Breadcrumb --}}
                        @if($path)
                            @php
                                $segments = explode('/', $path);
                                $breadcrumbPath = '';
                            @endphp
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('image-gallery') }}">uploads</a></li>
                                    @foreach($segments as $segment)
                                        @php
                                            $breadcrumbPath .= ($breadcrumbPath ? '/' : '') . $segment;
                                        @endphp
                                        @if(!$loop->last)
                                            <li class="breadcrumb-item">
                                                <a href="{{ route('image-gallery', ['path' => $breadcrumbPath]) }}">{{ $segment }}</a>
                                            </li>
                                        @else
                                            <li class="breadcrumb-item active" aria-current="page">{{ $segment }}</li>
                                        @endif
                                    @endforeach
                                </ol>
                            </nav>

                            {{-- Üst klasöre dön --}}
                            @php $parentPath = dirname($path); @endphp
                            <a href="{{ route('image-gallery', ['path' => $parentPath !== '.' ? $parentPath : null]) }}"
                               class="btn btn-outline-secondary mb-3">
                                <i class="fa fa-arrow-left"></i> Üst Klasöre Dön
                            </a>
                        @endif

                        {{-- Klasörler --}}
                        @if($folders->isNotEmpty())
                            <div class="row mb-4">
                                @foreach($folders as $folder)
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <div class="border p-3 rounded bg-light text-center">
                                            <a href="{{ route('image-gallery', ['path' => $folder]) }}" class="d-block text-dark text-decoration-none">
                                                <i class="fa fa-folder fa-2x mb-2"></i><br>
                                                {{ basename($folder) }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Resimler --}}
                        <div class="row mt-4">
                            @forelse($images as $image)
                                <div class="col-md-3 col-sm-6 mb-4">
                                    <div class="position-relative img-hover-zoom image-wrapper">
                                        <div class="spinner-overlay">
                                            <div class="spinner-border text-secondary" role="status" style="width: 2rem; height: 2rem;">
                                                <span class="visually-hidden">Yükleniyor...</span>
                                            </div>
                                        </div>

                                        <a href="{{ asset('uploads/' . ($path ? $path . '/' : '') . $image->getFilename()) }}"
                                           data-lightbox="gallery"
                                           data-title="{{ $image->getFilename() }}">
                                            <img src="{{ asset('uploads/' . ($path ? $path . '/' : '') . $image->getFilename()) }}"
                                                 alt="{{ $image->getFilename() }}"
                                                 class="img-fluid"
                                                 onload="this.style.display='block'; this.closest('.image-wrapper').querySelector('.spinner-overlay').style.display='none';">
                                        </a>

                                        <form method="POST" action="{{ route('gallery.destroy', $image->getFilename()) }}"
                                              style="position: absolute; top: 5px; right: 5px;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Bu görseli silmek istediğinize emin misiniz?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="small text-muted text-center mt-1 text-truncate">{{ $image->getFilename() }}</p>
                                </div>
                            @empty
                                <p class="text-muted ms-4">Uygun görsel bulunamadı.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sayfalama --}}
        <div class="pagination-wrapper overflow-auto text-center">
            <div class="d-inline-block">
                {{ $images->links() }}
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endsection
