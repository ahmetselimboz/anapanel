@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Foto Galeriler</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('photogallery.create') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Foto Galeri Ekle</a>
                                <a href="{{ route('photogallery.trashed') }}" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Çöp Kutusu</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive mx-2">
                            <table class="table table-hover">
                                <tr>
                                    <th>ID</th>
                                    <th>Galeri Resimleri</th>
                                    <th>Başlık</th>
                                    <th>Kategorisi</th>
                                    <th>Oluşturma Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                                @foreach($photogalleries as $photogallery)
                                <tr>
                                    <td>{{ $photogallery->id }}</td>
                                    <td> <a class="btn btn-sm btn-primary" href="{{ route('photogalleryimages', $photogallery->id) }}"> <i class="fa fa-external-link"></i> Görüntüle </a> </td>
                                    <td>{{ html_entity_decode($photogallery->title) }}</td>
                                    <td><span class="badge badge-primary">@if($photogallery->category_id!=0 and $photogallery->category!=null) {{ $photogallery->category->title }} @else KATEGORİSİZ @endif</span></td>
                                    <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y', strtotime($photogallery->created_at)) }} </span> </td>
                                    <td>
                                        <div class="clearfix">
                                            @if ($photogallery->category)
                                            <a target="_blank" href="{{ route('photo_gallery', ['categoryslug'=>$photogallery->category->slug,'slug'=>$photogallery->slug,'id'=>$photogallery->id]) }}" type="button" class="waves-effect waves-light btn btn-bitbucket pe-2 me-1 btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Görüntüle"><i class="fa fa-external-link"></i></a>

                                            @endif
                                            <a href="{{ route('photogallery.edit', $photogallery->id) }}" type="button" class="waves-effect waves-light btn btn-secondary btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"><i class="fa fa-pencil"></i></a>
                                            <a href="{{ route('photogallery.destroy', $photogallery->id) }}" type="button" class="waves-effect waves-light btn btn-danger btn-sm pull-left ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Çöpe At"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $photogalleries->links() }}
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection




