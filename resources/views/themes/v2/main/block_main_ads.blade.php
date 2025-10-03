@if (adsCheck($ads_id) && adsCheck($ads_id)->publish == 0)
    <div class="container mb-4">
        <div class="media-block">
            @if (adsCheck($ads_id)->type == 1)
                {!! adsCheck($ads_id)->code !!}
            @else
                <a href="{{ adsCheck($ads_id)->url }}" class="externallink" title="{{ $ads_id }}">
                    <img src="{{ asset(adsCheck($ads_id)->images) }}" alt="{{ $ads_id }}" class="img-fluid lazy"
                        data-type="{{ adsCheck($ads_id)->type }}" height="{{ adsCheck($ads_id)->height }}"
                        width="{{ adsCheck($ads_id)->width }}">
                </a>
            @endif
        </div>
    </div>
@endif
