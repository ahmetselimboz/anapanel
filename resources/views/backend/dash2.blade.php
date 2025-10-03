@extends('backend.layout')

@section('content')
    <section class="content">

        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="row g-0 py-2">

                        <div class="col-12 col-lg-3">
                            <div class="box-body be-1 border-light">
                                <div class="flexbox mb-1">
						  <span>
							  <i class="fa fa-users fa-3x text-primary mb-3"></i><br>
							ÜYELER
						  </span>
                                    <span class="text-primary fs-40">{{ $count_user }}</span>
                                </div>
                                <div class="progress progress-xxs mt-10 mb-0">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 50%; height: 4px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-3 hidden-down">
                            <div class="box-body be-1 border-light">
                                <div class="flexbox mb-1">
						  <span>
							  <i class="fa fa-list-ul fa-3x mb-3 text-success"></i><br>
							HABERLER
						  </span>
                                    <span class="text-success fs-40">{{ $count_post }}</span>
                                </div>
                                <div class="progress progress-xxs mt-10 mb-0">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 50%; height: 4px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12 col-lg-3 d-none d-lg-block">
                            <div class="box-body be-1 border-light">
                                <div class="flexbox mb-1">
						  <span>
							  <i class="fa fa-file-image-o fa-3x mb-3 text-warning"></i><br>
							FOTO GALERİLER
						  </span>
                                    <span class="text-warning fs-40">{{ $count_photo_gallery }}</span>
                                </div>
                                <div class="progress progress-xxs mt-10 mb-0">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 50%; height: 4px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12 col-lg-3 d-none d-lg-block">
                            <div class="box-body">
                                <div class="flexbox mb-1">
						  <span>
							  <i class="fa fa-video-camera fa-3x mb-3 text-danger"></i><br>
							VİDEO GALERİLER
						  </span>
                                    <span class="text-danger fs-40">{{ $count_video_gallery }}</span>
                                </div>
                                <div class="progress progress-xxs mt-10 mb-0">
                                    <div class="progress-bar bg-danger" role="progressbar"
                                         style="width: 50%; height: 4px;" aria-valuenow="50" aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>

        <div class="row">

            @if(Auth::check() and Auth::user()->status==1)
                <div class="col-xl-5 col-12">
                    <div class="box">
                        <div class="box-header no-border pb-0">
                            <h4 class="box-title">DENETLEME BEKLEYENLER </h4>
                        </div>
                        <div class="box-body mb-1">
                            <div class="bs-3 my-3 ps-3 py-2 border-primary"> HABER BAŞLIK SORUNLARI <div class="box-controls pull-right"><a href="{{ route('seocheck', ['type'=>1]) }}" type="button" class="btn btn-warning btn-sm fs-10" target="_blank"><i class="fa fa-search"></i> İNCELE</a></div></div>
                            <div class="bs-3 my-3 ps-3 py-2 border-warning"> HABER AÇIKLAMA SORUNLARI <div class="box-controls pull-right"><a href="{{ route('seocheck', ['type'=>2]) }}" type="button" class="btn btn-warning btn-sm fs-10" target="_blank"><i class="fa fa-search"></i> İNCELE</a></div></div>
                            <div class="bs-3 my-3 ps-3 py-2 border-dark"> HABER ETİKET SORUNLARI <div class="box-controls pull-right"><a href="{{ route('seocheck', ['type'=>3]) }}" type="button" class="btn btn-warning btn-sm fs-10" target="_blank"><i class="fa fa-search"></i> İNCELE</a></div></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="box">
                        <div class="box-header no-border pb-0">
                            <h4 class="box-title">KATEGORİ/HABER ORANI</h4>
                        </div>
                        <div class="">

                            <div id="analytics_chartC"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-12">
                    <div class="box">

                        <style>
                            .ggg td{ font-size: 12px!important; }
                        </style>
                        <div class="box-body no-padding" id="ggg">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tbody>
                                    <tr>
                                        <th>TESPİT</th>
                                        <th>DURUM</th>
                                    </tr>
                                    @php
                                        $false = '<td style="padding: 7px;"><span class="badge badge-pill badge-danger">KONTROL</span></td>';
                                        $true = '<td style="padding: 7px;"><span class="badge badge-pill badge-success">HARİKA</span></td>';
                                    @endphp
                                    <tr>
                                        <td style="padding: 7px;">SİTE BAŞLIĞI</td>
                                        @if(isset($jsondata->title) and $jsondata->title!=null) {!! $true !!} @else {!! $false !!} @endif
                                    </tr>
                                    <tr>
                                        <td style="padding: 7px;">SİTE AÇIKLAMASI</td>
                                        @if(isset($jsondata->description) and $jsondata->description!=null) {!! $true !!} @else {!! $false !!} @endif
                                    </tr>
                                    <tr>
                                        <td style="padding: 7px;">SİTE LOGO</td>
                                        @if(isset($settings["logo"]) and $settings["logo"]!=null) {!! $true !!} @else {!! $false !!} @endif
                                    </tr>
                                    <tr>
                                        <td style="padding: 7px;">İLETİŞİM BİLGİSİ</td>
                                        @if(isset($jsondata->phone) and $jsondata->phone!=null) {!! $true !!} @else {!! $false !!} @endif
                                    </tr>
                                    <tr>
                                        <td style="padding: 7px;">API SERVİSLERİ</td>
                                        @if(isset($jsondata->apiservicestatus) and $jsondata->apiservicestatus==0) {!! $true !!} @else {!! $false !!} @endif
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Son Eklenenler</h4>
                        <ul class="box-controls pull-right">
                            <li class="dropdown">
                                <a href="{{ route('post.index') }}" class="btn btn-danger-light px-10 base-font">Tümünü Gör</a>
                            </li>
                        </ul>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tr>
                                    <th>ID</th>
                                    <th>Başlık</th>
                                    <th class="text-center">Kategori</th>
                                    <th class="text-center">Seo</th>
                                    <th class="text-center">Hit</th>
                                    <th class="text-center">Oluşturma Tarihi</th>
                                    <th>Durumu</th>
                                    <th>İşlemler</th>
                                </tr>
                                @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td style="max-width: 380px;"><a class="ceditable" data-name="title" data-type="text" data-pk="{{ $post->id }}" data-title="Değiştir">{{ $post->title }}</a></td>
                                        <td class="text-center"><span class="badge badge-primary">@if($post->category!=null) {{ $post->category->title }} @else KATEGORİSİZ @endif</span></td>
                                        <td class="text-center">
                                            @if(strlen($post->meta_title)<58 or strlen($post->meta_description)<150)
                                                <span class="badge badge-dot badge-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Seo Uyumsuz"></span>
                                            @else
                                                <span class="badge badge-dot badge-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Seo Uyumlu"></span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $post->hit }}</td>
                                        <td class="text-center"><span class="text-muted"><i class="fa fa-clock-o"></i> {{ date('d.m.Y', strtotime($post->created_at)) }} </span> </td>
                                        <td>
                                            @if($post->publish==0) <span class="badge badge-success"> AKTİF </span> @else <span class="badge badge-secondary"> PASİF </span> @endif
                                        </td>
                                        <td>
                                            <div class="clearfix">
                                                <a href="{{ route('post.edit', $post->id) }}" type="button" class="waves-effect waves-light btn btn-secondary btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"><i class="fa fa-pencil"></i></a>
                                                <a href="{{ route('post.destroy', $post->id) }}" type="button" class="waves-effect waves-light btn btn-danger btn-sm pull-left ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Çöpe At"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if(Auth::check() and Auth::user()->status==1)
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Sistem Kayıtları</h4>
                            <ul class="box-controls pull-right">
                                <li class="dropdown">
                                    <a href="{{ route('activitylogs') }}" class="btn btn-danger-light px-10 base-font">Tümünü Gör</a>
                                </li>
                            </ul>
                        </div>

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
                                            <td>{{$activitylog->causer?->name ?? "--" }}</td>
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
                                        @if($akey==19) @break @endif
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection


