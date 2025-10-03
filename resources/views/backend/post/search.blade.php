@extends('backend.layout')

@section('custom_css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <style> .badge-dot {width: 12px!important;height: 12px!important;} </style>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Aranan haber sonuçları</h4>
                        <div class="dropdown d-inline-block ms-2" id="actionbox">
                            <button class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" href="#" aria-expanded="false">Seçilenleri</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item allprocess" href="#" id="all_delete" data-type="delete"><span class="badge badge-ring badge-danger me-1"></span> SİL </a>
                                <a class="dropdown-item allprocess" href="#" id="all_passive" data-type="passive"><span class="badge badge-ring badge-secondary me-1"></span> PASİF YAP</a>
                                <a class="dropdown-item allprocess" href="#" id="all_active" data-type="active"><span class="badge badge-ring badge-info me-1"></span> AKTİF YAP</a>
                            </div>
                        </div>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                @if ($archive == 1)
                                    <a href="{{ route('post_archive') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Haber Arşiv Listesine Dön </a>
                                @else
                                    <a href="{{ route('post.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Haber Listesine Dön </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive mx-2">
                            <table class="table table-hover">
                                <tr>
                                    <th class="p-0">
                                        <input type="checkbox" id="allselect" class="filled-in checkbox-toggle">
                                        <label for="allselect" class="mb-0 h-15 ms-15"></label>
                                    </th>
                                    <th>ID</th>
                                    <th>Başlık</th>
                                    <th class="text-center">Kategori</th>
                                    <th class="text-center">Seo</th>
                                    <th class="text-center">Hit</th>
                                    <th class="text-center">Oluşturma Tarihi</th>
                                    <th class="text-center">Durumu</th>
                                    <th>İşlemler</th>
                                </tr>
                                @foreach($posts as $post)
                                <tr>
                                    <td class="media-list media-list-divided media-list-hover p-0">
                                        <div class="media align-items-center">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input newschecker" value="{{$post->id}}" name="newsid[]">
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $post->id }}</td>
                                    <td style="max-width: 380px;"><a class="ceditable" data-name="title" data-type="text" data-pk="{{ $post->id }}" data-title="Değiştir">{{ $post->title }}</a></td>
                                    <td class="text-center"><span class="badge badge-primary">@if($post->category!=null) {{ $post->category->title }} @else KATEGORİSİZ @endif</span></td>
                                    <td class="text-center">
                                        @if(strlen($post->meta_title)<58 or strlen($post->meta_description)<150)
                                            <span class="badge badge-dot badge-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Seo Uyumsuz"></span>
                                        @else
                                            <span class="badge badge-dot badge-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Seo Uyumlu"></span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $post->hit }}</td>
                                    <td class="text-center"><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y', strtotime($post->created_at)) }} </span> </td>
                                    <td class="text-center">
                                        @if($post->publish==0) <span class="badge badge-success"> AKTİF </span> @else <span class="badge badge-secondary"> PASİF </span> @endif
                                    </td>
                                    <td>
                                        <div class="clearfix">
                                            @if($post->category!=null)<a target="_blank" href="{{ route('post', ['categoryslug'=>$post->category->slug,'slug'=>$post->slug,'id'=>$post->id]) }}" type="button" class="waves-effect waves-light btn btn-bitbucket pe-2 me-1 btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Görüntüle"><i class="fa fa-external-link"></i></a>@endif
                                            <a href="{{ route('post.edit', $post->id) }}" type="button" class="waves-effect waves-light btn btn-secondary btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"><i class="fa fa-pencil"></i></a>
                                            <a href="{{ route('post.destroy', $post->id) }}" type="button" class="waves-effect waves-light btn btn-danger btn-sm pull-left ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Çöpe At"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $posts->links() }}
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection


@section('custom_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

    <script src="{{ asset('backend/assets/vendor_plugins/iCheck/icheck.js') }}"></script>
    <script src="{{ asset('backend/js/pages/app-contact.js') }}"></script>

    <script src="{{ asset('backend/assets/vendor_components/apexcharts-bundle/dist/apexcharts.js') }}"></script>
    <script src="{{ asset('backend/js/pages/widgets.js') }}"></script>


    <script type="text/javascript">
        $.ajaxSetup({headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') } });
        $.fn.editable.defaults.mode = 'inline';
        $('.ceditable').editable(
            {
                url: "{{ route('post.ajaxUpdate') }}",
                type: 'select',
                name: 'title',
                title: 'Değiştir',
                validate: function(value) {
                    if($.trim(value) == '') {
                        return 'Boş bırakılamaz.';
                    }
                }
            }
        );

        $(".allprocess").click(function (){
            var datatype = $(this).data("type");
            var array = [];
            $("input:checked").each(function() {
                array.push($(this).val());
                array = jQuery.grep(array, function(value) {return value != "on"; });
            });
            $.ajax({
                url: "{{ route('post.ajaxAllProcess') }}",
                type:"POST",
                data:{ "_token": "{{ csrf_token() }}", newsid:array, processtype: datatype },
                success:function(response){ if(response==="ok"){ location.reload();} },
                error: function(response) {},
            });
        });




    </script>
@endsection





