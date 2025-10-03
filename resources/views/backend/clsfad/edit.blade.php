@extends('backend.layout')



@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Seri İlan Düzenle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('clsfad.create') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Seri İlan Ekle</a>
                                <a href="{{ route('clsfad.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Seri İlanlar Listesine Dön </a>
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

                    <form class="form" action="{{ route('clsfad.update', $clsfad->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Başlık <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Başlık" name="title" value="{{ $clsfad->title }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Yayın Durumu <span class="text-danger">*</span></label>
                                        <select class="form-control select2" style="width: 100%;" name="publish" required >
                                            <option value="0" @selected($clsfad->publish==0)  >Aktif</option>
                                            <option value="1" @selected($clsfad->publish==1)  >Pasif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Resim </label>
                                        @if($clsfad->images!=null)<a class="btn btn-primary btn-sm" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Mevcut Ana Seri Göster</a>@endif
                                        <input type="file" class="form-control" placeholder="Resim" name="images">
                                        @if($clsfad->images!=null)
                                            <div class="form-group mt-2 collapse" id="collapseExample"><div class="form-group"><img src="{{ asset('uploads/'.$clsfad->images) }}" alt=""> </div> </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Detay <span class="text-danger">*</span></label>
                                        <textarea id="detail_editor" cols="30" rows="5" class="form-control" placeholder="Detay" name="detail">@if(isset($clsfadmagicbox->detail)) {{ $clsfadmagicbox->detail }} @endif</textarea>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Kategori <span class="text-danger">*</span> </label>
                                        <select class="form-control select2" style="width: 100%;" name="category_id">
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" @selected($category->id==$clsfad->category_id)>{{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Ad Soyad</label>
                                        <input type="text" class="form-control" placeholder="Ad Soyad" name="name" @if(isset($clsfadmagicbox->name)) value="{{ $clsfadmagicbox->name }}" @endif>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Fiyat</label>
                                        <input type="number" class="form-control" placeholder="Fiyat" name="price" @if(isset($clsfadmagicbox->price)) value="{{ $clsfadmagicbox->price }}" @endif>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Telefon</label>
                                        <input type="text" class="form-control" placeholder="Örn: 05xxxxxxxx" name="phone" @if(isset($clsfadmagicbox->phone)) value="{{ $clsfadmagicbox->phone }}" @endif>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Adres</label>
                                        <input type="text" class="form-control" placeholder="Örn: Aşık Veysel Caddesi Küçükçekmece / İstanbul" name="address" @if(isset($clsfadmagicbox->address)) value="{{ $clsfadmagicbox->address }}" @endif>
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
                height: 200,
                //removeButtons: 'PasteFromWord'
            });

            CKEDITOR.editorConfig = function( config ) {
                config.toolbarGroups = [
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                    { name: 'forms', groups: [ 'forms' ] },
                    '/',
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                    { name: 'links', groups: [ 'links' ] },
                    { name: 'insert', groups: [ 'insert' ] },
                    '/',
                    { name: 'styles', groups: [ 'styles' ] },
                    { name: 'colors', groups: [ 'colors' ] },
                    { name: 'tools', groups: [ 'tools' ] },
                    { name: 'others', groups: [ 'others' ] },
                    { name: 'about', groups: [ 'about' ] }
                ];

                config.removeButtons = 'Source,Save,Templates,NewPage,ExportPdf,Preview,Print,PasteFromWord,PasteText,Paste,Copy,Redo,Undo,Cut,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,BidiLtr,BidiRtl,Language,Anchor,Table,HorizontalRule,SpecialChar,PageBreak,Iframe,BGColor,ShowBlocks,Maximize,About,Indent,Outdent,Blockquote,CreateDiv,NumberedList,RemoveFormat';
            };
        });

    </script>
@endsection
