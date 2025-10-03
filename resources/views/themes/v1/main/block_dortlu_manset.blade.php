@if(\Illuminate\Support\Facades\Storage::disk('public')->exists('main/dortlu_manset.json'))
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="h-scroll" id="topheadline">
                    <div class="top-headline h-scroll-contain">
                        @php $dortlu_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/dortlu_manset.json'); @endphp
                        @foreach($dortlu_mansetler as $dortlu)
                            <div class="top-headline-block h-scroll-item mb-4">
                                <div class="card overflow-hidden position-relative">
                                    <a href="{{ route('post', ['categoryslug'=>$dortlu["categoryslug"],'slug'=>$dortlu["slug"],'id'=>$dortlu["id"]]) }}" class="bg-dark-gradiant d-block externallink" title="{{ html_entity_decode($dortlu["title"]) }}">
                                        <img width="100%" height="165" class="card-img-top lazy " src="{{ imageCheck($dortlu["images"]) }}" alt="{{ html_entity_decode($dortlu["title"]) }}">
                                    </a>
                                    <a href="{{ route('post', ['categoryslug'=>$dortlu["categoryslug"],'slug'=>$dortlu["slug"],'id'=>$dortlu["id"]]) }}" class="my-card-text">
                                        <span class="line-clamp-2"> {{html_entity_decode($dortlu["title"]) }}</span>
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
    <div class="container">
        <div class="row my-3">
            <div class="alert alert-warning">
                Dörtlü Manşet Bulunamadı
            </div>
        </div>
    </div>
@endif
<style>
    #topheadline :where(.card ,.card-img-top) {
        border-radius: 0 !important;
    }

    #topheadline .card {
        border: 1px solid #eee;
    }

    .my-card-text {
        padding: 0 10px;
        background-color: #fff;
        font-weight: bold;
        width: 100%;
        height: 80px;
        display: flex;
        align-items: center;
        color: #222;
    }

    .my-card-text:hover {
        text-decoration: underline;
    }

</style>