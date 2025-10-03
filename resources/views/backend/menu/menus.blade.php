@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Menüler</h4>
                        @if(count($menus)>0)
                            <div class="box-controls pull-right">
                                <div class="btn-group">
                                    <a href="{{ route('menusCreate') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Menü Ekle</a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive mx-2">
                            @if(count($menus)>0)
                                <table class="table table-hover">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Menü</th>
                                        <th scope="col">Eklenme Tarihi</th>
                                        <th scope="col">Düzenlenme Tarihi</th>
                                        <th scope="col">İşlem</th>
                                    </tr>
                                    @foreach($menus as $menu)
                                        <tr>
                                            <th scope="row">{{ $menu->id }}</th>
                                            <td>{{ $menu->title }}</td>
                                            <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y H:i:s', strtotime($menu->created_at)) }} </span> </td>
                                            <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y H:i:s', strtotime($menu->updated_at)) }} </span> </td>
                                            <td>
                                                <div class="clearfix">
                                                    <a href="{{ route('menusID', $menu->id) }}" type="button" class="waves-effect waves-light btn btn-secondary btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"><i class="fa fa-pencil"></i></a>
                                                    <a href="{{ route('menutrashed', $menu->id) }}" type="button" class="waves-effect waves-light btn btn-danger btn-sm pull-left ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Çöpe At"><i class="fa fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            @else
                                <div class="text-center my-2 py-20 bg-secondary">
                                    <div class="card-body">
                                        <a href="{{ route('menusCreate') }}" class="btn btn-primary-light">BİR MENÜ OLUŞTUR</a>
                                    </div>
                                </div>
                            @endif
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


@endsection




