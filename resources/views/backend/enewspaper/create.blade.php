@extends('backend.layout')

@section('custom_css')
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">E Gazete Ekle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
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

                    <form class="form" action="{{ route('enewspaper.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="box-body">
        <div class="row">
            <!-- Başlık Alanı -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Başlık</label>
                    <input type="text" class="form-control" placeholder="Başlık" name="title" required>
                </div>
            </div>

            <!-- Harici Link Alanı -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Harici Link</label>
                    <input type="url" class="form-control" placeholder="http:// gibi" name="redirect_link">
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Yayın Durumu Alanı -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Yayın Durumu</label>
                    <select class="form-control select2" style="width: 100%;" name="publish">
                        <option value="0" selected="selected">Aktif</option>
                        <option value="1">Pasif</option>
                    </select>
                </div>
            </div>

            <!-- Yayın Tarihi Alanı -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Yayın Tarihi <span class="text-danger"> *</span></label>
                    <input class="form-control" type="date" name="date" id="date" value="{{ now()->format('Y-m-d') }}" required>
                </div>
            </div>

            <!-- Resim Alanı -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Resim</label>
                    <input type="file" class="form-control" placeholder="Resim" name="images" required>
                </div>
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
