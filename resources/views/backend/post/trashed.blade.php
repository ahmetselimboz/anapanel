@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Haberler Çöp Kutusu</h4>
                        <div class="dropdown d-inline-block ms-2" id="actionbox">
                            <button class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" href="#" aria-expanded="false">Seçilenleri</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item allprocess" href="#" id="all_delete" data-type="restore"><span class="badge badge-ring badge-success me-1"></span> GERİ YÜKLE </a>
                            </div>
                        </div>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('post.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Haber Listesine Dön </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive mx-2">
                            <table class="table table-hover">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="allselect" class="filled-in checkbox-toggle">
                                        <label for="allselect" class="mb-0 h-15 ms-15"></label>
                                    </th>
                                    <th>ID</th>
                                    <th>Başlık</th>
                                    <th>Oluşturma Tarihi</th>
                                    <th>Silinme Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                                @foreach($posts as $post)
                                    <tr>
                                        <td class="media-list media-list-divided media-list-hover">
                                            <div class="media align-items-center">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input newschecker" value="{{$post->id}}" name="newsid[]">
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $post->id }}</td>
                                        <td>{{ $post->title }}</td>
                                        <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y', strtotime($post->created_at)) }} </span> </td>
                                        <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y', strtotime($post->deleted_at)) }} </span> </td>
                                        <td>
                                            <div class="clearfix">
                                                <a href="{{ route('post.restore', $post->id) }}" type="button" class="waves-effect waves-light btn btn-secondary btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Geri getir"><i class="fa fa-undo"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            {!! $posts->links()  !!}
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
    <script src="{{ asset('backend/assets/vendor_plugins/iCheck/icheck.js') }}"></script>
    <script src="{{ asset('backend/js/pages/app-contact.js') }}"></script>


    <script type="text/javascript">
        $.ajaxSetup({headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') } });
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
