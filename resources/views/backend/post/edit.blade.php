@extends('backend.layout')

@section('custom_css')
<style>
    .bootstrap-tagsinput {
        width: 100%;
    }

    .error-border {
        border: 1px solid red;
    }

    .error-message {
        display: block;
        color: red;
        font-size: 12px;
        margin-top: 5px;
    }

    .image-preview {
        position: relative;
        border: 1px solid #ddd;
        padding: 5px;
        border-radius: 4px;
        background: #f8f9fa;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .image-preview img {
        display: block;
        max-width: 100%;
        height: auto;
    }

    .form-flex-buttons {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }

    #form_loader {
        background-color: rgba(255, 255, 255, .3);
        backdrop-filter: blur(3px);
        position: absolute;
        top: 0;
        left: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }

    #form_loader>div {
        width: 48px;
        height: 48px;
        display: inline-block;
        position: relative;
    }

    #form_loader>div::after,
    #form_loader>div::before {
        content: '';
        box-sizing: border-box;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #0D365A;
        position: absolute;
        left: 0;
        top: 0;
        animation: animloader 2s linear infinite;
    }

    #form_loader>div::after {
        animation-delay: 1s;
    }

    @keyframes animloader {
        0% {
            transform: scale(0);
            opacity: 1;
        }

        100% {
            transform: scale(1);
            opacity: 0;
        }
    }

    .tooltip-container {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .tooltip {
        visibility: hidden;
        background-color: #333;
        color: #fff;
        text-align: left;
        padding: 10px;
        border-radius: 6px;
        width: 300px;
        position: absolute;
        top: 35px;
        left: -60px;
        z-index: 10;
        opacity: 0;
        transition: opacity 0.3s, visibility 0.3s;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        font-size: 14px;
        line-height: 1.5;
    }

    .tooltip-container:hover .tooltip {
        visibility: visible;
        opacity: 1;
    }

    #pixelCalculator {
        font-family: Arial, sans-serif;
        font-size: 16px;
        white-space: nowrap;
        visibility: hidden;
        position: absolute;
    }
</style>
<link rel="stylesheet" href="{{ asset('backend/css/gorsel_editor.css') }}">
@endsection

