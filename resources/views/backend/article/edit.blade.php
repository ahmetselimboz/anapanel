@extends('backend.layout')



@section('content')
<style>
    .image-container {
    display: inline-block;
    width: 120px;
    height: 120px;
    border-radius: 10px;
    overflow: hidden;
    /* box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); */
    /* border: 2px solid #ddd; */
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.fw-bold {
    font-size: 16px;
    margin-bottom: 8px;
    color: #333;
}
</style>
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Makale Düzenle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('article.create') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Makale Ekle</a>
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

                    <form class="form" action="{{ route('article.update', $article->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-12 col-md-7">
                                    <div class="form-group">
                                        <label class="form-label">Başlık</label>
                                        <input type="text" class="form-control" placeholder="Başlık" name="title" value="{{ $article->title }}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Yazar </label>
                                        <select class="form-control select2" style="width: 100%;" name="author">
                                            <option value="" disabled  selected >Yazar Seçiniz</option>
                                            @foreach($author as $key => $value)
                                                <option value="{{ $value->id }}" @disabled($article->author_id != $value->id) @selected($article->author_id == $value->id)>{{ "($value->role_name)" }} - {{ $value->name }}</option>
                                            @endforeach
                                       </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-2 col-md-2 ">
                                    <div class="form-group">
                                        <label class="form-label">Yayın Durumu</label>
                                        <select class="form-control select2" style="width: 100%;" name="publish" id="publish">
                                            <option value="0" @if($article->publish==0) selected="selected" @endif>Aktif</option>
                                            <option value="1" @if($article->publish==1) selected="selected" @endif>Pasif</option>
                                            <option value="2" @if($article->publish==2) selected="selected" @endif>İleri Tarihli Makale</option>
                                        </select>
                                    </div>

                                    @php
                                        $displayStyle = $article->publish != 2 ? 'display: none;' : 'display: block;';
                                        $date = $article->publish == 2 ? $article->created_at : date('Y-m-d H:i') ;
                                    @endphp
                                    <div class="form-group" id="publish_date_div" style="{{ $displayStyle }}">
                                        <label class="form-label">Yayın Tarihi</label>
                                        <input type="datetime-local" class="form-control" placeholder="Yayın Tarihi" name="publish_date" id="publish_date" value="{{ $date }}" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Detay</label>
                                        <textarea id="detail_editor" cols="30" rows="5" class="form-control" placeholder="Açıklama" name="detail">{!! $article->detail !!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="images2"  type="file"  name="images" accept=".jpg,.png,.jpeg,.webp" >
                                <label class="input-group-text" for="images2"></label>
                              </div>
                              <div class="mb-3">
                                <p class="fw-bold">Mevcut Resim</p>
                                <div class="image-container">
                                    @if(empty($article->images)) Resim Yüklenmedi @else
                                    <img src="{{imageCheck($article->images)}}" alt="Mevcut Resim" id="image">
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

            CKEDITOR.replace('detail_editor', {
                //width: '70%',
                height: 300,
                filebrowserUploadUrl: "{{ route('home.ckeditorimageupload', ['_token' => csrf_token()]) }}",
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
