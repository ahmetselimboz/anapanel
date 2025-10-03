@extends('backend.layout')

@section('custom_css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


    <style>
        .badge-dot {
            width: 12px !important;
            height: 12px !important;
        }

        .custom-switch-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custom-switch {
            position: relative;
            display: inline-block;
            width: 80px;
            height: 34px;
            cursor: pointer;
        }

        .custom-switch-input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .custom-switch-slider {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #f44336;
            /* Kırmızı (Pasif durum) */
            transition: .4s;
            border-radius: 34px;
            display: flex;
            align-items: center;
        }

        .custom-switch-slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-text {
            color: white;
            font-size: 12px;
            position: absolute;
            right: 10px;
            font-weight: bold;
        }

        .custom-switch-input:checked+.custom-switch-slider {
            background-color: #4CAF50;
            /* Yeşil (Aktif durum) */
        }

        .custom-switch-input:checked+.custom-switch-slider:before {
            transform: translateX(46px);
        }

        .custom-switch-input:checked+.custom-switch-slider .status-text {
            left: 10px;
            right: auto;
        }

        .mobile-relative {
            position: absolute !important;
        }

        @media (max-width: 768px) {
            .mobile-relative {
                position: relative !important;
            }
        }
    </style>
@endsection

@section('content')
    @include('backend.post.stats_popup')
    @include('backend.post.share_popup')

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <div class="  d-flex  flex-md-row flex-column d-inline-block ms-2 gap-2" id="actionbox">
                            <h4 class="box-title">Haberler</h4> &nbsp;

                            <button class="btn btn-primary btn-sm dropdown-toggle " data-bs-toggle="dropdown" href="#"
                                aria-expanded="false">Seçilenleri</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item allprocess" href="#" id="all_delete" data-type="delete"><span
                                        class="badge badge-ring badge-danger me-1"></span> SİL </a>
                                <a class="dropdown-item allprocess" href="#" id="all_passive"
                                    data-type="passive"><span class="badge badge-ring badge-secondary me-1"></span> PASİF
                                    YAP</a>
                                <a class="dropdown-item allprocess" href="#" id="all_active" data-type="active"><span
                                        class="badge badge-ring badge-info me-1"></span> AKTİF YAP</a>
                            </div>
                            <div class="">
                                <select id="category_select" class="form-control " onchange="filterCategory()">
                                    <option value="">Bir kategori Seçiniz</option>
                                    <option value="all">Tümü</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                                    @endforeach

                                </select>


                            </div>

                            <form method="GET" action="{{ route('post.index') }}" class="d-flex">
                                <select class="form-select" aria-label="Aktif/Pasif Filtrele" name="filter-status"
                                    onchange="this.form.submit()">
                                    <option value="">Aktif/Pasif Filtrele</option>
                                    <option value="0" {{ request('filter-status') == '0' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="1" {{ request('filter-status') == '1' ? 'selected' : '' }}>Pasif
                                    </option>
                                </select>
                                <input type="text" id="date_range" name="date_range" class="form-control ms-2"
                                    placeholder="Tarih aralığı seç" value="{{ request('date_range') }}"
                                    onchange="this.form.submit()" />
                            </form>
                            <a href="{{ route('trends') }}" class="btn btn-warning btn-sm ">Google Trendler</a>
                        </div>


                        <div class="box-controls pull-right mobile-relative">
                            <div class="btn-group">
                                <a href="{{ route('post_archive') }}" type="button"
                                    class="btn btn-bitbucket btn-sm mx-xl-3"><i class="fa fa-archive"></i> Arşiv </a>
                                <a href="{{ route('post.create') }}" type="button" class="btn btn-success btn-sm"><i
                                        class="fa fa-plus"></i> Haber Ekle</a>
                                <a href="{{ route('post.trashed') }}" type="button" class="btn btn-danger btn-sm"><i
                                        class="fa fa-trash"></i> Çöp Kutusu</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive mx-2">
                            <table class="table table-hover">
                                <tr>
                                    <th class="p-0">
                                        <input type="checkbox" id="allselect" class="filled-in checkbox-toggle">
                                        <label for="allselect" class="mb-0 h-15 ms-15"></label>
                                    </th>
                                    <th>ID</th>
                                    <th>Başlık</th>
                                    <th class="text-center px-0">Kategori</th>
                                    <th>Kaynak</th>
                                    <th class="text-center px-0">İstatistikler</th>
                                    <th class="text-center">Google'da Ara</th>
                                    <th class="text-center">Oluşturma Tarihi</th>
                                    <th class="text-center">Durumu</th>
                                    <th class="text-center">Paylaş</th>
                                    <th>İşlemler</th>
                                </tr>
                                @foreach ($posts as $post)
                                    @php
                                        $extra = json_decode($post->extra, true);
                                    @endphp
                                    <tr>
                                        <td class="media-list media-list-divided media-list-hover p-0">
                                            <div class="media align-items-center">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input newschecker"
                                                        value="{{ $post->id }}" name="newsid[]">
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $post->id }}</td>
                                        <td style="max-width: 300px;"><a class="ceditable" data-name="title"
                                                data-type="text" data-pk="{{ $post->id }}"
                                                data-title="Değiştir">{{ $post->title }}</a></td>
                                        <td class="text-center px-0"><span class="badge badge-primary">
                                                @if ($post->category_id != null)
                                                    {{ $post['category']->title }}
                                                @else
                                                    KATEGORİSİZ
                                                @endif
                                            </span></td>
                                        <td class="text-center px-0"><span class="">
                                                {{ isset($extra['news_source']) ? $extra['news_source'] : '--' }}


                                            </span>
                                        </td>
                                        <td class="text-center px-0">
                                            <button class="btn btn-info btn-sm p-1 openModal"
                                                data-id="{{ $post->id }}"
                                                data-url="/{{ $post->category->slug }}/{{ $post->slug }}/{{ $post->id }}"
                                                data-title="{{ $post->title }}">
                                                <i data-feather="bar-chart"></i>
                                            </button>

                                        </td>
                                        <td class="text-center px-0" style="width: 50px;">
                                            <button class="btn btn-warning btn-sm p-1"
                                                onclick="googlePopup(@js($post->title))">
                                                <i data-feather="search"></i>
                                            </button>
                                        </td>

                                        <td class="text-center px-0" style="width: 50px;">
                                            <span class="text-muted"><i class="fa fa-clock-o"></i>
                                                {{ date('d.m.Y', strtotime($post->created_at)) }}
                                            </span>
                                        </td>
                                        {{-- <td class="text-center">
                                            @if ($post->publish == 0)
                                            <span class="badge badge-success"> AKTİF </span>
                                            @else
                                            <span class="badge badge-secondary"> PASİF </span>
                                            @endif
                                        </td> --}}
                                        <td class="text-center">
                                            <div class="custom-switch-container">
                                                <label class="custom-switch">
                                                    <input type="checkbox" class="custom-switch-input"
                                                        data-id="{{ $post->id }}"
                                                        {{ $post->publish == 0 ? 'checked' : '' }}>
                                                    <span class="custom-switch-slider">
                                                        <span
                                                            class="status-text">{{ $post->publish == 0 ? 'Aktif' : 'Pasif' }}</span>
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-center position-relative">
                                            <button type="button" class="btn btn-secondary btn-sm p-1"
                                                data-bs-toggle="modal" data-bs-target="#shareModal"
                                                data-id="{{ $post->id }}"
                                                data-categorySlug="{{ $post->category->slug }}"
                                                data-slug="{{ $post->slug }}">
                                                <i data-feather="share"></i>
                                            </button>

                                            <div class="shareArea position-absolute d-none justify-content-center gap-2 bg-white p-2 rounded-3 shadow-md"
                                                style="top:50px;z-index:1000;">
                                                @php
                                                    $url =
                                                        config('app.url') .
                                                        '/' .
                                                        $post->category->slug .
                                                        '/' .
                                                        $post->slug .
                                                        '/' .
                                                        $post->id;
                                                    $text = '';

                                                    $facebookShare = clone Share::page($url, $text);
                                                    $twitterShare = clone Share::page($url, $text);
                                            
                                                @endphp

                                                {{-- Facebook --}}
                                                <a href="{{ $facebookShare->facebook()->getRawLinks() }}" target="_blank"
                                                    rel="noreferrer" class="btn btn-info btn-sm p-1">
                                                    <i data-feather="facebook"></i>
                                                </a>

                                                {{-- Twitter --}}
                                                <a href="{{ $twitterShare->twitter()->getRawLinks() }}" target="_blank"
                                                    rel="noreferrer" class="btn btn-dark btn-sm p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24"
                                                        height="24" viewBox="0 0 50 50" style="fill:#FFFFFF;">
                                                        <path
                                                            d="M 5.9199219 6 L 20.582031 27.375 L 6.2304688 44 L 9.4101562 44 L 21.986328 29.421875 L 31.986328 44 L 44 44 L 28.681641 21.669922 L 42.199219 6 L 39.029297 6 L 27.275391 19.617188 L 17.933594 6 L 5.9199219 6 z M 9.7167969 8 L 16.880859 8 L 40.203125 42 L 33.039062 42 L 9.7167969 8 z">
                                                        </path>
                                                    </svg>

                                                </a>

                                            
                                            </div>

                                        </td>


                                        <td>
                                            <div class="clearfix">
                                                @if ($post['category'] != null)
                                                    <a target="_blank"
                                                        href="{{ route('post', ['categoryslug' => $post->category->slug, 'slug' => $post->slug, 'id' => $post->id]) }}"
                                                        type="button"
                                                        class="waves-effect waves-light btn btn-bitbucket pe-2 me-1 btn-sm pull-left"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Görüntüle"><i class="fa fa-external-link"></i></a>
                                                @endif
                                                <a href="{{ route('post.edit', $post->id) }}" type="button"
                                                    class="waves-effect waves-light btn btn-secondary btn-sm pull-left"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"><i
                                                        class="fa fa-pencil"></i></a>
                                                <a href="{{ route('post.destroy', $post->id) }}" type="button"
                                                    class="waves-effect waves-light btn btn-danger btn-sm pull-left ms-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Çöpe At"><i
                                                        class="fa fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            {{ $posts->links() }}
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js">
    </script>
    {{--
    <script src="https://cdn.jsdelivr.net/npm/share.js@2.0.0/share.min.js">
    </script> --}}

    <script src="{{ asset('backend/assets/vendor_plugins/iCheck/icheck.js') }}"></script>
    <script src="{{ asset('backend/js/pages/app-contact.js') }}"></script>

    <script src="{{ asset('backend/assets/vendor_components/apexcharts-bundle/dist/apexcharts.js') }}"></script>
    <script src="{{ asset('backend/js/pages/widgets.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/rangePlugin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/tr.js"></script>

    <script>
        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "Y-m-d", // Laravel'e gönderilecek format (gizli)
            altInput: true, // Kullanıcıya gösterilecek alan
            altFormat: "d.m.Y", // Kullanıcının göreceği format (örn. 24.06.2025)
            locale: "tr", // Türkçe dil desteği
        });

        $('#date_range').next('input').css('cursor', 'pointer');
    </script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.fn.editable.defaults.mode = 'inline';
        $('.ceditable').editable({
            url: "{{ route('post.ajaxUpdate') }}",
            type: 'select',
            name: 'title',
            title: 'Değiştir',
            validate: function(value) {
                if ($.trim(value) == '') {
                    return 'Boş bırakılamaz.';
                }
            }
        });

        $(".allprocess").click(function() {
            var datatype = $(this).data("type");
            var array = [];
            $("input:checked").each(function() {
                array.push($(this).val());
                array = jQuery.grep(array, function(value) {
                    return value != "on";
                });
            });
            $.ajax({
                url: "{{ route('post.ajaxAllProcess') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    newsid: array,
                    processtype: datatype
                },
                success: function(response) {
                    if (response === "ok") {
                        location.reload();
                    }
                },
                error: function(response) {},
            });
        });

        function googlePopup(baslik) {
            const query = encodeURIComponent(baslik);
            const googleUrl = `https://www.google.com/search?q=${query}`;
            window.open(googleUrl, '_blank', 'width=900,height=700');
        }




        $(document).ready(function() {
            $('.custom-switch-input').on('change', function() {
                let isChecked = $(this).prop('checked');
                let postId = $(this).data('id');
                let newStatus = isChecked ? 'aktif' : 'pasif';

                // Eğer pasiften aktife geçiyorsa (checkbox seçildiyse)
                if (isChecked) {
                    // Doğrudan durumu güncelle, popup gösterme
                    updateStatus($(this), postId, isChecked);
                    return;
                }

                // Aktiften pasife geçiyorsa, önceki duruma getir ve onay iste
                let previousState = !isChecked;
                $(this).prop('checked', previousState);

                // Popup göster (sadece aktiften pasife geçerken)
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: `Bu öğeyi ${newStatus} durumuna getirmek istediğinizden emin misiniz?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Evet',
                    cancelButtonText: 'Hayır'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Kullanıcı onayladı, checkbox durumunu güncelle
                        $(this).prop('checked', isChecked);

                        // Durum güncelleme fonksiyonunu çağır
                        updateStatus($(this), postId, isChecked);
                    }
                });
            });

            // Durum güncelleme işlemini yapmak için helper fonksiyon
            function updateStatus(element, postId, isChecked) {
                // Durum metnini güncelle
                let statusText = isChecked ? 'Aktif' : 'Pasif';
                element.siblings('.custom-switch-slider').find('.status-text').text(statusText);

                // AJAX ile sunucuya durum değişikliğini gönder
                $.ajax({
                    url: '/secure/post/update-status', // Durum güncelleme rotanız
                    type: 'POST',
                    data: {
                        id: postId,
                        publish: isChecked ? 0 : 1,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Başarılı işlem bildirimi (isteğe bağlı)
                        // Swal.fire('Başarılı!', 'Durum başarıyla güncellendi.', 'success');
                    },
                    error: function(error) {
                        // Hata durumunda checkbox'ı eski haline getir
                        element.prop('checked', !isChecked);
                        element.siblings('.custom-switch-slider').find('.status-text').text(isChecked ?
                            'Pasif' : 'Aktif');
                        Swal.fire('Hata!', 'Durum güncellenirken bir hata oluştu.', 'error');
                    }
                });
            }
        });


        $(document).ready(function() {
            $(document).on('click', function(e) {
                if ($(e.target).closest('.shareButton, .shareArea').length === 0) {
                    $('.shareArea').fadeOut(200).removeClass('d-flex').addClass('d-none');
                }
            });

            $('.shareButton').on('click', function(e) {
                e.stopPropagation();

                const $shareArea = $(this).next('.shareArea');

                $('.shareArea').not($shareArea).fadeOut(200).removeClass('d-flex').addClass('d-none');

                if ($shareArea.hasClass('d-none')) {
                    $shareArea.removeClass('d-none').addClass('d-flex').hide().fadeIn(200);
                } else {
                    $shareArea.fadeOut(200, function() {
                        $(this).removeClass('d-flex').addClass('d-none');
                    });
                }
            });


        });



        $('#category_select').on('change', function() {
            let selectedCategory = $(this).val();
            window.location.href = '?category=' + selectedCategory;
        });
    </script>
@endsection
