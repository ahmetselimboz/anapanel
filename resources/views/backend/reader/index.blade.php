@extends('backend.layout')


@section('content')
    <section class="content">
        <div class="row">

            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Okuyucu Bilgileri</h4>
                    
                    </div>
                    @php
                        $index = 1;
                    @endphp

                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive mx-2">
                            <table class="table table-hover">
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Telefon</th>
                                    <th>İşlemler</th>
                                </tr>
                                @forelse($readers as $item)
                                    <tr>
                                        <td>{{ $index }}</td>
                                        <td>{{ $item['email'] ?? "---"}}</td>
                                        <td>{{ $item['phone_number'] ?? "---" }}</td>
                                        
                                        <td class="text-center d-flex justify-content-start align-items-center gap-2">
                                            <a href="{{ route('readers.delete', ['id'=> $item['id']]) }}"
                                                class="btn btn-danger btn-sm"> <i class="fa fa-trash"></i> </a>
                                        </td>
                                    </tr>
                                     @php
                                        $index++;
                                    @endphp
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            <i class="fa fa-desktop fa-2x d-block mb-2"></i>
                                            Henüz bir okuyucu bilgisi gelmemiş
                                        </td>
                                    </tr>
                                @endforelse
                            </table>
 {{$readers->links()}}
                        </div>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>


        </div>
    </section>
@endsection
