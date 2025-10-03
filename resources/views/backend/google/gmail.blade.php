@extends('backend.layout')


@section('content')

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Gmail</h4>

                        <div class="box-controls pull-right">
                            <div class="btn-group">


                                @if(count($emails)> 0)
                                    <ul class="d-flex flex-row justify-content-center align-items-center m-0 p-0">
                                        @if ($prevPageToken)
                                            <div class="me-1">
                                                <button class="btn btn-sm btn-secondary  d-flex align-items-center"

                                                        onclick="window.history.back()"
                                                        aria-label="Önceki">
                                                <span aria-hidden="true" class="d-flex align-items-center">
                                                    <i data-feather="arrow-left" style="height: 16px"></i>
                                                    Önceki Sayfa
                                                </span>
                                                </button>
                                            </div>
                                        @else
                                            <div class="me-1">

                                            <span class="bg-secondary btn-sm  d-flex align-items-center"
                                                  style="opacity: 50%"><i data-feather="arrow-left" style="height: 16px">></i> Önceki Sayfa</span>
                                            </div>
                                        @endif


                                        @if ($nextPageToken)
                                            <div class="">
                                                <a class="btn btn-sm btn-secondary d-flex align-items-center"
                                                   href="{{ route('gmail', ['pageToken' => $nextPageToken]) }}"
                                                   aria-label="Sonraki">
                                                <span aria-hidden="true" class="d-flex align-items-center">
                                                    Sonraki Sayfa
                                                    <i data-feather="arrow-right" style="height: 16px"></i>
                                                </span>
                                                </a>
                                            </div>
                                        @else
                                            <div class="disabled">
                                            <span class="bg-secondary btn-sm  d-flex align-items-center"
                                                  style="opacity: 50%">
                                                Sonraki Sayfa
                                               <i data-feather="arrow-right" style="height: 16px">></i>
                                            </span>
                                            </div>
                                        @endif
                                    </ul>
                                    <a href="{{ route('logoutGoogle') }}" type="button"
                                       class="btn btn-danger btn-sm mx-xl-3 d-flex align-items-center"><i
                                                data-feather="log-out" class="me-1"></i> Google Gmail Çıkış Yap</a>
                                @else
                                    <a href="{{ route('google.connect') }}" type="button"
                                       class="btn btn-primary btn-sm mx-xl-3  d-flex align-items-center">
                                        <i data-feather="log-in" class="me-1"></i>
                                        Google Hesabını Bağla
                                    </a>


                                @endif

                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        @if(count($emails)> 0)
                            <div class="row"> <!-- Row eklendi -->
                                @foreach ($emails as $email)
                                    <div class="col-md-6 col-lg-6"> <!-- Daha iyi düzen için col-lg-4 -->
                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                <strong>{{ $email['from'] }}</strong>
                                            </div>
                                            <div class="card-body">
                                                <h6 class="card-subtitle my-2 text-muted ">{{ $email['date'] }}</h6>
                                                <p class="card-text">{{ $email['subject'] }}</p>
                                                <div class="d-flex flex-row align-items-center justify-content-center mt-2">

                                                    <a href="#"
                                                       class="btn btn-sm btn-info detay-btn me-1 d-flex align-items-center"
                                                       data-id="{{ $email['id'] }}"
                                                       data-from="{{ $email['from'] }}"
                                                       data-subject="{{ $email['subject'] }}"
                                                       data-date="{{ $email['date'] }}"
                                                       data-body="{{ htmlspecialchars($email['snippet']) }}">
                                                        <i data-feather="eye" class="me-1"></i>
                                                        İçeriği Gör
                                                    </a>
                                                    <form action="{{route("getGmailPost")}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="detail" value="{{ $email['snippet'] }}">
                                                        <button type="submit"
                                                                class="btn btn-sm btn-success d-flex align-items-center">
                                                            <i data-feather="plus"></i>
                                                            Habere Ekle
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <ul class="d-flex flex-row justify-content-center align-items-center mb-4 p-0">
                                @if ($prevPageToken)
                                    <div class="me-1">
                                        <button class="btn btn-sm btn-secondary  d-flex align-items-center"

                                                onclick="window.history.back()"
                                                aria-label="Önceki">
                                            <span aria-hidden="true"><i data-feather="arrow-left" style="height: 16px"></i> Önceki Sayfa</span>
                                        </button>
                                    </div>
                                @else
                                    <div class="me-1">

                                            <span class="bg-secondary btn-sm  d-flex align-items-center"
                                                  style="opacity: 50%"><i data-feather="arrow-left" style="height: 16px"></i> Önceki Sayfa</span>
                                    </div>
                                @endif


                                @if ($nextPageToken)
                                    <div class="">
                                        <a class="btn btn-sm btn-secondary d-flex align-items-center"
                                           href="{{ route('gmail', ['pageToken' => $nextPageToken]) }}"
                                           aria-label="Sonraki">
                                                <span aria-hidden="true" class="d-flex align-items-center">
                                                    Sonraki Sayfa
                                                    <i data-feather="arrow-right" style="height: 16px"></i>
                                                </span>
                                        </a>
                                    </div>
                                @else
                                    <div class="disabled">
                                            <span class="bg-secondary btn-sm  d-flex align-items-center"
                                                  style="opacity: 50%">
                                                Sonraki Sayfa
                                               <i data-feather="arrow-right" style="height: 16px"></i></span>
                                    </div>
                                @endif
                            </ul>
                            <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="emailModalLabel">E-Posta Detayları</h5>
                                            <div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Kimden:</strong> <span id="modal-from"></span></p>
                                            <p><strong>Konu:</strong> <span id="modal-subject"></span></p>
                                            <p><strong>Tarih:</strong> <span id="modal-date"></span></p>
                                            <hr>
                                            <p id="modal-body"></p>
                                        </div>
                                        <div class="modal-footer d-flex justify-content-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>

                                            <form action="{{route("getGmailPost")}}" method="post">
                                                @csrf
                                                <input type="hidden" name="detail" id="detail-input">
                                                <button type="submit"
                                                        class="btn btn-sm btn-success d-flex align-items-center">
                                                    <i data-feather="plus"></i>
                                                    Habere Ekle
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row align-items-center justify-content-center h-100">
                                <div class="col d-flex align-items-center justify-content-center">
                                    <span>Maillerinize erişmek için Google hesabınızı bağlayınız!</span>
                                </div>
                            </div>
                        @endif

                    </div>

                    <!-- /.box-body -->

                </div>


                <!-- Sayfalama -->


                <!-- FontAwesome ikonları için gerekli link -->


                <!-- /.box -->
            </div>
        </div>
    </section>




@endsection


@section('custom_js')
    <script>
        $(document).ready(function () {

            $('.detay-btn').on('click', function (event) {
                event.preventDefault();

                let from = $(this).data('from');
                let subject = $(this).data('subject');
                let date = $(this).data('date');
                let body = $(this).data('body');

                $('#modal-from').text(from);
                $('#modal-subject').text(subject);
                $('#modal-date').text(date);
                $('#modal-body').html(decodeURIComponent(body));
                $('#detail-input').val(body);
                $('#emailModal').modal('show');
            });
        });
    </script>
@endsection
