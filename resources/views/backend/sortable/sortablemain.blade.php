@extends('backend.layout')
@section('custom_css')
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Ana Sayfa Yapısı</h4>
                    </div>
                    <!-- /.box-header -->
                    <div class="row px-3 py-3">
                        <div class="col-md-8">
                            <ul class="todo-list">
                                {{-- @dd($sortable) --}}
                                @foreach ($sortable as $item)
                                    <li class="bg-light p-0 mb-15" id="{{ $item->id }}">
                                        <div class="position-relative p-20">
                                            <!-- drag handle -->
                                            <div class="handle handle2"></div>
                                            <span class="text-line fs-18">
                                                <input type="text" name="title" id="{{ $item->id }}"
                                                       onkeyup="titleAndLimit(this);" value="{{ $item->title }}"
                                                       class="form-control w-75 d-inline-block" placeholder="Görünecek Başlık">
                                            </span>


                                            @if ($item->type == 'menu' && $item->id != 18)
                                                <div class="mt-15">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="ads">Gösterilecek Menü</label>
                                                            <select name="menu" id="menu"
                                                                    class="form-control change"
                                                                    data-parent-id="{{ $item->id }}">
                                                                <option value="0">Seçilmedi</option>
                                                                @foreach ($menus as $menu)
                                                                    <option value="{{ $menu->id }}"
                                                                            @if ($menu->id == $item->menu) selected @endif>
                                                                        {{ $menu->title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="design">Konum</label>
                                                            <select name="design" id="design"
                                                                    class="form-control change"
                                                                    data-parent-id="{{ $item->id }}">
                                                                <option value="default">Tasarım Seçilmedi</option>
                                                                <option value="0"
                                                                        @if ($item->design == 0) selected @endif>Ana
                                                                    Menü (Tepe)
                                                                </option>
                                                                <option value="1"
                                                                        @if ($item->design == 1) selected @endif>Alt 1
                                                                    Menü
                                                                </option>
                                                                <option value="2"
                                                                        @if ($item->design == 2) selected @endif>Alt 2
                                                                    Menü
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($item->type == 'ads')
                                                <div class="mt-15">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="ads">Gösterilecek Reklam</label>
                                                            <select name="ads" id="ads"
                                                                    class="form-control change"
                                                                    data-parent-id="{{ $item->id }}">
                                                                <option value="0">Seçilmedi</option>
                                                                @foreach ($ads as $ad)
                                                                    <option value="{{ $ad->id }}"
                                                                            @if ($ad->id == $item->ads) selected @endif>
                                                                        {{ $ad->title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($item->id == 5)

                                                <div class="mt-15">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label for="design">Mİni Manşet Tasarım</label>
                                                            <select name="design" id="design_block_ana_manset"
                                                                    class="form-control change"
                                                                    data-parent-id="{{ $item->id }}">
                                                                <option value="default">Varsayılan Tasarım</option>
                                                                <option value="1"
                                                                        @if ($item->design == 1) selected @endif>1.
                                                                    Tasarım
                                                                </option>
                                                                <option value="2"
                                                                        @if ($item->design == 2) selected @endif>2.
                                                                    Tasarım
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif





                                            @if (
                                                $item->type != 'ads' &&
                                                    $item->type != 'menu' &&
                                                    $item->id == 12 ||
                                                    $item->id == 14 ||
                                                    $item->id == 8 ||
                                                    $item->id == 6 )
                                                <div class="mt-15">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label for="category">Kategorisi</label>
                                                            <select name="category" id="category"
                                                                    class="form-control change"
                                                                    data-parent-id="{{ $item->id }}">
                                                                <option value="0">Seçilmedi</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}"
                                                                            @if ($category->id == $item->category) selected @endif>
                                                                        {{ $category->title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="limit">Haber Sayısı</label>
                                                            <input name="limit" id="{{ $item->id }}"
                                                                   value="{{ $item->limit }}" class="form-control"
                                                                   type="number" minlength="0" maxlength="2"
                                                                   onkeyup="titleAndLimit(this);">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="design">Tasarım</label>
                                                            <select name="design" id="design"
                                                                    class="form-control change"
                                                                    data-parent-id="{{ $item->id }}">
                                                                <option value="default">Varsayılan Tasarım</option>
                                                                <option value="1"
                                                                        @if ($item->design == 1) selected @endif>1.
                                                                    Tasarım
                                                                </option>
                                                                <option value="2"
                                                                        @if ($item->design == 2) selected @endif>2.
                                                                    Tasarım
                                                                </option>
                                                                <option value="3"
                                                                        @if ($item->design == 3) selected @endif>3.
                                                                    Tasarım
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3 d-none">
                                                            <label for="color">Başlık veya Arka Plan</label>
                                                            <input name="color" id="color" type="color"
                                                                   class="form-control" style="height: 33px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <div class="box-inverse box-warning">
                                <div class="box-body text-center">
                                    <i class="fa fa-bullhorn fa-4x"></i>
                                    <h4 class="my-2">BİLGİLENDİRME</h4>
                                    <ul class="list-group">
                                        <li>Değişiklik olduğu anda kayıt edilir.</li>
                                        <li>Tamamen tut-sürükle-bırak ile düzenlenebilir.</li>
                                        <li>Reklam birimlerinde reklamlar listelenir.</li>
                                        <li>Modüle ait tasarımlar mevcutsa değiştirilebilir.</li>
                                        <li>Haber sayısı maksimum modül limiti kadar olabilir.</li>
                                        <li>Haber sayısı bazı tasarımlarda sabittir değişmez.</li>
                                        <li>Satın alınan ek modül/tasarım varsa düzenlenebilir.</li>
                                        <li class="text-danger">Haber kategorisi seçilen bölümlerde sadece "standart haber"
                                            türündekiler gösterilir.
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
    <script src="{{ asset('backend/assets/vendor_components/jquery-ui/jquery-ui.js') }}"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">


    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function () {
            "use strict";
            $('.todo-list').sortable({
                placeholder: 'sort-highlight',
                handle: '.handle',
                forcePlaceholderSize: true,
                zIndex: 999999,
                stop: function () {
                    $.map($(this).find('li'), function (el) {
                        let itemID = el.id;
                        let itemIndex = $(el).index();
                        $.ajax({
                            url: '{{ route('sortableHomePagePost') }}',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                // '_token':  headers,
                                itemID: itemID,
                                itemIndex: itemIndex
                            },
                        })
                    })
                }
            });
        });

        $(".change").on('change', function () {

            $.ajax({
                url: "{{ route('sortableHomePagePostOtherSetting') }}",
                type: "POST",
                data: {
                    value: this.value,
                    name: this.name,
                    parent_id: $(this).data("parent-id")
                },
                success: function (response) {
                    toastr.options = {
                        "closeButton": true,
                        "newestOnTop": true,
                        "positionClass": "toast-top-right"
                    };
                    toastr.success("Güncelleme yapıldı", "Başarılı");
                },
                error: function (response) {
                    toastr.options = {
                        "closeButton": true,
                        "newestOnTop": true,
                        "positionClass": "toast-top-right"
                    };
                    toastr.error("Güncelleme yapılamadı. Sayfayı yeniden başlatın.", "HATA");
                },
            });
        });


        function titleAndLimit(obj) {
            $.ajax({
                url: "{{ route('sortableHomePagePostOtherSetting') }}",
                type: "POST",
                data: {
                    value: obj.value,
                    name: obj.name,
                    parent_id: obj.id
                },
                success: function(response) {
                    toastr.options = {
                        "closeButton": true,
                        "newestOnTop": true,
                        "positionClass": "toast-top-right"
                    };
                    toastr.success("Güncelleme yapıldı", "Başarılı");
                },
                error: function(response) {
                    toastr.options = {
                        "closeButton": true,
                        "newestOnTop": true,
                        "positionClass": "toast-top-right"
                    };
                    toastr.error("Güncelleme yapılamadı Sayfayı yeniden başlatın.", "HATA");
                },
            });
        }
    </script>
@endsection
