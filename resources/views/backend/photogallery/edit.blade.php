@extends('backend.layout')



@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Foto Galeri Düzenle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('photogallery.create') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Foto Galeri Ekle</a>
                                <a href="{{ route('photogallery.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Foto Galeri Listesine Dön </a>
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

                    <form class="form" action="{{ route('photogallery.update', $photogallery->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Kategori</label>
                                        <select class="form-control select2" style="width: 100%;" name="category_id">
                                            <option value="0">Yok</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" @if($photogallery->category_id==$category->id) selected @endif >{{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label">Başlık</label>
                                        <input type="text" class="form-control" placeholder="Başlık" name="title" value="{{ $photogallery->title }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Yayın Durumu</label>
                                        <select class="form-control select2" style="width: 100%;" name="publish">
                                            <option value="0" @if($photogallery->publish==0) selected="selected" @endif>Aktif</option>
                                            <option value="1" @if($photogallery->publish==1) selected="selected" @endif>Pasif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">Hit</label>
                                        <input type="text" class="form-control" placeholder="Rakam" name="hit" value="{{ $photogallery->hit }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Kapak Resmi </label>
                                        <input type="file" class="form-control" placeholder="Resim" name="images">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Detay</label>
                                        <textarea placeholder="Detay" name="detail" id="detail_editor" cols="30" rows="5" class="form-control">{!! $photogallery->detail !!}</textarea>
                                    </div>
                                </div>
                            </div>

                            <a class="btn btn-sm btn-primary" href="{{ route('photogalleryimages', $photogallery->id) }}"> <i class="fa fa-external-link"></i> Galeri Resimlerini Görüntüle </a>

                            @if($photogallery->images!=null)
                                <a class="btn btn-primary btn-sm" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Mevcut Kapak Resmini Göster</a>
                            @endif
                            @if($photogallery->images!=null)
                                <div class="form-group collapse mt-2" id="collapseExample"><div class="form-group"><img src="{{ asset('uploads/'.$photogallery->images) }}" alt=""> </div> </div>
                            @endif


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
    <script src="{{ asset('backend/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            "use strict";
            CKEDITOR.replace('detail_editor', {
                //width: '70%',
                height: 300,
                //removeButtons: 'PasteFromWord'
            });
        });
    </script>
@endsection