@section('content')
@include('backend.post.gemini')
@include('backend.post.youtube_popup')
@include('backend.post.serp_popup')
@php $extra = json_decode($post->extra); @endphp
<section class="content">
    <div class="row">
        <div class="col-lg-12 col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Haberi Düzenle</h4>
                    <div class="box-controls pull-right">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary mx-1"
                                onclick="event.preventDefault(); document.getElementById('editPostForm').submit();"> <i
                                    class="ti-save-alt"></i> Kaydet </button>
                            <button onclick="window.location.href='{{ route('post.create') }}'" type="button"
                                class="btn btn-success btn-sm mx-1"><i class="fa fa-plus"></i> Haber Ekle</button>
                            <button onclick="window.location.href='{{ route('post.index') }}'" type="button"
                                class="btn btn-success btn-sm">
                                <iclass="fa fa-undo">
                                    </iclass=> Haber Listesine Dön
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
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
                <form class="form" id="editPostForm" action="{{ route('post.update', $post->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-10 row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Kategori</label>
                                        <div class="form-group">
                                            <select class="form-control select2" style="width: 100%;"
                                                name="category_id">
                                                @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @if ($category->id == $post->category_id) selected="selected" @endif>
                                                    {{ $category->title }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Hit <i class="fa fa-info-circle"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Sadece rakam girin"></i></label>
                                        <input type="number" min="0" class="form-control" placeholder="Rakam"
                                            name="hit" value="{{ $post->hit }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Kaynak <i class="fa fa-info-circle"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Haberin alındığı kaynak bilgisi"></i></label>
                                        <input type="text" class="form-control" placeholder="Haber kaynağı"
                                            name="news_source" value="{{ $extra->news_source ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Haberde Reklam?</label>
                                        <select class="form-control select2" style="width: 100%;" name="show_post_ads">
                                            <option value="0"
                                                @if (isset($extra->show_post_ads)) @if ($extra->show_post_ads == 0) selected @endif>@endif Gösterilsin
                                            </option>
                                            <option value="1"
                                                @if (isset($extra->show_post_ads)) @if ($extra->show_post_ads == 1) selected @endif>@endif Gösterilmesin
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Manşette Başlık?</label>
                                        <select class="form-control select2" style="width: 100%;"
                                            name="show_title_slide">
                                            <option value="0" @if ($post->show_title_slide == 0) selected @endif>
                                                Gösterilsin
                                            </option>
                                            <option value="1" @if ($post->show_title_slide == 1) selected @endif>
                                                Gösterilmesin
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Yayın Durumu</label>
                                        <select class="form-control select2" style="width: 100%;" name="publish">
                                            <option value="0" @if ($post->publish == 0) selected @endif>
                                                Aktif
                                            </option>
                                            <option value="1" @if ($post->publish == 1) selected @endif>
                                                Pasif
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Başlık</label>
                                            <div class="print_out_loud_wrapper">
                                                <input type="text" class="form-control" placeholder="Başlık"
                                                    name="title" value="{{ $post->title }}" id="title">

                                            </div>
                                            <div class="col-md-2 text-muted w-100 titleWidth">
                                                <span id="titleWidth">0px</span> / 580px
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2 col-md-1 px-0">
                                        <label class="form-label" style="height: 16px;"></label>
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown me-2">
                                                <!-- Masaüstü Görünüm -->
                                                <button
                                                    class="btn btn-secondary dropdown-toggle d-none d-md-inline-flex align-items-center rounded-3 px-2"
                                                    type="button" id="desktopOptions" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-h"></i>
                                                </button>

                                                <!-- Mobil Görünüm -->
                                                <button
                                                    class="btn btn-secondary dropdown-toggle d-inline-flex d-md-none rounded-3 p-2 "
                                                    type="button" id="mobileOptions" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-h"></i>
                                                </button>

                                                <!-- Dropdown Menüsü -->
                                                <ul class="dropdown-menu dropdown-menu-end"
                                                    aria-labelledby="desktopOptions">
                                                    <li>
                                                        <button
                                                            class="dropdown-item d-flex align-items-center title-dropdown-item"
                                                            type="button">
                                                            Baş Harfleri Büyüt
                                                        </button>
                                                    </li>

                                                    <li>
                                                        <button
                                                            class="dropdown-item d-flex align-items-center title-dropdown-item"
                                                            type="button">
                                                            Bütün Harfleri Büyüt
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button
                                                            class="dropdown-item d-flex align-items-center title-dropdown-item"
                                                            type="button">
                                                            Bütün Harfleri Küçült
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                            <button type="button" class="btn btn-warning py-1 px-2"
                                                data-bs-toggle="modal" data-bs-target="#serpModal">
                                                <i class="fa fa-google"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <div class="form-group">
                                            <label class="form-label">Etiketler</label>
                                            <input type="text" class="form-control"
                                                placeholder="Virgül ile ayırın" data-role="tagsinput" id="tags"
                                                name="keywords" value="{{ $post->keywords }}">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-2 ">
                                <label class="form-label"> Konum
                                    <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Haberin görüntüleceği konum"></i>
                                </label>
                                @php
                                $location = $post->locations?->pluck('location_id')->toArray() ?? [];
                                @endphp
                                <div class="form-check form-switch p-0">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="dortlu-manset" name="location[]" value="0"
                                        @checked(in_array(0, $location))>
                                    <label class="form-check-label" for="dortlu-manset">Üçlü/Dörtlü Manşet</label>
                                </div>
                                <div class="form-check form-switch p-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="main-maset"
                                        name="location[]" value="1" @checked(in_array(1, $location))>
                                    <label class="form-check-label" for="main-maset">Ana Manşet</label>
                                </div>
                                <div class="form-check form-switch p-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="mini-manset"
                                        name="location[]" value="2" @checked(in_array(2, $location))>
                                    <label class="form-check-label" for="mini-manset">Mini Manşet</label>
                                </div>
                                <div class="form-check form-switch p-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="son-dakika"
                                        name="location[]" value="4" @checked(in_array(4, $location))>
                                    <label class="form-check-label" for="son-dakika">Son Dakika</label>
                                </div>
                                {{-- <div class="form-check form-switch p-0">
                                        <input class="form-check-input" type="checkbox" role="switch" id="altili-manset" name="location[]" value="5" @checked(in_array(5, $location))>
                                        <label class="form-check-label" for="altili-manset">Altılı Manşet</label>
                                    </div> --}}


                                <input hidden value="3" name="location[]">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-10 col-md-10">
                                <div class="form-group">
                                    <label class="form-label">Açıklama <i class="fa fa-info-circle"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Arama sonuçları için özet olmalı"></i></label>
                                    <div class="print_out_loud_wrapper">
                                        <textarea id="description" cols="30" rows="2" class="form-control" placeholder="Açıklama"
                                            name="description" required>{{ $post->description }}</textarea>

                                    </div>

                                    <div class="col-md-2 text-muted  descriptionWidth" style="width: 100%;">
                                        <span id="descriptionWidth">0px</span> / 990px
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 col-md-2">
                                <label class="form-label"></label>
                                <div class="dropdown mb-4 mb-md-0 mt-md-3">
                                    <!-- Masaüstü Görünüm -->
                                    <button
                                        class="btn btn-secondary dropdown-toggle d-none d-md-inline-flex align-items-center py-1"
                                        type="button" id="desktopOptions" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa fa-ellipsis-h me-2"></i> Seçenekler
                                    </button>

                                    <!-- Mobil Görünüm -->
                                    <button
                                        class="btn btn-secondary dropdown-toggle d-inline-flex d-md-none rounded-3 p-2 mt-2"
                                        type="button" id="mobileOptions" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </button>

                                    <!-- Dropdown Menüsü -->
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="desktopOptions">
                                        <li>
                                            <button
                                                class="dropdown-item d-flex align-items-center description-dropdown-item"
                                                type="button">
                                                Baş Harfleri Büyüt
                                            </button>
                                        </li>

                                        <li>
                                            <button
                                                class="dropdown-item d-flex align-items-center description-dropdown-item"
                                                type="button">
                                                Bütün Harfleri Büyüt
                                            </button>
                                        </li>
                                        <li>
                                            <button
                                                class="dropdown-item d-flex align-items-center description-dropdown-item"
                                                type="button">
                                                Bütün Harfleri Küçült
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                        <span id="pixelCalculator"></span>
                        <div class="row">
                            <div class="col-md-12 d-flex align-items-center justify-content-center  mt-2">

                                <div class="mb-1 d-flex align-items-center justify-content-end">
                                    <b class="me-4" style="font-size: 12px;">
                                        İçeriği kopyalayarak "Bu içeriği SEO uyumlu hale getirir misin?" yazın ve
                                        güncellenen içeriği editöre tekrar yapıştırın.
                                    </b>
                                    @if (isset($gemini_api_key))
                                    <button class="btn btn-primary btn-sm ms-1" type="button"
                                        data-bs-toggle="modal" data-bs-target="#geminiChatModal">

                                        Gemini ile Özelleştir
                                    </button>
                                    @else
                                    <div class="tooltip-container">
                                        <button class="btn btn-outline-primary btn-sm ms-1 disabled"
                                            type="button" data-bs-toggle="modal"
                                            data-bs-target="#geminiChatModal" style="border:1px solid;">

                                            Gemini ile Özelleştir
                                        </button>
                                        <div class="tooltip">
                                            Bu özelliği kullanabilmek için
                                            <strong>"https://aistudio.google.com/"</strong>
                                            sitesinden aldığınız API Key'inizi <br>
                                            <strong>"Ayarlar > Analitik & Kod"</strong> sayfasındaki
                                            <strong>"Gemini API Key"</strong> alanına yerleştirmeniz gerekmektedir.
                                        </div>
                                    </div>
                                    @endif

                                    <a href="https://chatgpt.com/" target="_blank"
                                        class="btn btn-primary btn-sm ms-1">

                                        ChatGPT'ye Bağlan
                                    </a>
                                    <div class="print_out_loud_wrapper ms-3"
                                        style="min-width: calc(35px * 3);min-height: 36.5px;">
                                        <div class="print_out_loud_buttons show">
                                            <button type="button" class="print_out_loud_button polb__main">
                                                <i class="fa fa-microphone" aria-hidden="true"></i></button>
                                            <button type="button" class="print_out_loud_button polb__record"
                                                data-target="detail_editor">
                                                <i class="fa fa-play-circle" aria-hidden="true"></i></button>
                                            <button type="button" class="print_out_loud_button polb__devices">
                                                <i class="fa fa-caret-down" aria-hidden="true"></i></button>
                                        </div>
                                        <div class="print_out_loud_devices"><span>Mikrofon Bulunamadı !</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">

                                    <textarea id="detail_editor" class="form-control" placeholder="Açıklama" name="detail" rows="10"
                                        cols="30">{!! $post->detail !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"">
                                    <div class=" form-group">
                                <div class="form-group">
                                    <label class="form-label">Ana Resim <i class="fa fa-info-circle"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Haberin ana resmidir. Genişlik: 777 px Yükseklik: 510 px "></i>
                                    </label>
                                    <input type="file" class="form-control" placeholder="Resim"
                                        id="images" name="images" accept="image/png, image/gif, image/jpeg">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <div class="form-flex-buttons">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label class="form-label"> Görseli Tasarla
                                            <i class="fa fa-info-circle" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Yüklediğiniz görseli tasarlıyın. Genişlik: 777 px Yükseklik: 510 px "></i></label>
                                        <br>
                                        <button type="button" class="btn btn-primary btn-sm py-1 px-2 imageModal"
                                            data-method="create">
                                            Tasarla
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group" id="editExistImage">
                                    <div class="form-group">
                                        <label class="form-label"> Mevcut Görseli Tasarla
                                            <i class="fa fa-info-circle" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Mevcut görseli tasarlıyın. Genişlik: 777 px Yükseklik: 510 px "></i></label>
                                        <br>
                                        <button type="button" class="btn btn-primary btn-sm py-1 px-2 imageModal"
                                            data-method="edit">
                                            Düzenle
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (config('app.OLD_AUTHOR',1000) == 1004)

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="form-label">Mini Manşet ve Mobil Resim <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Haberin ana resmidir. Genişlik: 380 px Yükseklik: 430 px "></i>
                                    </label>
                                    <input type="file" class="form-control" placeholder="Resim" id="mini_images" name="mini_images" accept="image/png, image/gif, image/jpeg">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="form-label">Haber Detay Resmi (777x510) <i
                                            class="fa fa-info-circle" data-toggle="tooltip"
                                            data-placement="top"
                                            title="Haberin ana resmidir. Genişlik: 777 px Yükseklik: 510 px "></i>
                                    </label>
                                    <input type="file" class="form-control" placeholder="Resim" id="detail_images"
                                        name="detail_images" accept="image/png, image/gif, image/jpeg, image/webp">
                                </div>
                            </div>
                        </div>
                        @endif


                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="form-label d-block">Mevcut Ana Resim</label>
                                    @if (!blank($post->images))
                                    <img src="{{ asset('uploads/' . $post->images) }}" alt="" wwidth="300" height="200" id="currentImage">
                                    @else
                                    <img src="{{ asset('resimler/no_image.jpg') }}" alt="" width="300" height="200" id="currentImage">
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (config('app.OLD_AUTHOR',1000) == 1004)
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="form-label d-block">Mevcut Mini Manşet ve Mobil Resim</label>
                                    @if (isset($extra->mini_images) && !blank($extra->mini_images))
                                    <img src="{{ asset(isset($extra->mini_images)?'uploads/' . $extra->mini_images : '') }}" alt="" width="300" height="200">
                                    @else
                                    <img src="{{ asset('resimler/no_image.jpg') }}" alt="" width="300" height="200" id="currentImage">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-group">
                                <label class="form-label d-block">Mevcut Mini Manşet ve Mobil Resim</label>

                                @if (isset($extra->detail_images) && !blank($extra->detail_images))
                                <img src="{{ asset(isset($extra->detail_images)?'uploads/' . $extra->detail_images : '') }}" alt="" width="300" height="200">
                                @else
                                    <img src="{{ asset('resimler/no_image.jpg') }}" alt="" width="300" height="200" id="currentImage">
                                    @endif
                                </div>
                            </div>
                        </div>

                        @endif

                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="form-label">Dışarıya Yönlendirme Linki <i
                                            class="fa fa-info-circle" data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Habere giren ziyaretçiyi harici bir adrese yönlendirmek için kullanılır"></i></label>
                                    <input class="form-control" name="redirect_link" type="url"
                                        value="{{ $post->redirect_link }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Yayın Tarihi <i class="fa fa-info-circle"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="İleri tarihli değilse boş bırakın"></i></label>
                                <input class="form-control" type="datetime-local" name="datetime"
                                    value="{{ date('Y-m-d H:i', strtotime($post->created_at)) }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">İlişkili Haber<i class="fa fa-info-circle"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Haberin içinde görünecek ilişkili haberi seçebilirsiniz"></i></label>
                                <div class="form-group">
                                    <select class="form-control select2" style="width: 100%;" name="related_news"
                                        id="related_news">
                                        <option value="0">Seçilmedi</option>
                                        @if (isset($related_news))
                                        @foreach ($related_news as $related_new)
                                        <option value="{{ $related_new->id }}"
                                            @if (isset($extra->related_news) and $related_new->id == $extra->related_news) selected @endif>
                                            {!! $related_new->title !!}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">İlişkili Foto Galeri <i class="fa fa-info-circle"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Haberin içinde görünecek foto galeriyi seçebilirsiniz"></i></label>
                                        <div class="form-group">
                                            <select class="form-control select2" style="width: 100%;"
                                                name="related_photo" id="related_photo">
                                                <option value="0">Seçilmedi</option>
                                                @foreach ($related_photos as $related_photo)
                                                    <option value="{{ $related_photo->id }}"
                                        @if (isset($extra->related_photo)) @if ($related_photo->id == $extra->related_photo) selected @endif
                                        @endif >{{ $related_photo->title }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                            </div>
                        </div> --}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Yoruma Kapansın Mı? <i class="fa fa-info-circle"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="İstediğiniz haberi yorumlara kapalı konuma getirebilirsiniz"></i></label>
                                <select class="form-control select2" style="width: 100%;" name="comment_status"
                                    id="comment_status">
                                    <option value="0" @if ($extra->comment_status == 0) selected @endif>
                                        Hayır
                                    </option>
                                    <option value="1" @if ($extra->comment_status == 1) selected @endif>Evet
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">

                            <div class="form-group">
                                <label class="form-label">Video Embed Kodu <i class="fa fa-info-circle"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Haberle alakalı video embed kodu var ise ekleyebilirsiniz"></i></label>
                                <div class="row">
                                    <div class="col-8">
                                        <textarea cols="30" rows="1" class="form-control" placeholder="Embed kodu" name="video_embed"
                                            id="video_embed">{!! isset($extra->video_embed) ? $extra->video_embed : '' !!}</textarea>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-danger btn-sm py-1 px-2"
                                            data-bs-toggle="modal" data-bs-target="#youtubeModal">
                                            <i data-feather="youtube"></i>
                                            Video Ekle
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
    </div>


    <hr class="nomargin-bottom mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-label text-warning">Google'da Görünecek Başlık <i
                        class="fa fa-info-circle" data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Boş bırakılırsa standart başlığı görürsünüz"></i></label>
                @if (!empty($post->meta_title))
                <input type="text" class="form-control" placeholder="Başlık"
                    name="meta_title" id="meta_title" value="{{ $post->meta_title }}"
                    onkeyup="countTitle(this);" required>
                @else
                <input type="text" class="form-control" placeholder="Başlık"
                    name="meta_title" id="meta_title" value="{{ $post->title }}"
                    onkeyup="countTitle(this);" required>
                @endif
                <p id="charNumTitle"></p>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-label text-warning">Google'da Görünecek Açıklama <i
                        class="fa fa-info-circle" data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Boş bırakılırsa standart açıklamayı görürsünüz"></i></label>
                @if (!empty($post->meta_description))
                <textarea cols="30" rows="2" class="form-control" placeholder="Açıklama" name="meta_description"
                    id="meta_description" onkeyup="countDesc(this);" required>{{ $post->meta_description }}</textarea>
                @else
                <textarea cols="30" rows="2" class="form-control" placeholder="Açıklama" name="meta_description"
                    id="meta_description" onkeyup="countDesc(this);" required>{{ $post->description }}</textarea>
                @endif
                <p id="charNumDesc"></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-label text-warning">Haber arama sonuçlarında görünecek hali
                    <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Google algoritması değişmesi halinde görünüm google tarafından otomatik değiştirilebilir."></i></label>
                <div id="content" class="container">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="clearfix search-result">
                                <h4>
                                    <a href="#" id="meta_title_val">
                                        @if (empty($post->meta_title))
                                        {{ $post->title }}
                                        @else
                                        {{ $post->meta_title }}
                                        @endif
                                    </a>
                                </h4>
                                <small
                                    class="text-success">www.{{ $_SERVER['SERVER_NAME'] }}</small>
                                <p id="meta_description_val">
                                    @if (empty($post->meta_description))
                                    {{ $post->description }}
                                    @else
                                    {{ $post->meta_description }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <style>
                    .search-result {
                        padding: 20px 0;
                    }

                    .search-result h4 {
                        margin: 0;
                        line-height: 20px;
                    }

                    .search-result p {
                        font-size: 12px;
                        margin: 0;
                        padding: 0;
                    }
                </style>

            </div>
        </div>
    </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <button type="submit" class="btn btn-primary"><i class="ti-save-alt"></i> Kaydet</button>
    </div>
    </form>


    </div>
    <!-- /.box -->
    </div>
    </div>
    <div id="form_loader">
        <div></div>
    </div>
</section>
@endsection


@section('custom_js')
<script>
    const editor_upload_img_url = '{{ route('post.editor.upload') }}';
    const editor_delete_img_url = '{{ route('post.editor.delete') }}';
    const editor_list_img_url = '{{ route('post.editor.list') }}';
    const editor_originalsave_url = '{{ route('post.editor.originalsave') }}';
    const public_upload_url = '{{ asset('uploads/editor_original_images ') }}';
</script>

<script src="{{ asset('backend/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
<script src="{{ asset('backend/js/pages/advanced-form-element.js') }}"></script>
<script src="{{ asset('backend/assets/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.js') }}"></script>
<script src="{{ asset('backend/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('backend/js/gorsel_editor.js') }}"></script>
<script src="{{ asset('backend/js/print_out_loud.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#meta_title').keyup(function() {
            $('#meta_title_val').html($('#meta_title').val());
        });
        $('#meta_description').keyup(function() {
            $('#meta_description_val').html($('#meta_description').val());
        });
    });

    function countTitle(obj) {
        var maxLength = 70;
        strLength = obj.value.length;
        if (strLength > maxLength) {
            document.getElementById("charNumTitle").innerHTML =
                '<span style="color: red;">70 karakteri geçmemesi önerilir.</span>';
        } else {
            document.getElementById("charNumTitle").innerHTML =
                '<span style="color: green;">Minimum 50 karakter olması gerekir.</span>';
        }
    }

    function countDesc(obj) {
        var maxLength = 160;
        strLength = obj.value.length;
        if (strLength > maxLength) {
            document.getElementById("charNumDesc").innerHTML =
                '<span style="color: red;">160 karakteri geçmemesi önerilir.</span>';
        } else {
            document.getElementById("charNumDesc").innerHTML =
                '<span style="color: green;">Minimum 130 karakter olması gerekir.</span>';
        }
    }

    $(function() {
        "use strict";
        CKEDITOR.replace('detail_editor', {
            //width: '70%',
            height: 500,
            filebrowserUploadUrl: "{{ route('ckeditorimageupload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form',
            //removeButtons: 'PasteFromWord'
            // iFrame ve div gibi özel HTML içeriklerine izin ver
            extraAllowedContent: 'iframe[*]; div[*];',
            allowedContent: true
        });
        $('#form_loader').hide();

        const _editor = new GorselEditor;

        $('#tasarlaButton').click(function() {
            _editor.init('#tasarlaButton');
            _editor.start();
        });

        @if($post->editor_state)

        $('#mevcutduzenleButton').click(function() {
            const mevcutData = {!!$post->editor_state!!};
            _editor.init('#mevcutduzenleButton', 777, 510, mevcutData);
            _editor.start();
        });
        @endif

        $('#editPostForm').submit(async function(e) {
            $('#form_loader').show();
            const $_form = $(this);
            e.preventDefault();

            const editorState = await _editor.getResponse();
            if (editorState) {
                const stateJSON = JSON.stringify(editorState);

                const $hiddenInput = $('<input>', {
                    type: 'hidden',
                    name: 'editor_state',
                    value: stateJSON
                });

                $_form.append($hiddenInput);
            }

            $('#form_loader').hide();
            $_form[0].submit();
        });

    });

    document.addEventListener("DOMContentLoaded", function() {
        const dropdownItems = document.querySelectorAll(".title-dropdown-item");
        const dropdownItemsDesc = document.querySelectorAll(".description-dropdown-item");
        const titleInput = document.getElementById("title");
        const titleInputDesc = document.getElementById("description");

        dropdownItems.forEach(item => {
            item.addEventListener("click", function(e) {
                e.preventDefault(); // link davranışını engelle

                const action = this.textContent.trim();

                if (!titleInput) return;

                if (action === "Baş Harfleri Büyüt") {
                    titleInput.value = titleInput.value
                        .toLocaleLowerCase('tr-TR')
                        .split(" ")
                        .map(word => {
                            if (word.length === 0) return "";
                            return word[0].toLocaleUpperCase('tr-TR') + word.slice(1);
                        })
                        .join(" ");
                } else if (action === "Bütün Harfleri Büyüt") {
                    titleInput.value = titleInput.value.toLocaleUpperCase('tr-TR');
                } else if (action === "Bütün Harfleri Küçült") {
                    titleInput.value = titleInput.value.toLocaleLowerCase('tr-TR');
                }
            });
        });

        dropdownItemsDesc.forEach(item => {
            item.addEventListener("click", function(e) {
                e.preventDefault(); // link davranışını engelle

                const action = this.textContent.trim();

                if (!titleInputDesc) return;

                if (action === "Baş Harfleri Büyüt") {
                    titleInputDesc.value = titleInputDesc.value
                        .toLocaleLowerCase('tr-TR') // önce tümünü küçült
                        .split(" ") // kelimelere ayır
                        .map(word => {
                            if (word.length === 0) return "";
                            return word[0].toLocaleUpperCase('tr-TR') + word.slice(1);
                        })
                        .join(" ");


                } else if (action === "Bütün Harfleri Büyüt") {
                    titleInputDesc.value = titleInputDesc.value.toLocaleUpperCase('tr-TR');
                } else if (action === "Bütün Harfleri Küçült") {
                    titleInputDesc.value = titleInputDesc.value.toLocaleLowerCase('tr-TR');
                }
            });
        });

    });


    $(document).ready(function() {
        const src = $('#currentImage').attr('src');
        if (src.length > 0) {
            $('#editExistImage').remove();
        }
    });
</script>
@endsection