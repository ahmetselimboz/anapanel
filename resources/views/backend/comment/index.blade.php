@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Yorumlar</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('comment.trashed') }}" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Çöp Kutusu</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive mx-2">
                            <table class="table table-hover">
                                <tr>
                                    <th>ID</th>
                                    <th>Ad Soyad</th>
                                    <th>E Posta</th>
                                    <th>Durumu</th>
                                    <th>Yorum tipi</th>
                                    <th>Oluşturma Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                                @foreach($comments as $comment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $comment->title }}</td>
                                    <td>{{ $comment->email }}</td>
                                    <td>
                                        @if($comment->publish==0)
                                            <span class="badge badge-success">ONAYLI</span>
                                        @else
                                            <span class="badge badge-danger">ONAYSIZ</span>
                                        @endif
                                    </td>
                                    <td>
                                    @if($comment->type==0) <span class="badge badge-success">HABER</span>
                                    @elseif($comment->type==1) <span class="badge badge-primary">FOTO</span>
                                    @elseif($comment->type==2) <span class="badge badge-warning">VİDEO</span>
                                    @elseif($comment->type==3) <span class="badge badge-danger">MAKALE</span>
                                    @else <span class="badge badge-danger">YOK</span> @endif
                                    </td>

                                    <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y', strtotime($comment->created_at)) }} </span> </td>
                                    <td>
                                        <div class="clearfix">
                                            <a href="{{ route('comment.edit', $comment->id) }}" type="button" class="waves-effect waves-light btn btn-secondary btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"><i class="fa fa-pencil"></i></a>
                                            <a href="{{ route('comment.destroy', $comment->id) }}" type="button" class="waves-effect waves-light btn btn-danger btn-sm pull-left ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Çöpe At"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $comments->links() }}
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection




