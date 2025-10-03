@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Üyeler</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('users.create') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Üye Ekle</a>
                                <a href="{{ route('users.trashed') }}" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Çöp Kutusu</a>
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
                                    <th>Üyelik Tarihi</th>
                                    <th>E Posta</th>
                                    <th>Yetkisi</th>
                                    <th>İşlemler</th>
                                </tr>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y', strtotime($user->created_at)) }} </span> </td>
                                    <td>{{ $user->email }}</td>
                                    @if($user->status==0)
                                    <td><span class="badge  badge-secondary">Standart</span></td>
                                    @elseif($user->status==1)
                                    <td><span class="badge  badge-primary">YÖNETİCİ</span></td>
                                    @elseif($user->status==2)
                                        <td><span class="badge  badge-warning">Editör</span></td>
                                    @elseif($user->status==3)
                                        <td><span class="badge  badge-info">Köşe Yazarı</span></td>
                                    @endif
                                    <td>
                                        <div class="clearfix">
                                            <a href="{{ route('users.edit', $user->id) }}" type="button" class="waves-effect waves-light btn btn-secondary btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"><i class="fa fa-pencil"></i></a>
                                            @if($user->status!=1)
                                                <a href="{{ route('users.destroy', $user->id) }}" type="button" class="waves-effect waves-light btn btn-danger btn-sm pull-left ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Çöpe At"><i class="fa fa-trash"></i></a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $users->links() }}
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection




