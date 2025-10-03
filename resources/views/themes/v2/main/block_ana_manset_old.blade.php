<div class="container mobyok">
    <div class="row">
       @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/ana_manset.json'))
       <div class="col-12 col-lg-8 mb-4">
          <div class="headline-block overflow-hidden rounded-1">
             <div id="headlineCarousel" class="carousel" data-bs-ride="carousel" data-ride="carousel">
                <div class="carousel-inner">
                   @php $sayim = 0; @endphp
                   @php$ana_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json(
                   'main/ana_manset.json',
                   );
                   @endphp ?>
                   @foreach ($ana_mansetler as $manset_key => $ana_manset)
                   @if ($sayim > 14)
                   @break
                   @endif @php $sayim++; @endphp @if ($manset_key != $magicbox['mansetsabitreklamno'] - 1)
                   <a href="{{ route('post', ['categoryslug' => isset($ana_manset['categoryslug']) ? $ana_manset['categoryslug'] : 'kategorisiz', 'slug' => $ana_manset['slug'], 'id' => $ana_manset['id']]) }}"
                      class="externallink" title="{{ html_entity_decode($ana_manset['title']) }}">
                      <div class="carousel-item @if ($manset_key == 0) active @endif ">
                         <div class="headline-item">
                            <div class="headline-image"> <img width="100%"
                               src="{{ route('resizeImage', ['i_url' => imageCheck($ana_manset['images']), 'w' => 777, 'h' => 510]) }}"
                               alt="{{ html_entity_decode($ana_manset['title']) }}"
                               class="lazy"> </div>
                            <div class="headline-title bg-dark-gradiant">
                               @if (isset($ana_manset['show_title_slide']) && $ana_manset['show_title_slide'] == 0)
                               @if (isset($magicbox['mansetbaslik']) and $magicbox['mansetbaslik'] == 0)
                               <h1>{{ \Illuminate\Support\Str::limit(html_entity_decode($ana_manset['title']), 150) }}
                               </h1>
                               @endif
                               @endif
                            </div>
                         </div>
                      </div>
                   </a>
                   @else
                   @if (
                   $magicbox['mansetsabitreklamno'] != null and
                   $ads21 != null and
                   adsCheck($ads21->id) && adsCheck($ads21->id)->publish == 0)
                   @php $adsR = $manset_key; @endphp
                   @if (adsCheck($ads21->id)->type == 1)
                   {!! adsCheck($ads21->id)->code !!}
                   @else
                   <a href="{{ adsCheck($ads21->id)->url }}" class="externallink"
                      title="Reklam {{ $ads21->id }}">
                      <div
                         class="carousel-item @if ($manset_key == 0) active @endif ">
                         <div class="headline-item">
                            <div class="headline-image"> <img class="lazy"
                               src="{{ imageCheck(adsCheck($ads21->id)->images) }}"
                               alt="Reklam {{ $ads21->id }}"
                               data-type="{{ adsCheck($ads21->id)->type }}"
                               height="{{ adsCheck($ads21->id)->height }}"
                               width="{{ adsCheck($ads21->id)->width }}"> </div>
                         </div>
                      </div>
                   </a>
                   @endif
                   @else
                   <!-- Tekrar Manşet haberi çevir -->
                   <a href="{{ route('post', ['categoryslug' => isset($ana_manset['categoryslug']) ? $ana_manset['categoryslug'] : 'kategorisiz', 'slug' => $ana_manset['slug'], 'id' => $ana_manset['id']]) }}"
                      class="externallink"
                      title="{{ html_entity_decode($ana_manset['title']) }}">
                      <div
                         class="carousel-item @if ($manset_key == 0) active @endif ">
                         <div class="headline-item">
                            <div class="headline-image"> <img width="100%"
                               src="{{ route('resizeImage', ['i_url' => imageCheck($ana_manset['images']), 'w' => 777, 'h' => 510]) }}"
                               alt="{{ html_entity_decode($ana_manset['title']) }}"
                               class="lazy"> </div>
                            <div class="headline-title bg-dark-gradiant">
                               @if (isset($ana_manset['show_title_slide']) && $ana_manset['show_title_slide'] == 0)
                               @if (isset($magicbox['mansetbaslik']) and $magicbox['mansetbaslik'] == 0)
                               <h1>{{ \Illuminate\Support\Str::limit(html_entity_decode($ana_manset['title']), 150) }}
                               </h1>
                               @endif
                               @endif
                            </div>
                         </div>
                      </div>
                   </a>
                   @endif
                   @endif
                   @endforeach
                </div>
                <div class="carousel-indicators">
                   @php $sayim = 0; @endphp @foreach ($ana_mansetler as $manset_key_number => $ana_manset)
                   @if ($sayim > 14)
                   @break
                   @endif @php $sayim++; @endphp <button type="button"
                   data-bs-config="{'delay':0}" data-bs-target="#headlineCarousel"
                   data-bs-slide-to="{{ $manset_key_number }}"
                   @if ($manset_key_number == 0) class="active" aria-current="true" @endif>
                   @if (isset($adsR) and $adsR == $manset_key_number)
                   R
                   @else
                   {{ $manset_key_number + 1 }}
                   @endif
                   </button>
                   @endforeach
                   {{-- <a class="butontumu"
                      href="{{ route('frontend.index') }}/tum-mansetler">Tüm Manşetler</a> --}}
                </div>
             </div>
          </div>
       </div>
       @else
       <div class="alert alert-warning"> Ana Manşet Bulunamadı</div>
       @endif
       <div class="col-12 col-lg-4 mb-4">
          <div class="row">
             @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/mini_manset.json'))
             @php
             $mini_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/mini_manset.json'); @endphp
             <div class="col-12">
                @foreach ($mini_mansetler as $minimanset_key => $mini_manset)
                <div class="spot-sabit spot-8 mb-4">
                   <a
                      href="{{ route('post', ['categoryslug' => $mini_manset['categoryslug'], 'slug' => $mini_manset['slug'], 'id' => $mini_manset['id']]) }}"
                      title="{{ html_entity_decode($mini_manset['title']) }}">
                      <p><b>{{ $mini_manset['categorytitle'] }}</b><span>{{ html_entity_decode($mini_manset['title']) }}</span>
                      </p>
                      <img
                         src="{{ route('resizeImage', ['i_url' => imageCheck($mini_manset['images']), 'w' => 377, 'h' => 250]) }}"
                         alt="{{ html_entity_decode($mini_manset['title']) }}" />
                   </a>
                </div>
                @if ($minimanset_key == 1)
                @break
                @endif
                @endforeach
             </div>
             @else
             <div class="alert alert-warning"> Mini Manşet Bulunamadı</div>
             @endif
          </div>
       </div>
    </div>
 </div>
 <div class="container mobvar">
    <div class="spotlar">
       @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/ana_manset.json'))
       <div class="col-12 col-lg-8 mb-4">
          <div class="headline-block overflow-hidden rounded-1">
             <div id="headlineCarousel" class="carousel" data-bs-ride="carousel" data-ride="carousel">
                <div class="carousel-inner">
                   @php $sayim = 0; @endphp
                   @php
                   $ana_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/ana_manset.json');
                   $sonraki_haberler = array_slice($ana_mansetler, 15);
                   @endphp
                   @foreach ($ana_mansetler as $manset_key => $ana_manset)
                   @php $sayim++; @endphp
                   @if ($manset_key != $magicbox['mansetsabitreklamno'] - 1)
                   <a href="{{ route('post', ['categoryslug' => isset($ana_manset['categoryslug']) ? $ana_manset['categoryslug'] : 'kategorisiz', 'slug' => $ana_manset['slug'], 'id' => $ana_manset['id']]) }}"
                      class="externallink" title="{{ html_entity_decode($ana_manset['title']) }}">
                      <div class="carousel-item @if ($manset_key == 0) active @endif ">
                         <div class="headline-item">
                            <div class="headline-image"> <img width="100%"
                               src="{{ route('resizeImage', ['i_url' => imageCheck($ana_manset['images']), 'w' => 777, 'h' => 510]) }}"
                               alt="{{ html_entity_decode($ana_manset['title']) }}"
                               class="lazy"> </div>
                            <div class="headline-title bg-dark-gradiant">
                               @if (isset($ana_manset['show_title_slide']) && $ana_manset['show_title_slide'] == 0)
                               @if (isset($magicbox['mansetbaslik']) and $magicbox['mansetbaslik'] == 0)
                               <h1>{{ \Illuminate\Support\Str::limit(html_entity_decode($ana_manset['title']), 150) }}
                               </h1>
                               @endif
                               @endif
                            </div>
                         </div>
                      </div>
                   </a>
                   @else
                   @if (
                   $magicbox['mansetsabitreklamno'] != null and
                   $ads21 != null and
                   adsCheck($ads21->id) && adsCheck($ads21->id)->publish == 0)
                   @php $adsR = $manset_key; @endphp @if (adsCheck($ads21->id)->type == 1)
                   {!! adsCheck($ads21->id)->code !!}
                   @else
                   <a href="{{ adsCheck($ads21->id)->url }}" class="externallink"
                      title="Reklam {{ $ads21->id }}">
                      <div
                         class="carousel-item @if ($manset_key == 0) active @endif ">
                         <div class="headline-item">
                            <div class="headline-image"> <img class="lazy"
                               src="{{ imageCheck(adsCheck($ads21->id)->images) }}"
                               alt="Reklam {{ $ads21->id }}"
                               data-type="{{ adsCheck($ads21->id)->type }}"
                               height="{{ adsCheck($ads21->id)->height }}"
                               width="{{ adsCheck($ads21->id)->width }}"> </div>
                         </div>
                      </div>
                   </a>
                   @endif
                   @else
                   <!-- Tekrar Manşet haberi çevir -->
                   <a
                      href="{{ route('post', ['categoryslug' => isset($ana_manset['categoryslug']) ? $ana_manset['categoryslug'] : 'kategorisiz', 'slug' => $ana_manset['slug'], 'id' => $ana_manset['id']]) }}"
                      class="externallink"
                      title="{{ html_entity_decode($ana_manset['title']) }}">
                      <div
                         class="carousel-item @if ($manset_key == 0) active @endif ">
                         <div class="headline-item">
                            <div class="headline-image"> <img width="100%"
                               src="{{ route('resizeImage', ['i_url' => imageCheck($ana_manset['images']), 'w' => 777, 'h' => 510]) }}"
                               alt="{{ html_entity_decode($ana_manset['title']) }}"
                               class="lazy"> </div>
                            <div class="headline-title bg-dark-gradiant">
                               @if (isset($ana_manset['show_title_slide']) && $ana_manset['show_title_slide'] == 0)
                               @if (isset($magicbox['mansetbaslik']) and $magicbox['mansetbaslik'] == 0)
                               <h1>{{ \Illuminate\Support\Str::limit(html_entity_decode($ana_manset['title']), 150) }}
                               </h1>
                               @endif
                               @endif
                            </div>
                         </div>
                      </div>
                   </a>
                   @endif
                   @endif
                   @endforeach
                </div>
                <div class="carousel-indicators">
                   @php
                   $sayim = 0;
                   @endphp
                   @foreach ($ana_mansetler as $manset_key_number => $ana_manset)
                   @if ($sayim > 14)
                   @break
                   @endif @php $sayim++; @endphp
                   <button type="button" data-bs-config="{'delay':0}"
                   data-bs-target="#headlineCarousel" data-bs-slide-to="{{ $manset_key_number }}"
                   @if ($manset_key_number == 0) class="active" aria-current="true" @endif>
                   @if (isset($adsR) and $adsR == $manset_key_number)
                   R
                   @else
                   {{ $manset_key_number + 1 }}
                   @endif
                   </button>
                   @endforeach
                </div>
             </div>
          </div>
       </div>
       @else
       <div class="alert alert-warning"> Ana Manşet Bulunamadı</div>
       @endif
       <div class="tmz"></div>
       @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('main/mini_manset.json')) @php
       $mini_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/mini_manset.json'); @endphp
       <div class="col-12">
          <div class="yenilistele" id="yenilistele">
             <div class="yenilistekutu">
                @foreach ($mini_mansetler as $minimanset_key => $mini_manset)
                <div class="yeniliste">
                   <a
                      href="{{ route('post', ['categoryslug' => $mini_manset['categoryslug'], 'slug' => $mini_manset['slug'], 'id' => $mini_manset['id']]) }}"
                      title="{{ html_entity_decode($mini_manset['title']) }}">
                      <img
                         src="{{ route('resizeImage', ['i_url' => imageCheck($mini_manset['images']), 'w' => 140, 'h' => 100]) }}"
                         alt="{{ html_entity_decode($mini_manset['title']) }}" />
                      <p><span>{{ html_entity_decode($mini_manset['title']) }}</span></p>
                   </a>
                </div>
                @if ($minimanset_key == 3)
                @break
                @endif
                @endforeach
             </div>
             <div class="yenilistekutu">
                @foreach ($mini_mansetler as $minimanset_key => $mini_manset)
                @if ($minimanset_key < 4)
                @else
                <div class="yeniliste">
                   <a
                      href="{{ route('post', ['categoryslug' => $mini_manset['categoryslug'], 'slug' => $mini_manset['slug'], 'id' => $mini_manset['id']]) }}"
                      title="{{ html_entity_decode($mini_manset['title']) }}">
                      <img
                         src="{{ route('resizeImage', ['i_url' => imageCheck($mini_manset['images']), 'w' => 140, 'h' => 100]) }}"
                         alt="{{ html_entity_decode($mini_manset['title']) }}" />
                      <p><span>{{ html_entity_decode($mini_manset['title']) }}</span></p>
                   </a>
                </div>
                @endif @if ($minimanset_key == 7)
                @break
                @endif
                @endforeach
             </div>
             <div class="yenilistekutu">
                @foreach ($mini_mansetler as $minimanset_key => $mini_manset)
                @if ($minimanset_key < 8)
                @else
                <div class="yeniliste">
                   <a
                      href="{{ route('post', ['categoryslug' => $mini_manset['categoryslug'], 'slug' => $mini_manset['slug'], 'id' => $mini_manset['id']]) }}"
                      title="{{ html_entity_decode($mini_manset['title']) }}">
                      <img
                         src="{{ route('resizeImage', ['i_url' => imageCheck($mini_manset['images']), 'w' => 140, 'h' => 100]) }}"
                         alt="{{ html_entity_decode($mini_manset['title']) }}" />
                      <p><span>{{ html_entity_decode($mini_manset['title']) }}</span></p>
                   </a>
                </div>
                @endif @if ($minimanset_key == 11)
                @break
                @endif
                @endforeach
             </div>
          </div>
       </div>
       @endif
    </div>
 </div>
 <div class="tmz"></div>
