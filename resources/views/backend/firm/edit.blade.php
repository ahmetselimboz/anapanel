@extends('backend.layout')



@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Firma Düzenle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('firm.create') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Firma Ekle</a>
                                <a href="{{ route('firm.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Firma Listesine Dön </a>
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

                    <form class="form" action="{{ route('firm.update', $firm->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Başlık</label>
                                        <input type="text" class="form-control" placeholder="Başlık" name="title" value="{{ $firm->title }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Yayın Durumu</label>
                                        <select class="form-control select2" style="width: 100%;" name="publish">
                                            <option value="0" @if($firm->publish==0) selected @endif>Aktif</option>
                                            <option value="1" @if($firm->publish==1) selected @endif>Pasif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Resim </label>
                                        @if($firm->images!=null)<a class="btn btn-primary btn-sm" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Mevcut Ana Resmi Göster</a>@endif
                                        <input type="file" class="form-control" placeholder="Resim" name="images">
                                        @if($firm->images!=null)
                                            <div class="form-group mt-2 collapse" id="collapseExample"><div class="form-group"><img src="{{ asset('uploads/'.$firm->images) }}" alt=""> </div> </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Detay</label>
                                        <textarea id="detail_editor" cols="30" rows="5" class="form-control" placeholder="Detay" name="detail">@if(isset($firmmagicbox->detail)) {{ $firmmagicbox->detail }} @endif</textarea>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Kategori</label>
                                        <select class="form-control select2" style="width: 100%;" name="category_id">
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" @if($category->id==$firm->category_id) selected="selected" @endif>{{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Telefon</label>
                                        <input type="text" class="form-control" placeholder="Telefon" name="phone" @if(isset($firmmagicbox->phone)) value="{{ $firmmagicbox->phone }}" @endif>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Web Site</label>
                                        <input type="text" class="form-control" placeholder="https://" name="website" @if(isset($firmmagicbox->website)) value="{{ $firmmagicbox->website }}" @endif>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">E Posta</label>
                                        <input type="text" class="form-control" placeholder="ornek@ornek.com" name="email" @if(isset($firmmagicbox->email)) value="{{ $firmmagicbox->email }}" @endif>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Sektör</label>
                                        <input type="text" class="form-control" placeholder="Örn: Bilgisayar" name="sector_category" @if(isset($firmmagicbox->sector_category)) value="{{ $firmmagicbox->sector_category }}" @endif>
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="form-label">Adres</label>
                                        <input type="text" class="form-control" placeholder="Örn: Aşık Veysel Caddesi Küçükçekmece / İstanbul" name="address" @if(isset($firmmagicbox->address)) value="{{ $firmmagicbox->address }}" @endif>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Video (Embed)</label>
                                        <textarea name="embed" id="" cols="30" rows="3" class="form-control">@if(isset($firmmagicbox->embed)) {!! $firmmagicbox->embed !!} @endif</textarea>
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
