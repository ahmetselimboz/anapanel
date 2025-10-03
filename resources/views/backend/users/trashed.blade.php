@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Üyeler Çöp Kutusu</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('users.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Üye Listesine Dön </a>
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
                                    <th>Silinme Tarihi</th>
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
                                    <td><span class="badge  badge-success">Reklamcı</span></td>
                                    @elseif($user->status==3)
                                    <td><span class="badge  badge-warning">Editör</span></td>
                                    @elseif($user->status==4)
                                    <td><span class="badge  badge-info">Köşe Yazarı</span></td>
                                    @endif
                                    <td>
                                        <div class="d-flex">
                                                <a href="{{ route('users.restore', $user->id) }}" type="button" class="waves-effect waves-light btn btn-secondary btn-sm pull-left mx-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Geri getir"><i class="fa fa-undo"></i></a>

                                                <a href="{{ route('users.delete', $user->id) }}"
                                                    on type="button" class="waves-effect waves-light btn btn-danger  mx-1 btn-sm pull-left"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Sil"><i class="fa fa-trash"></i></a>
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




