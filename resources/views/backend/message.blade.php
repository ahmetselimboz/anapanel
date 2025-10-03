@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">

            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">İletişim Talepleri</h4>
                    </div>


                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th>Ad Soyad</th>
                                    <th>E Posta</th>
                                    <th>Telefon</th>
                                    <th>Mesaj</th>
                                    <th>Tarih</th>
                                </tr>

                                @foreach($messages as $mkey => $message)
                                    <tr>
                                        <th scope="row">{{ $message->id }}</th>
                                        <td>{{ $message->name }}</td>
                                        <td>{{ $message->email }}</td>
                                        <td>{{ $message->phone }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary-light btn-sm" data-bs-toggle="modal" data-bs-target="#modal{{ $message->id }}">GÖRÜNTÜLE </button>
                                            <div class="modal fade" id="modal{{ $message->id }}">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">{{ $message->id }} Numaralı Mesaj</h4>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body"> <p>{{ $message->message }}</p> </div>
                                                        <div class="modal-footer"><button type="button" class="btn btn-danger" data-bs-dismiss="modal">KAPAT</button> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y H:i',strtotime($message->created_at)) }}</span> </td>
                                    </tr>
                                @endforeach
                            </table>
                            <div class="cnavigation">{{ $messages->links() }}</div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>


        </div>
    </section>
@endsection


