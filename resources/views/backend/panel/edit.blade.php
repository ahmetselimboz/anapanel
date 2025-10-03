@extends('backend.layout')



@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">{{ $panel->title }} Düzenle</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('panels.index') }}" type="button" class="btn btn-success btn-sm"><i
                                        class="fa fa-undo"></i> Site Listesine Dön</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Hata!</strong> Aşağıdaki hataları düzeltin.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="form" action="{{ route('panel.update', ['slug' => $panel->slug]) }}"
                        method="post">
                        @csrf
                        <div class="box-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Başlık</label>
                                        <input type="text" class="form-control" placeholder="Başlık" name="title"
                                            value="{{ $panel->title }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Domain</label>
                                        <input type="text" class="form-control" placeholder="example.com" name="domain"
                                            value="{{ $panel->domain }}">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Durum</label>
                                        <div class="switch-container">
                                            <label class="switch">
                                                <input type="checkbox" id="status-switch" name="status" value="1"
                                                    {{ $panel->status ? 'checked' : '' }}
                                                    data-panel-id="{{ $panel->id }}">
                                                <span class="slider round"></span>
                                            </label>
                                            <span id="status-text"
                                                class="ml-2">{{ $panel->status ? 'Aktif' : 'Kilitli' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Status Değişim Tarihi <small
                                                class="text-muted">(Opsiyonel)</small></label>
                                        <div class="input-group">
                                            <input type="text" id="status-date-picker" class="form-control"
                                                name="status_date"
                                                value="{{ $panel->status_date ? $panel->status_date->format('d.m.Y H:i') : '' }}"
                                                placeholder="Tarih ve saat seçin" readonly>
                                            <span class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                        <small class="text-muted">Bu tarihte status otomatik olarak pasif yapılacak.</small><br/>
                                        <small class="text-muted">Tarihi belirledikten sonra kaydet butonuna basmayı unutmayınız!</small>
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

@section('custom_css')
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

    <style>
        /* Switch CSS */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ed2525;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #26be00;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #26be00;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .switch-container {
            display: flex;
            align-items: center;
        }

        #status-text {
            font-weight: bold;
            margin-left: 10px;
        }

        /* Flatpickr Custom Styling */
        .flatpickr-input {
            cursor: pointer;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-left: 0;
            cursor: pointer;
        }

        .flatpickr-calendar {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
        }
    </style>
@endsection

@section('custom_js')
    <script src="{{ asset('backend/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
    <script src="{{ asset('backend/js/pages/advanced-form-element.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.js') }}"></script>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/tr.js"></script>

    <script>
        $(document).ready(function() {
            // CSRF token setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Flatpickr DateTime Picker
            flatpickr("#status-date-picker", {
                enableTime: true,
                dateFormat: "d.m.Y H:i",
                time_24hr: true,
                locale: "tr",
                minDate: "today",
                allowInput: true,
                clickOpens: true,
                placeholder: "Tarih ve saat seçin",
                onReady: function(selectedDates, dateStr, instance) {
                    // Input group click event
                    $('.input-group-text').click(function() {
                        instance.open();
                    });
                },
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        // Format the date for backend (ISO format)
                        var isoDate = selectedDates[0].toISOString();

                        // Set a hidden input for backend processing
                        if (!$('#status-date-hidden').length) {
                            $('#status-date-picker').after(
                                '<input type="hidden" id="status-date-hidden" name="status_date_iso" value="">'
                                );
                        }
                        $('#status-date-hidden').val(isoDate);
                    }
                },
                onOpen: function(selectedDates, dateStr, instance) {
                    // Add custom styling when opened
                    $('.flatpickr-calendar').addClass('animated fadeIn');
                },
                onClose: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        toastr.info('Otomatik pasif olma tarihi: ' + dateStr + ". Kaydet butonuna basmayı unutmayınız!", 'Tarih Seçildi', {
                            timeOut: 3000
                        });
                    }
                }
            });

            // Switch toggle event
            $('#status-switch').change(function() {
                var isChecked = $(this).is(':checked');
                var panelId = $(this).data('panel-id');
                var statusValue = isChecked ? 1 : 0;

                // Loading state
                $(this).prop('disabled', true);
                $('#status-text').text('Güncelleniyor...');

                // AJAX request
                $.ajax({
                    url: '{{ route('panel.toggle.status') }}',
                    type: 'POST',
                    data: {
                        panel_id: panelId,
                        status: statusValue
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update text
                            $('#status-text').text(response.status ? 'Aktif' : 'Kilitli');

                            // Success notification
                            toastr.success(response.message, 'Başarılı');
                        } else {
                            // Revert switch
                            $('#status-switch').prop('checked', !isChecked);
                            $('#status-text').text(!isChecked ? 'Aktif' : 'Kilitli');

                            toastr.error(response.message, 'Hata');
                        }
                    },
                    error: function(xhr) {
                        // Revert switch
                        $('#status-switch').prop('checked', !isChecked);
                        $('#status-text').text(!isChecked ? 'Aktif' : 'Kilitli');

                        var errorMessage = 'Bir hata oluştu';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        toastr.error(errorMessage, 'Hata');
                    },
                    complete: function() {
                        // Re-enable switch
                        $('#status-switch').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
