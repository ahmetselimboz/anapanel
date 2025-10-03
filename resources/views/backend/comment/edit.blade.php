@extends('backend.layout')



@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Yorum Düzenle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('post', ['categoryslug' => $comment->post->category->slug, 'slug' => $comment->post->slug, 'id' => $comment->post->id]) }}" type="button" class="btn btn-primary btn-sm" target="_blank">
                                    <i class="fa fa-external-link"></i>
                                    Yorumun Olduğu Habere Git
                                </a>
                                <a href="{{ route('comment.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Yorum Listesine Dön </a>
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

                    <form class="form" action="{{ route('comment.update', $comment->id) }}" method="post" >
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Ad Soyad</label>
                                        <input type="text" class="form-control" placeholder="Başlık" name="title" value="{{ $comment->title }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">E Posta</label>
                                        <input type="text" class="form-control" placeholder="ornek@ornek.com" name="email" value="{{ $comment->email }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Yorum Detayı</label>
                                        <textarea name="detail" id="" cols="30" rows="1" class="form-control">{{ $comment->detail }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Yayın Durumu</label>
                                        <select class="form-control select2" style="width: 100%;" name="publish">
                                            <option value="0" @if($comment->publish==0) selected="selected" @endif>Aktif</option>
                                            <option value="1" @if($comment->publish==1) selected="selected" @endif>Pasif</option>
                                        </select>
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
@endsection
