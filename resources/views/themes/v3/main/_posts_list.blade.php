@php $counter = 1; @endphp

@foreach ($posts_other as $post)
    <div class="spot spotduz spotduz-{{ $counter }}">
        <a href="{{ route('post', ['categoryslug' => categoryCheck($post['category_id'])->slug, 'slug' => $post['slug'], 'id' => $post['id']]) }}"
            title="{{ $post['title'] }}">
            <b>{{ categoryCheck($post['category_id'])->title }}</b>
            <div class="spot-resim">
                <img src="{{ route('resizeImage', ['i_url' => imageCheck($post['images']), 'w' => 550, 'h' => 307]) }}"
                     alt="{{ html_entity_decode($post['title']) }}" />
            </div>
            <p><span>{{ html_entity_decode($post['title']) }}</span></p>
        </a>
    </div>
     @php $counter++; @endphp
@endforeach
