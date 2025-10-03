@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">E Gazete</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('enewspaper.create') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> E Gazete Ekle</a>
                                <a href="{{ route('enewspaper.trashed') }}" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Çöp Kutusu</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive mx-2">
                            <table class="table table-hover">
                                <tr>
                                    <th>ID</th>
                                    <th>Başlık</th>
                                    <th>Oluşturma Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                                @foreach($enewspapers as $enewspaper)
                                <tr>
                                    <td>{{ $enewspaper->id }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-primary" href="{{ route('enewspaperimages', $enewspaper->id) }}"> <i class="fa fa-external-link"></i> Görüntüle </a>
                                    </td>

                                    <td>{{ $enewspaper->title }}</td>
                                    <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y', strtotime($enewspaper->date)) }} </span> </td>
                                    <td>
                                        <div class="clearfix">
                                        <a target="_blank" href="{{route('home.enews-detail', $enewspaper->id)}}" type="button" class="waves-effect waves-light btn btn-bitbucket pe-2 me-1 btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Görüntüle"><i class="fa fa-external-link"></i></a>
                                            <a href="{{ route('enewspaper.edit', $enewspaper->id) }}" type="button" class="waves-effect waves-light btn btn-secondary btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"><i class="fa fa-pencil"></i></a>
                                            <a href="{{ route('enewspaper.destroy', $enewspaper->id) }}" type="button" class="waves-effect waves-light btn btn-danger btn-sm pull-left ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Çöpe At"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $enewspapers->links() }}
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection




