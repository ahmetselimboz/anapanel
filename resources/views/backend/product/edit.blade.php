@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Ürün Düzenle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('product.index') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Ürün Listesine Dön </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Hata!</strong> Aşağıdaki hataları düzeltin.<br><br>
                            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
                        </div>
                    @endif

                    <form class="form" action="{{ route('product.update', $product->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Kategori</label>
                                        <div class="form-group">
                                            <select class="form-control select2" style="width: 100%;" name="category_id">
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" @if($category->id==$product->category_id) selected="selected" @endif>{{ $category->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Vitrinde Göster</label>
                                        <div class="form-group">
                                            <select class="form-control select2" style="width: 100%;" name="showcase">
                                                <option value="0" @if($product->showcase==0) selected="selected" @endif>Hayır</option>
                                                <option value="1" @if($product->showcase==1) selected="selected" @endif>Evet</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Fiyat (varsa)</label>
                                        <input type="text" class="form-control" placeholder="Sadece rakam" name="price"  value="{{ $product->price }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Başlık</label>
                                        <input type="text" class="form-control" placeholder="Başlık" name="title" value="{{ $product->title }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Okunma Sayısı (Hit)</label>
                                        <input type="text" class="form-control" placeholder="Rakam yazabilirsiniz" name="hit" value="{{ $product->hit }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Açıklama</label>
                                        <textarea id="" cols="30" rows="2" class="form-control" placeholder="Açıklama" name="description">{{ $product->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Detay</label>
                                        <textarea id="detail_editor" class="form-control" placeholder="Açıklama" name="detail">{!! $product->detail !!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class="form-label">Resim</label>
                                            <input type="file" class="form-control" placeholder="Resim" name="images[]" multiple>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Yayın Durumu</label>
                                        <select class="form-control select2" style="width: 100%;" name="publish">
                                            <option value="0" selected="selected">Aktif</option>
                                            <option value="1">Pasif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            @if(count($productimages)>0)
                                            <label class="form-label d-block">Mevcut Resimler</label>
                                            <div class="row">
                                                @foreach($productimages as $pimage)
                                                <div class="col-md-4">
                                                    <img src="{{ asset("uploads/".$pimage->image) }}" width="163" height="107">
                                                    <a href="{{ route('product_image_delete', $pimage->id) }}" class="badge badge-danger">SİL</a>
                                                </div>
                                                @endforeach
                                            </div>
                                            @else
                                                <label class="form-label d-block">Mevcut Resim Bulunamadı</label>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary"> <i class="ti-save-alt"></i> Kaydet </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection


@section('custom_js')
    <script src="{{ asset('backend/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
    <script src="{{ asset('backend/js/pages/advanced-form-element.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            "use strict";
            CKEDITOR.replace('detail_editor')
        });
    </script>
@endsection
