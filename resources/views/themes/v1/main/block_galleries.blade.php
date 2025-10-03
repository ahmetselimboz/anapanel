@if(\Illuminate\Support\Facades\Storage::disk('public')->exists('main/photo_galleries.json'))
    @php $photo_galleries = \Illuminate\Support\Facades\Storage::disk('public')->json('main/photo_galleries.json'); @endphp
    <div class="bg-black py-4 mb-4">
        <div class="container div-photo-gallery-block">
            <div class="row">
                <div class="col-12">
                    <div class="news-headline-block justify-content-between mb-4"> <!--Block Başlık-->
                        <h2 class="text-white">Foto Galeri</h2>
                        <div class="headline-block-indicator"><div class="indicator-ball" style="background-color:#FFF200;"></div></div>
                        <a href="{{ route('photo_galleries') }}" class="all-button externallink">Tümü</a>
                    </div>
                </div>
                @foreach($photo_galleries as $pkey => $photo_gallery)
                    <div class=" @if($pkey==0) col-12 @else col-6 @endif col-lg-4">
                        <div class="photo-gallery-block-item mb-4">
                            <div class="photo-gallery-image position-relative">
                                <a href="{{ route('photo_gallery', ['categoryslug'=>$photo_gallery["categoryslug"],'slug'=>$photo_gallery["slug"],'id'=>$photo_gallery["id"]] ) }}" title="{{ $photo_gallery["title"] }}" class="externallink">
                                    <img src="
                                    {{ route('resizeImage', ["i_url" => imageCheck($photo_gallery["images"]), "w" => 379, "h" => 213]) }}
                                        " alt="{{ $photo_gallery["title"] }}" class="lazy rounded w-100 mx-auto d-block">
                                </a>
                                <i class="gallery-image-icon"></i>
                            </div>
                            <div class="photo-gallery-title">
                                <a href="{{ route('photo_gallery', ['categoryslug'=>$photo_gallery["categoryslug"],'slug'=>$photo_gallery["slug"],'id'=>$photo_gallery["id"]] ) }}" title="{{ $photo_gallery["title"] }}" class="externallink">
                                    <h1 class="text-white mb-0 text-truncate-line-2">{{ $photo_gallery["title"] }} </h1>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@else
    <div class="container d-none">
        <div class="row">
            <div class="alert alert-warning"> Foto Galeri Bulunamadı </div>
        </div>
    </div>
@endif
