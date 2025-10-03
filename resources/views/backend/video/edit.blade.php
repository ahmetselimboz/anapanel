@extends('backend.layout')



@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Video Düzenle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('video.create') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Video Ekle</a>
                                <a href="{{ route('video.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Video Listesine Dön </a>
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

                    <form class="form" action="{{ route('video.update', $video->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Kategori</label>
                                        <select class="form-control select2" style="width: 100%;" name="category_id">
                                            <option value="0">Yok</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" @if($video->category_id==$category->id) selected @endif >{{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label">Başlık</label>
                                        <input type="text" class="form-control" placeholder="Başlık" name="title" value="{{ $video->title }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Yayın Durumu</label>
                                        <select class="form-control select2" style="width: 100%;" name="publish">
                                            <option value="0" @if($video->publish==0) selected="selected" @endif>Aktif</option>
                                            <option value="1" @if($video->publish==1) selected="selected" @endif>Pasif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label class="form-label">Hit</label>
                                        <input type="text" class="form-control" placeholder="Rakam" name="hit" value="{{ $video->hit }}">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Detay</label>
                                        <textarea placeholder="Detay" name="detail" id="detail_editor" cols="30" rows="5" class="form-control">{!! $video->detail !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Embed Kodu</label>
                                        <textarea placeholder="Embed Kodu" name="embed" id="" cols="30" rows="13" class="form-control">{!! $video->embed !!}</textarea>
                                    </div>
                                    @if($video->images!=null)<a class="btn btn-primary btn-sm" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Mevcut Kapak Resmini Göster</a>@endif
                                    @if($video->images!=null)
                                        <div class="form-group collapse" id="collapseExample"><div class="form-group"><img src="{{ asset('uploads/'.$video->images) }}" alt=""> </div> </div>
                                    @endif
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
