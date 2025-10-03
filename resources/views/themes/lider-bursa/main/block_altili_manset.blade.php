
<style>
    #homenewslider {
        overflow: hidden;
        position: relative;
    }

    #homenewslider img {
        width: 100%;
        max-width: 100%;
    }

    #homenewslider .slick-slide {
        float: left;
        display: block;
    }

    #homenewslider .slick-dots {
        position: absolute;
        background: #54545447;
        flex-direction: column;
        border-radius: 1.5rem;
        margin: 0;
        padding: 10px;
        left: 15px;
        right: unset;
        bottom: unset;
        top: 50%;
        transform: translateY(-50%);
        list-style: none;
    }

    #homenewslider .slick-dots li+li {
        margin-top: 10px;
    }

    #homenewslider .slick-dots li button {
        background-color: transparent;
        border: 0;
        outline: 0;
        color: #fff;
        width: 30px;
        height: 30px;
        border-radius: 50%;
    }

    #homenewslider .slick-dots li.slick-active button {
        background-color: #2a3e81;
    }

    #homenewsliderindicators [data-bs-target] {
        text-indent: 0;
    }

    #homenewslider2 {
        overflow: hidden;
        position: relative;
    }

    #homenewslider2 img {
        width: 100%;
        max-width: 100%;
    }

    #homenewslider2 .slick-slide {
        float: left;
        display: block;
    }

    #homenewslider2 .slick-dots {
        position: absolute;
        background: #54545447;
        flex-direction: column;
        border-radius: 1.5rem;
        margin: 0;
        padding: 10px;
        left: 15px;
        right: unset;
        bottom: unset;
        top: 50%;
        transform: translateY(-50%);
        list-style: none;
    }

    #homenewslider2 .slick-dots li+li {
        margin-top: 10px;
    }

    #homenewslider2 .slick-dots li button {
        background-color: transparent;
        border: 0;
        outline: 0;
        color: #fff;
        width: 30px;
        height: 30px;
        border-radius: 50%;
    }

    #homenewslider2 .slick-dots li.slick-active button {
        background-color: #2a3e81;
    }

    #homenewslider2indicators [data-bs-target] {
        text-indent: 0;
    }

    @media(max-width: 768px) {
        #homenewslider .slick-dots {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            flex-direction: row;
            bottom: 10px;
            top: unset;
            transform: translateY(0) translateX(-50%);
            left: 50%;
            padding: 5px 10px;
        }

        #homenewslider .slick-dots li button {
            width: 20px;
            height: 20px;
            font-size: 100%;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #homenewslider img {
            min-height: 120px;
            object-fit: cover;
            object-position: center center;
        }

        #homenewslider .slick-dots li+li {
            margin: 0;
        }

        #homenewslider2 .slick-dots {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            flex-direction: row;
            bottom: 10px;
            top: unset;
            transform: translateY(0) translateX(-50%);
            left: 50%;
            padding: 5px 10px;
        }

        #homenewslider2 .slick-dots li button {
            width: 20px;
            height: 20px;
            font-size: 100%;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #homenewslider2 img {
            min-height: 120px;
            object-fit: cover;
            object-position: center center;
        }

        #homenewslider2 .slick-dots li+li {
            margin: 0;
        }
    }
    .badge-custom-main {
        position: absolute;
        background-color: yellow;
        color: black;
        font-size: 15px;
        font-weight: bold;
        padding: 3px 6px;
        z-index: 9999;
        top: 13px;
        left: 14px;
   }
</style>


@if (!blank(config('official_advert')))

<div class="container mobyok ">
   <div class="spotlar">
      @php $official_adverts = config('official_advert'); @endphp
      @foreach($official_adverts as $official)
            <div class="spot-2">
            <a href="{{route('home.offficial_advert_detail',$official['id'])}}" title="{{html_entity_decode($official['title']) }}">
                    <div class="w-100 overflow-hidden" style="height: 276px">
                    <span class="badge-custom-main">RESMİ İLANDIR</span>

                          <img src="{{ route('resizeImage', ['i_url' => $official['images'] ,'w' => 217, 'h' => 250]) }}" alt="{{html_entity_decode($official['title']) }}"
                          onerror="this.onerror=null;this.src='/uploads/defaultimage.jpg'" width="217" height="250">
                    </div>
                    <p><span>{{html_entity_decode($official['title']) }}</span></p>
                    </a>
            </div>

      @endforeach
   </div>
</div>

<div class="container mobvar ">
      @php $official_adverts = config('official_advert'); @endphp
      <div class="spotlar"  style="margin: 0px 0px 5px 0px;"> @php	$counter = 1;	@endphp
      @foreach($official_adverts as $officialKey => $official)

            <div class="spot spotduz3 spotduz3-{{ $counter }}">
                    <a href="{{route('home.offficial_advert_detail',$official['id'])}}"
                    title="{{html_entity_decode($official['title']) }}">
                    <span class="badge-custom-main">RESMİ İLANDIR</span>
                    <img src="{{ route('resizeImage', ['i_url' => $official['images'] ,  'w' => 380, 'h' => 253]) }}" alt="{{html_entity_decode($official['title']) }}"
                        onerror="this.onerror=null;this.src='/uploads/defaultimage.jpg'" width="380" height="253">
                        <p><span>{{html_entity_decode($official['title']) }}</span></p>
                    </a> </div>

        @endforeach
    </div>
</div>
@endif
@php $ana_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/ana_manset.json');

    $sonraki_haberler = array_slice($ana_mansetler, 15, 4);
@endphp
<div class="container mobyok">
    <div class="spotlar">
        @foreach ($sonraki_haberler as $manset_key => $ana_manset)
            <div class="spot-2"> <a href="{{ route('post', ['categoryslug' => $ana_manset['categoryslug'], 'slug' => $ana_manset['slug'], 'id' => $ana_manset['id']]) }}"
                    title="{{ html_entity_decode($ana_manset['title']) }}">
                    <div class="w-100 overflow-hidden" style="height: 276px">
                        <img src="{{ route('resizeImage', ['i_url' => $ana_manset['images'], 'w' => 217, 'h' => 250]) }}"
                        alt="{{ html_entity_decode($ana_manset['title']) }}"  style="height: auto"  onerror="this.onerror=null;this.src='/uploads/defaultimage.jpg'" width="217" height="250"/>
                    </div>

                    <p><span>{{ html_entity_decode($ana_manset['title']) }}</span></p>
                </a>
            </div>
        @endforeach
    </div>
</div>
<div class="container mobvar">
    <div class="spotlar"  style="margin: 0px 0px 5px 0px;"> @php	$counter = isset($officialKey) ? $officialKey +  1 : 1;	@endphp
        @foreach ($sonraki_haberler as $manset_key => $ana_manset)

            <div class="spot spotduz3 spotduz3-{{ $counter }}"> <a
                    href="{{ route('post', ['categoryslug' => $ana_manset['categoryslug'], 'slug' => $ana_manset['slug'], 'id' => $ana_manset['id']]) }}"
                    title="{{ html_entity_decode($ana_manset['title']) }}"> <img
                        src="{{ route('resizeImage', ['i_url' => $ana_manset['images'], 'w' => 380, 'h' => 253]) }}"
                        alt="{{ html_entity_decode($ana_manset['title']) }}"  onerror="this.onerror=null;this.src='/uploads/defaultimage.jpg'" width="380" height="253" />
                    <p><span>{{ html_entity_decode($ana_manset['title']) }}</span></p>
                </a> </div>

        @endforeach
    </div>
</div>
