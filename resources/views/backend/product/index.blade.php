@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Ürünler</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('product.create') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Ürün Ekle</a>
                                <a href="{{ route('product.trashed') }}" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Çöp Kutusu</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive mx-2">
                            <table class="table table-hover">
                                <tr>
                                    <th>ID</th>
                                    <th>Resim</th>
                                    <th>Başlık</th>
                                    <th>Kategori</th>
                                    <th>Fiyat</th>
                                    <th>Hit</th>
                                    <th>Oluşturma Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                                @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td><img src="{{ asset('uploads/'.firstImage($product->id)) }}" alt="" width="35"></td>
                                    <td>{{ $product->title }}</td>
                                    <td>{{ $product->category->title }}</td>
                                    <td>{{ $product->price }} </td>
                                    <td>{{ $product->hit }}</td>
                                    <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y', strtotime($product->created_at)) }} </span> </td>
                                    <td>
                                        <div class="clearfix">
                                            <a href="{{ route('product.edit', $product->id) }}" type="button" class="waves-effect waves-light btn btn-secondary btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"><i class="fa fa-pencil"></i></a>
                                            <a href="{{ route('product.destroy', $product->id) }}" type="button" class="waves-effect waves-light btn btn-danger btn-sm pull-left ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Çöpe At"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $products->links() }}
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection




