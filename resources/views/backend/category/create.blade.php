@extends('backend.layout')

@section('custom_css')
    <style>
        .cbuttontext:before, .cbuttontext:after{content:""!important;}
        .bootstrap-tagsinput{width:100%;}
    </style>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Kategori Ekle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('category.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Kategori Listesine Dön </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Hata!</strong> Aşağıdaki hataları düzeltin.<br><br>
                            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
                        </div>
                    @endif

                    <form class="form" action="{{ route('category.store') }}" method="post">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Kategori Tipi</label>
                                        <div class="form-group">
                                            <select class="form-control select2" style="width: 100%;" name="category_type" id="category_type_change" required>
                                                <option value="">SEÇİLMEDİ</option>
                                                <option value="0">Haber</option>
                                                {{-- <option value="1">Foto Galeri</option> --}}
                                                <option value="2">Video Galeri</option>
                                                {{-- <option value="3">Firma Rehberi</option>
                                                <option value="4">Seri İlanlar</option>
                                                <option value="5">Resmi İlanlar</option> --}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Üst Kategori</label>
                                        <div class="form-group">
                                            <input type="hidden" name="parent_categoryResult" id="parent_categoryResult" value="">
                                            <span class="badge badge-warning d-block py-2" id="catnote">Kategori Tipi Seçin <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="ÜST KATEGORİ SEÇİMİ YAPACAKSANIZ ÖNCE KATEGORİ TİPİNİ SEÇMELİSİNİZ."></i></span>
                                            <div class="d-none" id="0">
                                                <select class="form-control select2 select2parentcategory" style="width: 100%;" name="parent_category" >
                                                    <option value="0">Yok</option>
                                                    @foreach($parent_categories as $parent_category)
                                                        @if($parent_category->category_type==0)
                                                            <option value="{{ $parent_category->id }}">{{ $parent_category->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="d-none" id="1">
                                                <select class="form-control select2 select2parentcategory" style="width: 100%;" name="parent_category">
                                                    <option value="0">Yok</option>
                                                    @foreach($parent_categories as $parent_category)
                                                        @if($parent_category->category_type==1)
                                                            <option value="{{ $parent_category->id }}">{{ $parent_category->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="d-none" id="2">
                                                <select class="form-control select2 select2parentcategory" style="width: 100%;" name="parent_category" >
                                                    <option value="0">Yok</option>
                                                    @foreach($parent_categories as $parent_category)
                                                        @if($parent_category->category_type==2)
                                                            <option value="{{ $parent_category->id }}">{{ $parent_category->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="d-none" id="3">
                                                <select class="form-control select2 select2parentcategory" style="width: 100%;" name="parent_category">
                                                    <option value="0">Yok</option>
                                                    @foreach($parent_categories as $parent_category)
                                                        @if($parent_category->category_type==3)
                                                            <option value="{{ $parent_category->id }}">{{ $parent_category->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="d-none" id="4">
                                                <select class="form-control select2 select2parentcategory" style="width: 100%;" name="parent_category">
                                                    <option value="0">Yok</option>
                                                    @foreach($parent_categories as $parent_category)
                                                        @if($parent_category->category_type==4)
                                                            <option value="{{ $parent_category->id }}">{{ $parent_category->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Kategoride Reklam?</label>
                                        <select class="form-control select2" style="width: 100%;" name="show_category_ads">
                                            <option value="0" selected="selected">Gösterilsin</option>
                                            <option value="1">Gösterilmesin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Başlık</label>
                                        <input type="text" class="form-control" placeholder="Başlık" name="title">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Yayın Durumu</label>
                                        <select class="form-control select2" style="width: 100%;" name="publish">
                                            <option value="0" selected="selected">Aktif</option>
                                            <option value="1">Pasif</option>
                                        </select>
                                    </div>
                                </div>
                         
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="form-label">Anahtar Kelimeler</label>
                                        <input type="text" class="form-control" placeholder="Anahtar Kelimeler" name="keywords" data-role="tagsinput">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Açıklama</label>
                                        <textarea id="" cols="30" rows="5" class="form-control" placeholder="Açıklama" name="description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary"> <i class="ti-save-alt"></i> Kaydet </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
    <script src="{{ asset('backend/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
    <script src="{{ asset('backend/js/pages/advanced-form-element.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.js') }}"></script>
    <script type="text/javascript">
        $('#category_type_change').change(function (){
            $("#catnote").addClass("d-none"); var getselectid =  $(this).val(); $("#0,#1,#2,#3,#4").addClass("d-none"); $("#"+getselectid).removeClass("d-none");
        });

        $('.select2parentcategory').on('select2:select', function (e) {
            var data = e.params.data; // console.log(data);
            $("#parent_categoryResult").val(data.id)
        });
    </script>
@endsection




















