@extends('backend.layout')

@section('content')
    <style>
        .menuItemTrash{font-size:18px;color: palevioletred;cursor: pointer;}
    </style>
    <section class="content">
        <div class="row">

            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Menüler</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('menusCreate') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Menü Ekle</a>
                                <a href="{{ route('menus') }}" type="button" class="btn btn-success btn-sm"><i class="fa fa-undo"></i> Menü Listesine Dön </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="accordion" id="accordionMenus">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header mt-0" id="headingCategories">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategories" aria-expanded="true" aria-controls="collapseCategories">
                                                Kategoriler
                                            </button>
                                        </h2>
                                        <div id="collapseCategories" class="accordion-collapse collapse show" aria-labelledby="headingCategories" data-bs-parent="#accordionMenus">
                                            <div class="accordion-body categorybox">
                                                @foreach($categories as $category)
                                                    <div class="d-flex align-items-center mb-10">
                                                        <div class="d-flex flex-column flex-grow-1">{{ $category->title }}</div>
                                                        <span class="addtoright fs-12"
                                                        data-url="{{ '/'.$category->slug}}"
                                                        data-type="category" data-name="{{$category->title}}" data-id="{{$category->id}}"> EKLE <i class="fa fa-arrow-right"></i>
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header mt-0" id="headingPages">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                                Sayfalar
                                            </button>
                                        </h2>
                                        <div id="collapsePages" class="accordion-collapse collapse" aria-labelledby="headingPages" data-bs-parent="#accordionMenus">
                                            <div class="accordion-body">
                                                @foreach($pages as $page)
                                                    <div class="d-flex align-items-center mb-10">
                                                        <div class="d-flex flex-column flex-grow-1">{{ $page->title }}</div>
                                                        <span class="addtoright fs-12"
                                                        data-url="{{ '/sayfa/'.$page->slug }}"
                                                        data-type="page" data-name="{{$page->title}}" data-id="{{$page->id}}"
                                                        > EKLE <i class="fa fa-arrow-right"></i>
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header mt-0" id="headingCustom">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCustom" aria-expanded="false" aria-controls="collapseCustom">
                                                Özel Bağlantılar
                                            </button>
                                        </h2>
                                        <div id="collapseCustom" class="accordion-collapse collapse" aria-labelledby="headingCustom" data-bs-parent="#accordionMenus">
                                            <div class="accordion-body">
                                                <div class="form-group"><input type="text" class="form-control" placeholder="Bağlantı Url https://" name="custom_url" ></div>
                                                <div class="form-group"><input type="text" class="form-control" placeholder="Bağlantı Başlığı" name="custom_title" ></div>
                                                <button type="submit" class="btn btn-primary btn-sm addtorightCustom fs-12"> EKLE <i class="fa fa-arrow-right"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="bg-primary-light">
                                    <div class="box-header with-border">
                                        <h4 class="box-title text-primary">Menü ismi</h4>
                                        <input type="text" name="menu_title" class="form-control w-200 d-inline-block ms-3" @if($menus->id!=null) value="{{ $menus->title }}" @endif >
                                        @if($menus->id!=null)
                                            <div class="box-controls pull-right">
                                                <div class="btn-group"><a href="{{ route('menutrashed', $menus->id) }}" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Menüyü Sil</a></div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="box-body">
                                        @if($menus->id!=null)
                                            <div class="myadmin-dd-empty dd" id="nestable2"><ol class="dd-list nestableclass">{!! $menus->menulistdata !!}</ol></div>
                                        @else
                                            <div class="myadmin-dd-empty dd" id="nestable2"><ol class="dd-list nestableclass"></ol></div>
                                        @endif
                                        <button type="submit" class="btn btn-primary btn-sm" id="menusave"> <i class="ti-save-alt"></i> Menüyü Kaydet </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>


        </div>
    </section>


@endsection

@section('custom_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <script src="{{ asset('backend/assets/vendor_components/nestable/jquery.nestable.js') }}"></script>
    <script type="text/javascript">

        $(function () {
            "use strict";

            $(document).ready(function () {
                // Nestable
                // BENİM YAZDIKLARIM
                $.ajaxSetup({headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') } });

                $(".addtoright").click(function (){
                    let  url = $(this).data("url");
                    let  type = $(this).data("type");
                    let  name = $(this).data("name");
                    let  id = $(this).data("id");
                    let  check = $(".nestableclass").find("[data-id='" + id + "'][data-type='" + type + "']");
                    // var check = $(".nestableclass").find("[data-id='" + id + "']");
                    if(check.length>0){
                        return false
                    }else{
                        $(".nestableclass").append('<li class="dd-item dd3-item" id="item'+id+'" data-url="'+url+'" data-type="'+type+'" data-name="'+name+'" data-id="'+id+'"> <div class="dd-handle dd3-handle"></div>\n' +
                            '<div class="dd3-content"> '+name+' <i class="fa fa-trash pull-right menuItemTrash" id="'+id+'"></i> </div>\n' +
                            '</li>');
                    }
                });

                $(".addtorightCustom").click(function (){
                    var url = $('input[name="custom_url"]').val(), type = "custom", name = $('input[name="custom_title"]').val(), id = "0";
                    if(!name){
                        return false;
                    }else{
                        $(".nestableclass").append('<li class="dd-item dd3-item" id="item'+id+'" data-url="'+url+'" data-type="'+type+'" data-name="'+name+'" data-id="'+id+'"> <div class="dd-handle dd3-handle"></div>\n' +
                            '<div class="dd3-content"> '+name+' <i class="fa fa-trash pull-right menuItemTrash" id="'+id+'"></i></div>\n' +
                            '</li>');
                    }
                });

                $(document).on("click",".menuItemTrash",function(e){
                    $(this).attr('id');
                    $("#item"+$(this).attr('id')).remove();
                })

                $("#menusave").click(function (){
                    var menu_title = $('input[name="menu_title"]').val();
                    var menulistdata = $('ol[class="dd-list nestableclass"]').html();
                    var serializedata = $('.dd').nestable('serialize');
                    @if($menus->id!=null) var menu_id = {{ $menus->id }}; @else var menu_id = null; @endif
                    $.ajax({
                        url: "{{ route('menusajax') }}",
                        type:"POST",
                        data:{ menu_id : menu_id ,menu_title : menu_title, menulistdata : menulistdata, serializedata : serializedata },
                        success:function(response){
                            if(response==="ok"){
                                toastr.options = {"closeButton": true, "newestOnTop": true, "positionClass": "toast-top-right"};
                                toastr.success("Menü güncellemesi yapıldı","Başarılı");
                                // location.reload();
                            }
                        },
                        error: function(response) {},
                    });
                });
                // BENİM YAZDIKLARIM

                var updateOutput = function (e) {
                    var list = e.length ? e : $(e.target), output = list.data('output');
                    if (window.JSON) {
                        output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
                    }
                    else { output.val('JSON browser support required for this demo.'); }
                };
                $('#nestable2').nestable({
                    group: 1
                }).on('change', updateOutput);

                updateOutput($('#nestable2').data('output', $('#nestable2-output')));

            });

        });


    </script>
@endsection