@section('custom_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

    <script src="{{ asset('backend/assets/vendor_plugins/iCheck/icheck.js') }}"></script>
    <script src="{{ asset('backend/js/pages/app-contact.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor_components/apexcharts-bundle/dist/apexcharts.js') }}"></script>

    <script type="text/javascript">
        $.ajaxSetup({headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') } });
        $.fn.editable.defaults.mode = 'inline';
        $('.ceditable').editable(
            {
                url: "{{ route('post.ajaxUpdate') }}",
                type: 'select',
                name: 'title',
                title: 'Değiştir',
                validate: function(value) {
                    if($.trim(value) == '') {
                        return 'Boş bırakılamaz.';
                    }
                }
            }
        );

        $(".allprocess").click(function (){
            var datatype = $(this).data("type");
            var array = [];
            $("input:checked").each(function() {
                array.push($(this).val());
                array = jQuery.grep(array, function(value) {return value != "on"; });
            });
            $.ajax({
                url: "{{ route('post.ajaxAllProcess') }}",
                type:"POST",
                data:{ "_token": "{{ csrf_token() }}", newsid:array, processtype: datatype },
                success:function(response){ if(response==="ok"){ location.reload();} },
                error: function(response) {},
            });
        });

        <?php
        if($series=="no"){ ?>
        var options = {
            series: ["0"],
            labels: ["bulunamadı"],
            chart: { width: 250, type: 'donut', },
            colors: ['#7367F0', ],
            dataLabels: { enabled: false },
            responsive: [{ breakpoint: 480, options: {chart: {width: 200}, legend: {show: false} } }],
            legend: {show: false}
        };
        <?php }else{ ?>
        var options = {
            series: [{!! str_replace("'","",$series) !!}],
            labels: [{!! $labels !!}],
            chart: { width: 250, type: 'donut', },
            colors: ['#7367F0', '#EA5455', '#3799fb','#5ec870'],
            dataLabels: { enabled: false },
            responsive: [{ breakpoint: 480, options: {chart: {width: 200}, legend: {show: false} } }],
            legend: {show: false}
        };
        <?php } ?>


        var chart = new ApexCharts(document.querySelector("#analytics_chartC"), options);
        chart.render();

    </script>
@endsection
