@extends('themes.' . $theme . '.frontend_layout')

@section('meta')
    <title>Kullanıcı Profili</title>
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">PROFİLİM</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $userprofile->name }}</li>
                    </ol>
                </nav>
            </div>

            <div class="col-12 col-lg-12">
                <div class="row"><!-- Yazar Kartı -->
                    <div class="col-12 mb-4">
                        <div class="author-detail-card overflow-hidden rounded-1 border-0">
                            <div class="author-detail-article">
                                <h5 class="text-truncate-line-2">{{ $userprofile->name }}</h5>
                            </div>
                            <div class="author-detail-footer d-flex">
                                <a href="#" title="yazar link">
                                    <img src="{{ imageCheck($userprofile->avatar) }}" class="img-thumbnail lazy"
                                        alt="{{ $userprofile->name }}">
                                    <div class="w-100 d-flex justify-content-between">
                                        <div>
                                            <small class="text-white text-opacity-50 text-truncate">Kayıt Tarihi:
                                                {{ date('d.m.Y - H:i', strtotime($userprofile->created_at)) }}</small>
                                            <a href="{{ route('userlogout') }}" class="btn btn-success btn-sm">Güvenli
                                                Çıkış</a>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="detail-content mb-4" id="detailContent">
                    @if ($userprofile->status == 3)
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Makale Gönder
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <strong>Hata!</strong> Aşağıdaki hataları düzeltin.<br><br>
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <form method="post" action="{{ route('articlesend') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="articleTitle" class="form-label">Başlık</label>
                                                <input type="text" class="form-control" id="articleTitle" value="{{old('articletitle')}}"
                                                    name="articletitle">
                                            </div>
                                            <div class="mb-3">
                                                <label for="articleDetail" class="form-label">İçerik</label>
                                                <textarea name="articledetail" id="articleDetail" class="form-control" rows="10"></textarea>
                                            </div>
                                            <div class="input-group mb-3">
                                                <input type="file" class="form-control" id="images2"  type="file"  name="images" accept=".jpg,.png,.jpeg,.webp" >
                                                <label class="input-group-text" for="images2"></label>
                                              </div>
                                            {{-- <div class="mb-3">
                                                <label for="images" class="form-label">Öne Çıkan Resim</label>
                                                <input type="file" id="images" class="file" name="images" accept=".jpg,.png,.jpeg,.webp" />
                                                </div> --}}
                                                <button type="submit" class="btn btn-primary">GÖNDER</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Makale Listem
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="list-unstyled">
                                            @foreach ($articles as $article)
                                                <li class="py-2">
                                                    @if ($article->publish == 0)
                                                        <a href="{{ route('article', ['slug' => $article->slug, 'id' => $article->id]) }}"
                                                            class="externallink">{{ $article->title }}</a>
                                                        <span class="btn btn-sm btn-success"
                                                            style="font-size: 8px;">ONAYLANDI</span>
                                                    @else
                                                        {{ $article->title }}
                                                        <span class="btn btn-sm btn-warning" style="font-size: 8px;">ONAY
                                                            BEKLİYOR</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <hr>
                    <div class="col-12">
                        <h3 class="mb-3"> Profil Detayları </h3>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Hata!</strong> Aşağıdaki hataları düzeltin.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form class="form" action="{{ route('userprofileupdate') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="box-body">
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Ad Soyad" name="name"
                                                value="{{ $userprofile->name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Email"
                                                value="{{ $userprofile->email }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="password" class="form-control" placeholder="Şifre"
                                                name="password">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Telefon"
                                                name="phone" value="{{ $userprofile->phone }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="file">
                                                <input type="file" class="form-control w-100" placeholder="Resim"
                                                    name="avatar">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="file">
                                                @if ($userprofile->avatar != null)
                                                    <a class="btn btn-primary btn-sm" data-bs-toggle="collapse"
                                                        href="#collapseExample" role="button" aria-expanded="false"
                                                        aria-controls="collapseExample">Mevcut Avatarı Göster</a>
                                                @endif
                                            </label>
                                            @if ($userprofile->avatar != null)
                                                <div class="form-group collapse" id="collapseExample">
                                                    <div class="form-group"><img
                                                            src="{{ imageCheck($userprofile->avatar) }}" alt=""
                                                            class="lazy"> </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <textarea rows="5" class="form-control" placeholder="Kişi öz bilgi" name="about">{{ $userprofile->about }}</textarea>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary"> <i class="ti-save-alt"></i> GÜNCELLE
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('custom_js')
    <script src="{{ asset('backend/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            CKEDITOR.replace('articleDetail', {
                //width: '70%',
                height: 500,
                filebrowserUploadUrl: "{{ route('home.ckeditorimageupload', ['_token' => csrf_token()]) }}",
                filebrowserUploadMethod: 'form',
                     removeButtons: 'PasteFromWord,Source',
                          toolbar: [{
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript',
                            '-', 'RemoveFormat'
                        ]
                    },
                    {
                        name: 'paragraph',
                        items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-',
                            'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight',
                            'JustifyBlock'
                        ]
                    },
                    {
                        name: 'links',
                        items: ['Link', 'Unlink']
                    },
                    {
                        name: 'insert',
                        items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar']
                    },
                    {
                        name: 'styles',
                        items: ['Styles', 'Format', 'Font', 'FontSize']
                    },
                    {
                        name: 'colors',
                        items: ['TextColor', 'BGColor']
                    },
                    {
                        name: 'tools',
                        items: ['Maximize']
                    }
                ]
            });
           CKEDITOR.config.allowedContent = true;
        });
    </script>
@endsection
