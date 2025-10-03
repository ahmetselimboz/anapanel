@extends('backend.layout')



@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Reklam Düzenle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('ads.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Reklam Listesine Dön </a>
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

                    <form class="form" action="{{ route('ads.update', $ads->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Başlık</label>
                                        <input type="text" class="form-control" name="title" value="{{ $ads->title }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Link <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Reklam harici bir adrese gidecekse url eklemek gerekir."></i></label>
                                        <input type="text" class="form-control" placeholder="http::// gibi" name="url" value="{{ $ads->url }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Yayın Durumu</label>
                                        <select class="form-control select2" style="width: 100%;" name="publish">
                                            <option value="0" @if($ads->publish==0) selected="selected" @endif>Aktif</option>
                                            <option value="1" @if($ads->publish==1) selected="selected" @endif>Pasif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Reklam Tipi</label>
                                        <select class="form-control select2" style="width: 100%;" name="type" id="type">
                                            <option value="0" @if($ads->type==0) selected="selected" @endif>Resim Reklam</option>
                                            <option value="1" @if($ads->type==1) selected="selected" @endif>Kod Reklam</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="code">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Kod Reklam</label>
                                        <textarea name="code" cols="30" rows="10" class="form-control">{!! $ads->code !!}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="images">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Resim Seçin</label>
                                        <input type="file" name="images" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Genişlik</label>
                                        <input type="text" name="width" class="form-control" value="{{ $ads->width }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Yükseklik</label>
                                        <input type="text" name="height" class="form-control" value="{{ $ads->height }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Mevcut Resim</label>
                                        <img src="{{ asset($ads->images) }}" alt="" class="d-block w-100 img-fluid">
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
    <script type="text/javascript">
        $(function () {

            <?php if($ads->type==0){ ?> $("#images").show(); $("#code").hide(); <?php }else{ ?> $("#images").hide(); $("#code").show(); <?php } ?>

            $("#type").change(function (){
                if($(this).val()==0){ $("#images").show(); $("#code").hide(); }else{ $("#images").hide(); $("#code").show(); }
            });

        });
    </script>
@endsection
