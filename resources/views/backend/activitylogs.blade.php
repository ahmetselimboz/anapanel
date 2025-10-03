@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">

            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Sistem Kayıtları</h4>
                    </div>


                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th>İŞLEM</th>
                                    <th>MODÜL</th>
                                    <th>İŞLEM YAPAN</th>
                                    <th>İŞLEM DETAYI</th>
                                    <th>İŞLEM ZAMANI</th>
                                </tr>

                                @foreach($activitylogs as $akey => $activitylog)
                                    <tr>
                                        <th scope="row">{{ $activitylog->id }}</th>
                                        <td>
                                            @if($activitylog->event=="deleted")
                                                <span class="badge badge-danger">SİLME</span>
                                            @elseif($activitylog->event=="created")
                                                <span class="badge badge-success">OLUŞTURMA</span>
                                            @elseif($activitylog->event=="updated")
                                                <span class="badge badge-warning">GÜNCELLEME</span>
                                            @endif
                                        </td>
                                        <td>{{ $activitylog->subject_type }}</td>
                                        <td>{{ hasUser($activitylog->causer_id) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary-light btn-sm" data-bs-toggle="modal" data-bs-target="#modal{{ $activitylog->id }}">GÖRÜNTÜLE </button>
                                            <div class="modal fade" id="modal{{ $activitylog->id }}">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">{{ $activitylog->id }} Numaralı Sistem Kaydı</h4>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body"> <p>{{ $activitylog->properties }}</p> </div>
                                                        <div class="modal-footer"><button type="button" class="btn btn-danger" data-bs-dismiss="modal">KAPAT</button> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y H:i',strtotime($activitylog->created_at)) }}</span> </td>
                                    </tr>
                                @endforeach
                            </table>
                            <div class="cnavigation">{{ $activitylogs->links() }}</div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>


        </div>
    </section>
@endsection


