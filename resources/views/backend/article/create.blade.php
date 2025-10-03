@extends('backend.layout')

@section('custom_css')
    <style>
    </style>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Makale Ekle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('article.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Makale Listesine Dön </a>
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

                    <form class="form" action="{{ route('article.store') }}" method="post" enctype="multipart/form-data" >
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-12 col-md-7">
                                    <div class="form-group">
                                        <label class="form-label">Başlık</label>
                                        <input type="text" class="form-control" placeholder="Başlık" name="title">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 col-md-3 ">
                                    <div class="form-group">
                                        <label class="form-label">Yazar </label>
                                        <select class="form-control select2" style="width: 100%;" name="author">
                                            <option value="" disabled  selected >Yazar Seçiniz</option>
                                            @foreach($author as $key => $value)
                                                <option value="{{ $value->id }}">{{ $value->role_name }} - {{ $value->name }}</option>
                                            @endforeach
                                       </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-2 col-md-2 ">

                                    <div class="form-group">
                                        <label class="form-label">Yayın Durumu</label>
                                        <select class="form-control select2" style="width: 100%;" name="publish" id="publish">
                                            <option value="0" selected="selected">Aktif</option>
                                            <option value="1">Pasif</option>
                                            <option value="2">İleri Tarihli Makale</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="publish_date_div" style="display: none;">
                                        <label class="form-label">Yayın Tarihi</label>
                                        <input type="datetime-local" class="form-control" placeholder="Yayın Tarihi" name="publish_date" id="publish_date" value="{{ date('Y-m-d H:i') }}" >
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Detay</label>
                                        <textarea id="detail_editor" cols="30" rows="5" class="form-control" placeholder="Açıklama" name="detail"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="images2"  type="file"  name="images" accept=".jpg,.png,.jpeg,.webp" >
                                <label class="input-group-text" for="images2"></label>
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
                    CKEDITOR.replace('detail_editor', {
                        //width: '70%',
                        height: 300,
                        filebrowserUploadUrl: "{{ route('ckeditorimageupload', ['_token' => csrf_token()]) }}",
                        filebrowserUploadMethod: 'form',
                        //removeButtons: 'PasteFromWord'
                    });
                });



        $(document).ready(function() {

            function getCurrentDateTimeLocal() {
                const now = new Date();
                now.setSeconds(0, 0); // Saniye ve milisaniyeyi sıfırla
                const iso = now.toISOString();
                return iso.substring(0, 16); // "YYYY-MM-DDTHH:MM"
            }

            // Min değeri ayarla
            $('#publish_date').attr('min', getCurrentDateTimeLocal());



            $('#publish').change(function() {
                if ($(this).val() == 2) {
                    $('#publish_date_div').show();
                    $('#publish_date').attr('required', true);
                } else {
                    $('#publish_date_div').hide();
                    $('#publish_date').removeAttr('min').val(''); // Tarih alanın
                    $('#publish_date').removeAttr('required');
                }
            });
        });
    </script>
@endsection
