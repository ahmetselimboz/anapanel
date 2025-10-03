

@if(\Illuminate\Support\Facades\Storage::disk('public')->exists('main/dortlu_manset.json'))
<div class="container mobyok">
   <div class="spotlar">
      @php $dortlu_mansetler = \Illuminate\Support\Facades\Storage::disk('public')->json('main/dortlu_manset.json'); @endphp
      @foreach($dortlu_mansetler as $dortlu)
      <div class="spot">
         <a href="{{ route('post', ['categoryslug'=>$dortlu['categoryslug'],'slug'=>$dortlu['slug'],'id'=>$dortlu['id']]) }}" title="{{ html_entity_decode($dortlu['title']) }}">
            <!--<b>{{ $dortlu["categorytitle"] }}</b>-->
            <img src="{{ route('resizeImage', ['i_url' => 'uploads/'.$dortlu['images'], 'w' => 277, 'h' => 185]) }}" alt="{{ html_entity_decode($dortlu['title']) }}"
               onerror="this.onerror=null;this.src='/uploads/defaultimage.jpg'" />
            <p><span>{{html_entity_decode($dortlu["title"]) }}</span></p>
         </a>
      </div>
      @endforeach
   </div>
</div>
@else
<div class="container d-none">
   <div class="row my-3">
      <div class="alert alert-warning">
         Dörtlü Manşet Bulunamadı
      </div>
   </div>
</div>
@endif