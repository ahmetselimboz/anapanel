@extends('backend.layout')



@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">E Gazete Düzenle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('enewspaper.create') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> E Gazete Ekle</a>
                                <a href="{{ route('enewspaper.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> E Gazete Listesine Dön </a>
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

                    <form class="form" action="{{ route('enewspaper.update', $enewspaper->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="box-body">
        <div class="row">
            <!-- Başlık Alanı -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Başlık <span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" placeholder="Başlık" name="title" value="{{ $enewspaper->title }}" required>
                </div>
            </div>

            <!-- Harici Link Alanı -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Harici Link</label>
                    <input type="text" class="form-control" placeholder="http:// gibi" name="redirect_link" value="{{ $enewspaper->redirect_link }}">
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Yayın Durumu Alanı -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Yayın Durumu</label>
                    <select class="form-control select2" style="width: 100%;" name="publish">
                        <option value="0" @if($enewspaper->publish==0) selected="selected" @endif>Aktif</option>
                        <option value="1" @if($enewspaper->publish==1) selected="selected" @endif>Pasif</option>
                    </select>
                </div>
            </div>

            <!-- Yayın Tarihi Alanı -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Yayın Tarihi <span class="text-danger"> *</span></label>
                    <input class="form-control" type="date" name="date" id="date" value="{{ $enewspaper->date }}" required>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Resim Alanı -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Resim <span class="text-danger"> *</span></label>
                    <input type="file" class="form-control" placeholder="Resim" name="images">
                </div>
            </div>
        </div>

        <!-- Mevcut Resim Gösterme -->
        <div class="row">

        @if($enewspaper->images != null)
        <div class="col-12 col-md-2">
        <a class="btn btn-primary btn-sm" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Mevcut Resmi Göster</a>
                <div class="form-group collapse" id="collapseExample">
                    <div class="form-group">
                        <img width="200" src="{{ asset('uploads/'.$enewspaper->images) }}" alt="Mevcut Resim">
                    </div>
                </div>
            </div>
        @endif

        <!-- İçerik Resimlerini Göster -->
            <div class="col-12 col-md-2">
                <a class="btn btn-sm btn-primary" href="{{ route('enewspaperimages', $enewspaper->id) }}">
                    <i class="fa fa-external-link"></i> İçerik Resimlerini Göster
                </a>
            </div>
        </div>
    </div>

    <!-- Form Footer (Kaydet Butonu) -->
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
