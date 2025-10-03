@extends('backend.layout')


@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                            <h4 class="box-title">Bildirim Güncelle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('notification.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Bildirim Listesine Dön</a>
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

                    <form class="form" action="{{ route('notification.update') }}" method="post">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                               
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Başlık</label>
                                        <input type="text" class="form-control" placeholder="Başlık" name="title" value='{{ $notification->title }}'>
                                        <input type="hidden" class="form-control" placeholder="Başlık" name="id" value='{{ $notification->id }}'>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                   <div class="form-group">
                                        <label class="form-label">Kime</label>
                                        <div class="form-group">
                                         <select class="form-control select2 select2parentcategory" style="width: 100%;" name="customer_id">
                                            <option value="0" {{ !isset($notification->customer) ? 'selected' : '' }}>Tümü</option>

                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" {{ isset($notification->customer) && $notification->customer->id == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->title }}
                                                </option>
                                            @endforeach
                                        </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                               
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">İçerik</label>
                                        <textarea name="message" id="message" class="form-control @error('description') is-invalid @enderror" 
                                                  rows="4">{{ $notification->message }}</textarea>
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
    <script src="{{ asset('backend/assets/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.js') }}"></script>
<script src="{{ asset('backend/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>

<script>
$(document).ready(function() {
    CKEDITOR.replace('message', {
        height: 200,
        toolbar: [
            { name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates'] },
            { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
            { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'SpellChecker', 'Scayt'] },
            '/',
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] },
            { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
            { name: 'insert', items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] },
            '/',
            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
            { name: 'tools', items: ['Maximize', 'ShowBlocks'] }
        ],
        removeButtons: '',
        filebrowserUploadUrl: "{{ route('ckeditorimageupload', ['_token' => csrf_token()]) }}",
        filebrowserUploadMethod: 'form'
    });


   
});
</script>
@endsection




















