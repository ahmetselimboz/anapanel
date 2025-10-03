@if(auth()->check() and (auth()->user()->status==1 || auth()->user()->status==2) )
    <div class="bg-dark text-white">
        <div class="container">
            <div class="row">
                <ul class="list-group list-group-horizontal list-unstyled justify-content-center">
                    @if($routename=="post")
                        <li class="mx-3 my-1"><a href="{{ route('post.edit', $post->id) }}" style="color: white;">DÜZENLE</a></li>
                    @elseif($routename=="page")
                        <li class="mx-3 my-1"><a href="{{ route('page.edit', $page->id) }}" style="color: white;">DÜZENLE</a></li>
                    @endif

                    <li class="mx-3 my-1"><a href="{{ route('secure.index') }}" style="color: white;">YÖNETİM PANELİ</a></li>
                    <li class="mx-3 my-1"><a href="{{ route('userlogout') }}" style="color: white;">GÜVENLİ ÇIKIŞ</a></li>
                </ul>
            </div>
        </div>
    </div>
@endif




@if($routename!="frontend.index")
    @if(\Illuminate\Support\Facades\Storage::disk('public')->exists('main/sortable_list.json'))
        @foreach(\Illuminate\Support\Facades\Storage::disk('public')->json('main/sortable_list.json') as $block)
            @if($block["type"]=="menu" and $block["design"]==0)
                @include($theme_path.'.main.block_main_menu', [ 'menu_id' => $block["menu"], 'design' => 0 ])
                @break
            @endif
        @endforeach
    @endif
@endif
