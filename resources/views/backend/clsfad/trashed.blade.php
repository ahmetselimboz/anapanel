@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Seri İlanlar Çöp Kutusu</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('clsfad.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Seri İlanlar Listesine Dön </a>
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
                                    <th>Silinme Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                                @foreach($clsfads as $clsfad)
                                    <tr>
                                        <td>{{ $clsfad->id }}</td>
                                        <td>{{ $clsfad->title }}</td>
                                        <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y', strtotime($clsfad->created_at)) }} </span> </td>
                                        <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y', strtotime($clsfad->deleted_at)) }} </span> </td>
                                        <td>
                                            <div class="clearfix">
                                                <a href="{{ route('clsfad.restore', $clsfad->id) }}" type="button" class="waves-effect waves-light btn btn-secondary btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Geri getir"><i class="fa fa-undo"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection




