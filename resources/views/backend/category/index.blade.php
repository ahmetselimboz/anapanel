@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Kategoriler</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('category.create') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Kategori Ekle</a>
                                <a href="{{ route('category.trashed') }}" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Çöp Kutusu</a>
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
                                    <th>Tipi</th>
                                    <th>Oluşturma Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                                @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->title }}</td>
                                    <td>
                                        @if($category->category_type==0) <span class="badge badge-success">HABER</span>
                                        @elseif($category->category_type==1) <span class="badge badge-primary">FOTO</span>
                                        @elseif($category->category_type==2) <span class="badge badge-warning">VİDEO</span>
                                        @elseif($category->category_type==3) <span class="badge badge-dark">FİRMA REHBERİ</span>
                                        @elseif($category->category_type==4) <span class="badge badge-secondary">SERİ İLAN</span>
                                        @elseif($category->category_type==5) <span class="badge badge-info">RESMİ İLAN</span>
                                        @else <span class="badge badge-danger">YOK</span> @endif
                                    </td>
                                    <td><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y', strtotime($category->created_at)) }} </span> </td>
                                    <td>
                                        <div class="clearfix">
                                            <a target="_blank" href="{{ route('category', ['slug'=>$category->slug,'id'=>$category->id]) }}" type="button" class="waves-effect waves-light btn btn-bitbucket pe-2 me-1 btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Görüntüle"><i class="fa fa-external-link"></i></a>
                                            <a href="{{ route('category.edit', $category->id) }}" type="button" class="waves-effect waves-light btn btn-secondary btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"><i class="fa fa-pencil"></i></a>
                                            <a href="{{ route('category.destroy', $category->id) }}" type="button" class="waves-effect waves-light btn btn-danger btn-sm pull-left ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Çöpe At"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $categories->links() }}
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection




