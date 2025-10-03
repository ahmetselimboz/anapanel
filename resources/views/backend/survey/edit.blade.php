@extends('backend.layout')



@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Anket Düzenle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('survey.create') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Anket Ekle</a>
                                <a href="{{ route('survey.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Anket Listesine Dön </a>
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

                    <form class="form" action="{{ route('survey.update', $survey->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Başlık</label>
                                        <input type="text" class="form-control" placeholder="Başlık" name="title" value="{{ $survey->title }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Yayın Durumu</label>
                                        <select class="form-control select2" style="width: 100%;" name="publish">
                                            <option value="0" @if($survey->publish==0) selected="selected" @endif>Aktif</option>
                                            <option value="1" @if($survey->publish==1) selected="selected" @endif>Pasif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Resim <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="OPSİYONEL"></i></label>
                                        @if($survey->images!=null)<a class="btn btn-primary btn-sm" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Mevcut Ana Resmi Göster</a>@endif
                                        <input type="file" class="form-control" placeholder="Resim" name="images">
                                    </div>
                                    @if($survey->images!=null)
                                        <div class="form-group collapse" id="collapseExample"><div class="form-group"><img src="{{ asset('uploads/'.$survey->images) }}" alt=""> </div> </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row mt-3">
                                <h4 class="box-title">Anket Seçenekleri</h4>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-5"><input type="text" class="form-control " name="answer1" placeholder="Seçenek 1" value="{{ $surveyanswer->answer1 }}"></div>
                                <div class="col-md-1"><input type="number" class="form-control " name="answerhit1" min="0" placeholder="Oy" value="{{ $surveyanswer->answerhit1 }}"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-5"><input type="text" class="form-control " name="answer2" placeholder="Seçenek 2" value="{{ $surveyanswer->answer2 }}"></div>
                                <div class="col-md-1"><input type="number" class="form-control " name="answerhit2" min="0" placeholder="Oy" value="{{ $surveyanswer->answerhit2 }}"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-5"><input type="text" class="form-control " name="answer3" placeholder="Seçenek 3" value="{{ $surveyanswer->answer3 }}"></div>
                                <div class="col-md-1"><input type="number" class="form-control " name="answerhit3" min="0" placeholder="Oy" value="{{ $surveyanswer->answerhit3 }}"></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-5"><input type="text" class="form-control " name="answer4" placeholder="Seçenek 4" value="{{ $surveyanswer->answer4 }}"></div>
                                <div class="col-md-1"><input type="number" class="form-control " name="answerhit4" min="0" placeholder="Oy" value="{{ $surveyanswer->answerhit4 }}"></div>
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
            $("#answeradd").click(function (){
                $(".answer_area").append('<div class="mb-3">\n' +
                    '                                        <input type="text" class="form-control w-75 d-inline-block" name="answer[]" value="">\n' +
                    '                                        <span class="btn btn-danger btn-sm text-white"><i class="fa fa-trash"></i></span>\n' +
                    '                                    </div>');
            });

            $("#answer_delete").click(function (){
                console.log($(this).data("id"));
                $(".answer_area").remove("#answer");
            });
        });
    </script>
@endsection
