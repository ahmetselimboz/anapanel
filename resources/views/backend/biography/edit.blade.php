@extends('backend.layout')



@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Biyografi Düzenle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('biography.create') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Biyografi Ekle</a>
                                <a href="{{ route('biography.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Biyografi Listesine Dön </a>
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

                    <form class="form" action="{{ route('biography.update', $biography->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Başlık</label>
                                        <input type="text" class="form-control" placeholder="Başlık" name="title" value="{{ $biography->title }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Yayın Durumu</label>
                                        <select class="form-control select2" style="width: 100%;" name="publish">
                                            <option value="0" @if($biography->publish==0) selected="selected" @endif>Aktif</option>
                                            <option value="1" @if($biography->publish==1) selected="selected" @endif>Pasif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Resim</label>
                                        @if($biography->images!=null)<a class="btn btn-primary btn-sm" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Mevcut Ana Resmi Göster</a>@endif
                                        <input type="file" class="form-control" placeholder="Resim" name="images">
                                    </div>
                                    @if($biography->images!=null)
                                        <div class="form-group collapse" id="collapseExample"><div class="form-group"><img src="{{ asset('uploads/'.$biography->images) }}" alt=""> </div> </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Detay</label>
                                        <textarea id="detail_editor" cols="30" rows="5" class="form-control" placeholder="Açıklama" name="detail">{!! $biography->detail !!}</textarea>
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
