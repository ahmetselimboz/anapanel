@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Ürünler Çöp Kutusu</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('product.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Ürün Listesine Dön </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive mx-2">
                            <table class="table table-hover">
                                <tr>
                                    <th>Başlık</th>
                                    <th>Oluşturma Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->title }}</td>
                                        <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y', strtotime($product->created_at)) }} </span> </td>
                                        <td>
                                            <div class="clearfix">
                                                <a href="{{ route('product.restore', $product->id) }}" type="button" class="waves-effect waves-light btn btn-secondary btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Geri getir"><i class="fa fa-undo"></i></a>
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




