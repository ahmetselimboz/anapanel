@extends('backend.layout')


@section('content')
    <section class="content">
        <div class="row">

            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Paneller</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('panel.create.page') }}" type="button" class="btn btn-success btn-sm">
                                    <i class="fa fa-plus"></i> Panel Ekle
                                </a>


                            </div>
                        </div>
                    </div>
                    <style>
                        .btn-block {
                            width: 100%;
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                        }

                        .btn-block:hover {
                            box-shadow: none !important;
                        }

                        .w-100 {
                            width: 100% !important;
                        }
                    </style>

                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div id="accordion">
                            @forelse($panels as $key=>$item)
                                <div class="card">
                                    <div class="card-header" id="heading{{ $key }}">
                                        <button class="btn btn-link btn-block" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $key }}" aria-expanded="true"
                                            aria-controls="collapse{{ $key }}">
                                            <div class="row w-100">
                                                <div class="col-md-4 d-flex align-items-center justify-content-start">
                                                    <span>{{ $item->domain }}</span>
                                                </div>
                                                <div class="col-md-4 d-flex align-items-center justify-content-center">
                                                    @if ($item->status == true)
                                                        <span class="badge badge-success"><i class="fa fa-check"></i>
                                                            Aktif</span>
                                                    @else
                                                        <span class="badge badge-danger"><i class="fa fa-lock"></i>
                                                            Kilitli</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-4 d-flex align-items-center justify-content-end">
                                                    <i class="fa fa-chevron-down"></i>
                                                </div>
                                            </div>



                                        </button>
                                    </div>

                                    <div id="collapse{{ $key }}" class="collapse"
                                        aria-labelledby="heading{{ $key }}" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row w-100">
                                                <div class="col-md-4">
                                                   
                                                </div>
                                              
                                                <div class="col-md-4 d-flex justify-content-center align-items-center">
                                                    <div class="d-flex flex-column gap-2 justify-content-center align-items-center">
                                                    <a href="{{ route('readers.index', ['slug' => $item->slug]) }}"
                                                        class="btn btn-info btn-sm">
                                                            <i class="fa fa-user"></i>
                                                        </a>
                                                        <small class="text-muted">Okur Bilgileri</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="d-flex flex-column gap-2 justify-content-center align-items-end">
                                                        
                                                        <a href="{{ route('panel.edit', ['slug' => $item->slug]) }}"
                                                        class="btn btn-secondary btn-sm" data-bs-toggle="tooltip"
                                                        title="Düzenle">
                                                        <i class="fa fa-pencil"></i>
                                                        Düzenle
                                                    </a>
                                                    <a href="{{ route('panel.delete', ['slug' => $item->slug]) }}"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="fa fa-trash"></i>
                                                        Sil
                                                    </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="card">
                                    <div class="card-body">
                                        <i class="fa fa-desktop fa-2x d-block mb-2"></i>
                                        Henüz bir panel eklenmemiş
                                    </div>
                                </div>
                            @endforelse



                        </div>
                    
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>


                
            </div>
    </section>
@endsection
