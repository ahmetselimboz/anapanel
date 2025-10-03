@extends('backend.layout')

@section('custom_css')
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <style>
        #actions {margin: 2em 0;}
        .progress {width: 10rem;}
        div.table {display: table;}
        div.table .file-row {display: table-row;}
        div.table .file-row > div {display: table-cell;vertical-align: top;border-top: 1px solid #ddd;padding: 8px;}
        div.table .file-row:nth-child(odd) {background: #f9f9f9;}
        #total-progress {opacity: 0;transition: opacity 0.3s linear;}
        #previews .file-row.dz-success .progress {opacity: 0;transition: opacity 0.3s linear;}
        #previews .file-row .delete {display: none;}
        #previews .file-row.dz-success .start, #previews .file-row.dz-success .cancel {display: none;}
        #previews .file-row.dz-success .delete {display: block;}
        .getimagesclass > img {max-height:100%;width: 100%}
    </style>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title"> <span class="text-info">{{ $photogallery->title }}</span> galeri resimleri</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('enewspaper.edit', $photogallery->id) }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Galeri Detayına Dön </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    @if ($errors->any())
                        <div class="alert alert-danger"><strong>Hata!</strong> Aşağıdaki hataları düzeltin.<br><br><ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul></div>
                    @endif

                    <div class="row mx-2" id="sortable">
                        <div class="alert alert-info p-2 mb-0 mx-3 mx-auto my-1"> Sürükle bırak ile sıralamayı değiştirebilirsiniz.</div>
                        @foreach($getimages as $getimage)
                            <div class="ui-state-default col-md-2 getimagesclass my-2 imgrow{{ $getimage->id }}" data-id="{{ $getimage->id }}">
                                <img src="{{ asset('uploads/'.$getimage->images) }}" alt="" class="mb-2">
                                <textarea id="imagetextarea_{{ $getimage->id }}" cols="30" rows="4" class="form-control">{{ $getimage->title }}</textarea>
                                <div class="alert alert-success alert{{ $getimage->id }} d-block w-100 p-2 d-none " role="alert"></div>
                                <div class="alert alert-danger alert{{ $getimage->id }} d-block w-100 p-2 d-none " role="alert"></div>
                                <span id="{{ $getimage->id }}" class="imageupdatebutton btn btn-sm btn-secondary m-1 text-center">GÜNCELLE</span>
                                <span id="{{ $getimage->id }}" class="imagedeletebutton btn btn-sm btn-danger m-1 text-center">SİL</span>
                            </div>
                        @endforeach
                    </div>

                    <div id="actions" class="row">
                        <div class="col-lg-7">
                            <span class="btn btn-success fileinput-button"><i class="glyphicon glyphicon-plus"></i><span> Resim Ekle</span></span>
                            <a href="{{ route('enewspaperimages', $photogallery->id) }}" class="btn btn-primary "><i class="fa fa-refresh"></i><span> Sayfayı Yenile</span></a>
                            <button type="submit" class="d-none btn btn-primary start"><i class="glyphicon glyphicon-upload"></i><span> Yüklemeyi Başlat</span></button>
                            <button type="reset" class="d-none btn btn-warning cancel"><i class="glyphicon glyphicon-ban-circle"></i><span> Yükleme İptal</span></button>
                        </div>
                        <div class="col-lg-5">
                            <span class="fileupload-process">
                                <div id="total-progress" class="progress active" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" >
                                  <div class="progress-bar progress-bar-striped progress-bar-success" role="progressbar" style="width: 0%" data-dz-uploadprogress ></div>
                                </div>
                            </span>
                        </div>
                    </div>

                    <div class="files table table-striped" id="previews">
                        <div id="template" class="file-row">
                            <div><span class="preview"><img data-dz-thumbnail /></span></div>
                            <div><p class="name" data-dz-name></p><strong class="error text-danger" data-dz-errormessage></strong></div>

                            <div>
                                <p class="size" data-dz-size></p>
                                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-primary start"><i class="glyphicon glyphicon-upload"></i><span> Yükle</span></button>
                                <button data-dz-remove class="btn btn-warning cancel"><i class="glyphicon glyphicon-ban-circle"></i><span> İptal</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection


@section('custom_js')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="{{ asset('backend/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
    <script src="{{ asset('backend/js/pages/advanced-form-element.js') }}"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script>
        var previewNode = document.querySelector("#template");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: "{{ route('enewspaperimagespost', $photogallery->id) }}", // Set the url
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button", // Define the element that should be used as click trigger to select files.
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.webp",
            //params: { imagestitle: $('.imagestitle').val() }
        });

        myDropzone.on("addedfile", function(file) {file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };});
        myDropzone.on("totaluploadprogress", function(progress) {document.querySelector("#total-progress .progress-bar").style.width = progress + "%"; });
        myDropzone.on("sending", function(file) {
            document.querySelector("#total-progress").style.opacity = "1"; file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
        });
        myDropzone.on("queuecomplete", function(progress) { document.querySelector("#total-progress").style.opacity = "0"; });
        document.querySelector("#actions .start").onclick = function() {myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));};
        document.querySelector("#actions .cancel").onclick = function() {myDropzone.removeAllFiles(true);};


        $('.imageupdatebutton').click(function (){
            var imageid = $(this).attr("id"), imagetext = $('#imagetextarea_'+imageid).val();
            $.ajax({
                //url: sitelocal+"/photogalleryimage/update/"+imageid,
                url: "{{ route('enewspaperimageupdateNotID') }}",
                type:"POST",
                data:{ "_token": "{{ csrf_token() }}", imageid:imageid, imagetext:imagetext }, success:function(response){}, error: function(response) {},
            });
        });
        $('.imagedeletebutton').click(function (){
            var imageid = $(this).attr("id");
            var sitelocal = "http://localhost:8888/ershaber/public/secure";
            $.ajax({
                url: "{{ route('enewspaperimagedeleteNotID') }}",
                type:"POST",
                data:{ "_token": "{{ csrf_token() }}", imageid:imageid},
                success:function(response){
                    $(".imgrow"+imageid).remove();
                },
                error: function(response) {},
            });
        });


        $(document).ready(function() {
            $(function () {
                $("#sortable").sortable({
                    stop: function( event, ui ) {
                        $( "#sortable .ui-state-default" ).each(function( index ) {
                            var imageid = $( this ).data('id'), sortby = index;
                            $.ajax({
                                url: "{{ route('enewspaperimagesortby') }}",
                                type:"POST",
                                data:{ "_token": "{{ csrf_token() }}", imageid:imageid, sortby: index },
                                success:function(response){
                                    console.log(response);
                                },
                                error: function(response) {},
                            });
                        });
                    }
                });
            });
        });


    </script>
@endsection
