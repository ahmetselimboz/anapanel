@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Video</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('video.create') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Video Ekle</a>
                                <a href="{{ route('video.trashed') }}" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Çöp Kutusu</a>
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
                                    <th>Kategorisi</th>
                                    <th>Oluşturma Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                                @foreach($videos as $video)
                                    <tr>
                                        <td>{{ $video->id }}</td>
                                        <td>{{ html_entity_decode($video->title) }}</td>
                                        <td><span class="badge badge-primary">@if($video->category_id!=0 and $video->category!=null) {{ $video->category->title }} @else KATEGORİSİZ @endif</span></td>
                                        <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y', strtotime($video->created_at)) }} </span> </td>
                                        <td>
                                            <div class="clearfix">
                                                <a target="_blank" href="{{ route('video_gallery', ['categoryslug'=>$video->category->slug,'slug'=>$video->slug,'id'=>$video->id]) }}" type="button" class="waves-effect waves-light btn btn-bitbucket pe-2 me-1 btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Görüntüle"><i class="fa fa-external-link"></i></a>
                                                <a href="{{ route('video.edit', $video->id) }}" type="button" class="waves-effect waves-light btn btn-secondary btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"><i class="fa fa-pencil"></i></a>
                                                <a href="{{ route('video.destroy', $video->id) }}" type="button" class="waves-effect waves-light btn btn-danger btn-sm pull-left ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Çöpe At"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            {{ $videos->links() }}
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection




