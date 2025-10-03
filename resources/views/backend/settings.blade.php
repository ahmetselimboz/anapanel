@extends('backend.layout')

@section('content')

    <div class="modal fade" id="securityModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ">
            <div class="modal-content rounded-3">
                <div class="modal-header bg-primary">
                    <h4 id="modal-title" class="text-center m-2">2FA Kurulumu</h4>
                    <button type="button" id="close-model" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Kapat"></button>
                </div>
                <hr class="m-0">
                <div class="modal-body my-3">
                    <p>Lütfen bu QR kodu Google Authenticator ile taratın:</p>
                    <div id="qrCodeContainer" class="text-center my-2"></div>
                    <p class="text-center">Veya bu kodu manuel girin:</p>
                    <div class="text-center mb-3">
                        <code id="secretCode">Yükleniyor...</code>
                    </div>

                    <form method="POST" action="{{ route('2fa.enable') }}">
                        @csrf
                        <div class="row">
                            <div class="col-9">
                                <input type="text" name="code" class="form-control"
                                    placeholder="Google Authenticator kodu">
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-success py-1">Etkinleştir</button>
                            </div>
                        </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#securityModal').on('show.bs.modal', function() {
                $.ajax({
                    url: "{{ route('2fa.setup') }}",
                    method: 'GET',
                    success: function(data) {
                        console.log(data)
                        $('#qrCodeContainer').html(data.qrCode);
                        $('#secretCode').text(data.secret);
                        $('#secretInput').val(data.secret);
                    },
                    error: function() {
                        $('#qrCodeContainer').html(
                            '<p class="text-danger">QR kod alınamadı.</p>');
                        $('#secretCode').text('Hata');
                    }
                });
            });
        });
    </script>


    <section class="content">

        <div class="row">
            <div class="col-12 col-lg-12 col-xl-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li><a href="#general" class="active" data-bs-toggle="tab">Genel Ayarlar</a></li>
                        @if(auth()->user()->status==1)
                        <li><a href="#security" data-bs-toggle="tab">Güvenlik</a></li> 
                        @endif
                        <li><a href="#socialmedia" data-bs-toggle="tab">Sosyal Medya</a></li>
                        <li><a href="#images" data-bs-toggle="tab">Logo & Resimler</a></li>
                        <li><a href="#code" data-bs-toggle="tab">Analitik & Kod</a></li>
                        <li><a href="#agencies" data-bs-toggle="tab">Ajanslar</a></li>
                        <li><a href="#smtp" data-bs-toggle="tab">SMTP</a></li>
                        <li><a href="#customsetting" data-bs-toggle="tab">Özel Ayarlar</a></li>
                        <li><a href="#colorsetting" data-bs-toggle="tab">Renk Ayarları</a></li>
                        <li><a href="#modulsetting" data-bs-toggle="tab">Modül Ayarları</a></li>
                    </ul>

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

                    <form class="form" id="settingsForm" action="{{ route('settings.update') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="tab-content">

                            <div class="tab-pane active" id="general">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Başlık</label>
                                                <input type="text" class="form-control" placeholder="Site Başlık"
                                                    name="title" value="{{ $settings->title }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Açıklama</label>
                                                <textarea id="" cols="30" rows="2" class="form-control" placeholder="Site Açıklama"
                                                    name="description">{{ $settings->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Anahtar Kelimeler</label>
                                                <textarea id="" cols="30" rows="2" class="form-control" placeholder="Virgülle Ayırın"
                                                    name="keywords">
@if (isset($jsondata->keywords))
{{ $jsondata->keywords }}
@endif
</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Sayfa Yenileme Süresi (saniye)</label>
                                                <input type="text" name="refresh" class="form-control"
                                                    placeholder="180"
                                                    @if (isset($jsondata->refresh)) value="{{ $jsondata->refresh }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Site E Posta</label>
                                                <input type="text" name="email" class="form-control"
                                                    placeholder="info@site.com"
                                                    @if (isset($jsondata->email)) value="{{ $jsondata->email }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Site Telefon</label>
                                                <input type="text" name="phone" class="form-control"
                                                    placeholder="05xxx"
                                                    @if (isset($jsondata->phone)) value="{{ $jsondata->phone }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Adres</label>
                                                <input type="text" name="address" class="form-control"
                                                    placeholder="Mebusevler mahallesi Kızılay Ankara"
                                                    @if (isset($jsondata->address)) value="{{ $jsondata->address }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Copyright Yazısı</label>
                                                <input type="text" name="copyright" class="form-control"
                                                    placeholder="Tüm hakları saklıdır gibi"
                                                    @if (isset($jsondata->copyright)) value="{{ $jsondata->copyright }}" @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Bakım Modu</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="maintenance">
                                                    <option value="0"
                                                        @if ($settings->maintenance == 0) selected="selected" @endif>Kapalı
                                                    </option>
                                                    <option value="1"
                                                        @if ($settings->maintenance == 1) selected="selected" @endif>Açık
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Google ve diğer arama motorları indeks
                                                    ayarı</label>
                                                <select class="form-control select2" style="width: 100%;" name="noindex">
                                                    <option value="0"
                                                        @if (isset($jsondata->noindex) and $jsondata->noindex == 0) selected="selected" @endif>İndeks
                                                        alsın
                                                    </option>
                                                    <option value="1"
                                                        @if (isset($jsondata->noindex) and $jsondata->noindex == 1) selected="selected" @endif>İndeks
                                                        almasın !
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Mobil Sabit Yazı Adı</label>
                                                <input type="text" name="live_stream_name" class="form-control"
                                                    placeholder="Menü Adı"
                                                    @if (isset($jsondata->live_stream_name)) value="{{ $jsondata->live_stream_name }}" @endif>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Mobil Sabit Yazı Linki</label>
                                                <input type="text" name="live_stream_link" class="form-control"
                                                    placeholder="Menü Linki"
                                                    @if (isset($jsondata->live_stream_link)) value="{{ $jsondata->live_stream_link }}" @endif>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="box-footer"> <button type="submit" class="btn btn-primary"> <i
                                            class="ti-save-alt"></i> Kaydet </button> </div>
                            </div>
                            @if(auth()->user()->status==1)
                            <div class="tab-pane" id="security">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Google Authenticator</label>
                                                @if (auth()->user()->two_factor_enabled)
                                                    <a href="{{ route('2fa.disable') }}" class="btn btn-danger d-block">
                                                        İki Faktörlü Doğrulamayı Kaldır
                                                    </a>
                                                @else
                                                    <a href="{{ route('2fa.active') }}" class="btn btn-primary d-block" >İki Faktörlü Doğrulamayı Aç</a>
                                                @endif

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="box-footer"> <button type="submit" class="btn btn-primary"> <i
                                            class="ti-save-alt"></i> Kaydet </button> </div>
                            </div>
                            @endif
                            <div class="tab-pane" id="socialmedia">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Facebook</label>
                                                <input type="text" class="form-control" placeholder="http ile ekleyin"
                                                    name="fb"
                                                    @if (isset($jsondata->fb)) value="{{ $jsondata->fb }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">İnstagram</label>
                                                <input type="text" class="form-control" placeholder="http ile ekleyin"
                                                    name="in"
                                                    @if (isset($jsondata->in)) value="{{ $jsondata->in }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Twitter</label>
                                                <input type="text" class="form-control" placeholder="http ile ekleyin"
                                                    name="tw"
                                                    @if (isset($jsondata->tw)) value="{{ $jsondata->tw }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Twitter Kullanıcı Adı</label>
                                                <input type="text" class="form-control"
                                                    placeholder="@twitter_kullanıcı_ismi" name="tw"
                                                    @if (isset($jsondata->tw_name)) value="{{ $jsondata->tw_name }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Youtube</label>
                                                <input type="text" class="form-control" placeholder="http ile ekleyin"
                                                    name="yt"
                                                    @if (isset($jsondata->yt)) value="{{ $jsondata->yt }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Linkedin</label>
                                                <input type="text" class="form-control" placeholder="http ile ekleyin"
                                                    name="ln"
                                                    @if (isset($jsondata->ln)) value="{{ $jsondata->ln }}" @endif>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer"> <button type="submit" class="btn btn-primary"> <i
                                            class="ti-save-alt"></i> Kaydet </button> </div>
                            </div>

                            <div class="tab-pane" id="images">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label">Site Logo <i class="fa fa-info-circle"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Genişlik: 270 px Yükseklik: 65 px "></i></label>
                                                <input type="file" name="logo" class="form-control"
                                                    placeholder="Resim">
                                            </div>
                                            @if ($settings->logo != null)
                                                <a class="btn btn-primary btn-sm" data-bs-toggle="collapse"
                                                    href="#collapseLogo" role="button" aria-expanded="false"
                                                    aria-controls="collapseLogo">
                                                    Mevcut Logoyu Göster / Gizle
                                                </a>
                                                <div class="collapse" id="collapseLogo">
                                                    <img src="{{ asset('/' . $settings->logo) }}" alt="">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6  mb-3">
                                            <div class="form-group">
                                                <label class="form-label">Varsayılan Resim <i class="fa fa-info-circle"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Genişlik: 777 px Yükseklik: 510 px "></i></label>
                                                <input type="file" name="defaultimage" class="form-control"
                                                    placeholder="Resim">
                                            </div>
                                            @if ($settings->defaultimage != null)
                                                <a class="btn btn-primary btn-sm" data-bs-toggle="collapse"
                                                    href="#collapsedefaultimage" role="button" aria-expanded="false"
                                                    aria-controls="collapsedefaultimage">Mevcut Resmi Göster / Gizle </a>
                                                <div class="collapse" id="collapsedefaultimage"> <img
                                                        src="{{ asset('uploads/' . $settings->defaultimage) }}"
                                                        alt=""> </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label">Favicon</label>
                                                <input type="file" name="favicon" class="form-control"
                                                    placeholder="Resim">
                                            </div>
                                            @if ($settings->favicon != null)
                                                <a class="btn btn-primary btn-sm" data-bs-toggle="collapse"
                                                    href="#collapsefavicon" role="button" aria-expanded="false"
                                                    aria-controls="collapsefavicon">Mevcut Favicon Göster / Gizle </a>
                                                <div class="collapse" id="collapsefavicon"> <img
                                                        src="{{ asset($settings->favicon) }}" alt="">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer"> <button type="submit" class="btn btn-primary"> <i
                                            class="ti-save-alt"></i> Kaydet </button> </div>
                            </div>

                            <div class="tab-pane" id="code">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Head Kod Alanı</label>
                                                <textarea name="headcode" id="" cols="30" rows="5" class="form-control">
@if (isset($jsondata->headcode))
{{ $jsondata->headcode }}
@endif
</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Body Kod Alanı</label>
                                                <textarea name="bodycode" id="" cols="30" rows="5" class="form-control">
@if (isset($jsondata->bodycode))
{{ $jsondata->bodycode }}
@endif
</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Google News Adı</label>
                                                <input type="text" class="form-control" placeholder="tskhaber"
                                                    name="googlenews"
                                                    @if (isset($jsondata->googlenews)) value="{{ $jsondata->googlenews }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Yandex Metrica ID</label>
                                                <input type="text" class="form-control" placeholder="Örn: 123434-UA"
                                                    name="yandexmetricaid"
                                                    @if (isset($jsondata->yandexmetricaid)) value="{{ $jsondata->yandexmetricaid }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Google Analitik Kodu</label>
                                                <textarea id="" cols="30" rows="5" class="form-control"
                                                    placeholder="Google analitik takip kodu" name="googleanalytics">
@if (isset($jsondata->googleanalytics))
{{ $settings->googleanalytics }}
@endif
</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Google Analitik Mülk Kimliği</label>
                                                <input type="text" class="form-control" id="limitedInput"
                                                    placeholder="XXXXXXXXX" name="google_property_id"
                                                    @if (isset($jsondata->google_property_id)) value="{{ $jsondata->google_property_id }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Gemini API Key</label>
                                                <input type="text" class="form-control" placeholder="API-Key"
                                                    name="gemini_api_key"
                                                    @if (isset($jsondata->gemini_api_key)) value="{{ $jsondata->gemini_api_key }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Google Client ID</label>
                                                <input type="text" class="form-control" placeholder="Client ID"
                                                    name="google_client_id"
                                                    @if (isset($jsondata->google_client_id)) value="{{ $jsondata->google_client_id }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Google Client Secret</label>
                                                <input type="text" class="form-control" placeholder="Client Secret"
                                                    name="google_client_secret"
                                                    @if (isset($jsondata->google_client_secret)) value="{{ $jsondata->google_client_secret }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Google Redirect Url</label>
                                                <input type="text" class="form-control" placeholder="Redirect Url"
                                                    name="google_redirect_url"
                                                    @if (isset($jsondata->google_redirect_url)) value="{{ $jsondata->google_redirect_url }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Google ReCaptcha Site Key</label>
                                                <input type="text" class="form-control" placeholder="Site Key"
                                                    name="google_recaptcha_site_key"
                                                    @if (isset($jsondata->google_recaptcha_site_key)) value="{{ $jsondata->google_recaptcha_site_key }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Google ReCaptcha Secret Key</label>
                                                <input type="text" class="form-control" placeholder="Secret Key"
                                                    name="google_recaptcha_secret_key"
                                                    @if (isset($jsondata->google_recaptcha_secret_key)) value="{{ $jsondata->google_recaptcha_secret_key }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Yandex Analitik Kodu</label>
                                                <textarea id="" cols="30" rows="5" class="form-control"
                                                    placeholder="Yandex analitik takip kodu" name="yandexanalytics">
@if (isset($jsondata->yandexanalytics))
{{ $settings->yandexanalytics }}
@endif
</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Ads.txt Kod Alanı</label>
                                                <textarea name="adstext" id="" cols="30" rows="5" class="form-control">
@if (isset($jsondata->adstext))
{{ $jsondata->adstext }}
@endif
</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Footer Kod Alanı</label>
                                                <textarea name="footercode" id="" cols="30" rows="5" class="form-control">
@if (isset($jsondata->footercode))
{{ $jsondata->footercode }}
@endif
</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer"> <button type="submit" class="btn btn-primary"> <i
                                            class="ti-save-alt"></i> Kaydet </button> </div>
                            </div>

                            <div class="tab-pane" id="agencies">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-4" style="border-right: 1px solid #0172af7a;">

                                            <div class="form-group">
                                                <label class="form-label">IHA Kullanıcı Kodu</label>
                                                <input name="iha_user_code" id="" class="form-control"
                                                    value="@if (isset($jsondata->iha_user_code)) {{ $jsondata->iha_user_code }} @endif"></input>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">IHA Kullanıcı Adı</label>
                                                <input name="iha_user_name" id="" class="form-control"
                                                    value="@if (isset($jsondata->iha_user_name)) {{ $jsondata->iha_user_name }} @endif"></input>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">IHA RSS Şifresi</label>
                                                <input name="iha_user_password" id="" class="form-control"
                                                    value="@if (isset($jsondata->iha_user_password)) {{ $jsondata->iha_user_password }} @endif"></input>
                                            </div>
                                        </div>
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label class="form-label">HIBYA RSS Link</label>
                                                <input name="hibya_rss_link" id="" class="form-control"
                                                    value="@if(isset($jsondata->hibya_rss_link)) {{ $jsondata->hibya_rss_link }} @endif"></input>
                                            </div>
                                            
                                        </div>

                                    </div>
                                </div>
                                <div class="box-footer"> <button type="submit" class="btn btn-primary"> <i
                                            class="ti-save-alt"></i> Kaydet </button> </div>
                            </div>

                            <div class="tab-pane" id="smtp">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Sunucu Adresi</label>
                                                <input type="text" class="form-control"
                                                    placeholder="xxx.xxxxxxxxx.com" name="smtpserver"
                                                    @if (isset($jsondata->smtpserver)) value="{{ $jsondata->smtpserver }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Kullanıcı Adı</label>
                                                <input type="text" class="form-control"
                                                    placeholder="ornek@xxxxxxxx.com" name="smtpuser"
                                                    @if (isset($jsondata->smtpuser)) value="{{ $jsondata->smtpuser }}" @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Şifre</label>
                                                <input type="text" class="form-control" placeholder=""
                                                    name="smtppassword"
                                                    @if (isset($jsondata->smtppassword)) value="{{ $jsondata->smtppassword }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Port</label>
                                                <input type="text" class="form-control" placeholder=""
                                                    name="smtpport"
                                                    @if (isset($jsondata->smtpport)) value="{{ $jsondata->smtpport }}" @endif>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer"> <button type="submit" class="btn btn-primary"> <i
                                            class="ti-save-alt"></i> Kaydet </button> </div>
                            </div>

                            <div class="tab-pane" id="customsetting">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?php $all = scandir(base_path() . '/resources/views/themes');
                                                unset($all[0]);
                                                unset($all[1]); ?>
                                                <label class="form-label">Site Teması</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="sitetheme">
                                                    @foreach ($all as $file)
                                                        <option value="{{ $file }}"
                                                            @if (isset($jsondata->sitetheme) and $file == $jsondata->sitetheme) selected @endif>
                                                            {{ $file }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Gelen Yorumlar</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="generalcomment">
                                                    <option value="0"
                                                        @if (isset($jsondata->generalcomment) and $jsondata->generalcomment == 0) selected @endif>Onaya Gelsin
                                                    </option>
                                                    <option value="1"
                                                        @if (isset($jsondata->generalcomment) and $jsondata->generalcomment == 1) selected @endif>Direk
                                                        Yayınlansın
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 d-none">
                                            <div class="form-group">
                                                <label class="form-label">Gelen Haberler</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="incomingnews">
                                                    <option value="0"
                                                        @if (isset($jsondata->incomingnews) and $jsondata->incomingnews == 0) selected @endif>Onaya Gelsin
                                                    </option>
                                                    <option value="1"
                                                        @if (isset($jsondata->incomingnews) and $jsondata->incomingnews == 1) selected @endif>Direk
                                                        Yayınlansın
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 d-none">
                                            <div class="form-group">
                                                <label class="form-label">Okunma Sayıları</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="generalhitshow">
                                                    <option value="0"
                                                        @if (isset($jsondata->generalhitshow) and $jsondata->generalhitshow == 0) selected @endif>Göster</option>
                                                    <option value="1"
                                                        @if (isset($jsondata->generalhitshow) and $jsondata->generalhitshow == 1) selected @endif>Gizle</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Site Bağlantıları</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="externallink">
                                                    <option value="0"
                                                        @if (isset($jsondata->externallink) and $jsondata->externallink == 0) selected @endif>Yeni sekmede
                                                        AÇILSIN
                                                    </option>
                                                    <option value="1"
                                                        @if (isset($jsondata->externallink) and $jsondata->externallink == 1) selected @endif>Yeni sekmede
                                                        AÇILMASIN
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Sağ Tık Engelleme</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="rightclick">
                                                    <option value="0"
                                                        @if (isset($jsondata->rightclick) and $jsondata->rightclick == 0) selected @endif>Engellensin
                                                    </option>
                                                    <option value="1"
                                                        @if (isset($jsondata->rightclick) and $jsondata->rightclick == 1) selected @endif>Engellenmesin
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Site Genel Yazı Tipi</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="generalfont">
                                                    <option value="0">Varsayılan</option>
                                                    <option value="Roboto"
                                                        @if (isset($jsondata->generalfont) and $jsondata->generalfont == 'Roboto') selected @endif>Roboto</option>
                                                    <option value="Open Sans"
                                                        @if (isset($jsondata->generalfont) and $jsondata->generalfont == 'Open Sans') selected @endif>Open Sans
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Manşet Haber Limiti</label>
                                                <input type="number" min="1" class="form-control"
                                                    name="mansetlimit"
                                                    @if (isset($jsondata->mansetlimit)) value="{{ $jsondata->mansetlimit }}" @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Manşet ve Kategori Başlıkları</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="mansetbaslik">
                                                    <option value="0"
                                                        @if (isset($jsondata->mansetbaslik) and $jsondata->mansetbaslik == 0) selected @endif>Göster</option>
                                                    <option value="1"
                                                        @if (isset($jsondata->mansetbaslik) and $jsondata->mansetbaslik == 1) selected @endif>Gizle</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Manşet ve Kategori Açıklamaları</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="mansetaciklama">
                                                    <option value="0"
                                                        @if (isset($jsondata->mansetaciklama) and $jsondata->mansetaciklama == 0) selected @endif>Göster</option>
                                                    <option value="1"
                                                        @if (isset($jsondata->mansetaciklama) and $jsondata->mansetaciklama == 1) selected @endif>Gizle</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Manşet Sabit Reklam Numarası</label>
                                                <input type="number" min="1" max="30" class="form-control"
                                                    name="mansetsabitreklamno"
                                                    @if (isset($jsondata->mansetsabitreklamno)) value="{{ $jsondata->mansetsabitreklamno }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Haberleri Arşive Taşı (Girilen Günden Eski
                                                Olanlar)</label>
                                            <div class="input-group">
                                                <input type="text" maxlength="4"
                                                    placeholder="Boş bırakıldığında pasif durumda olacaktır. Gün sayısı Giriniz"
                                                    class="form-control"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                    aria-label="Gün" name="archive_recording_time"
                                                    @if (isset($jsondata->archive_recording_time)) value="{{ $jsondata->archive_recording_time }}" @endif>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Gün</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">İnce Üst Bar</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="thintopbar">
                                                    <option value="0"
                                                        @if (isset($jsondata->thintopbar) and $jsondata->thintopbar == 0) selected @endif>Kapalı</option>
                                                    <option value="1"
                                                        @if (isset($jsondata->thintopbar) and $jsondata->thintopbar == 1) selected @endif>Açık</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Play Store URL</label>
                                                <input type="text" class="form-control" name="playstoreurl"
                                                    @if (isset($jsondata->playstoreurl)) value="{{ $jsondata->playstoreurl }}" @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">App Store URL</label>
                                                <input type="text" class="form-control" name="appstoreurl"
                                                    @if (isset($jsondata->appstoreurl)) value="{{ $jsondata->appstoreurl }}" @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Hava Durumu Linki</label>
                                                <input type="text" class="form-control" name="weather_link"
                                                    placeholder="Link"
                                                    @if (isset($jsondata->weather_link)) value="{{ $jsondata->weather_link }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Nobetçi Eczaneler Linki</label>
                                                <input type="text" class="form-control" name="pharmacy_link"
                                                    placeholder="Link"
                                                    @if (isset($jsondata->pharmacy_link)) value="{{ $jsondata->pharmacy_link }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Vefat Edenler Linki</label>
                                                <input type="text" class="form-control" name="deceased_link"
                                                    placeholder="Link"
                                                    @if (isset($jsondata->deceased_link)) value="{{ $jsondata->deceased_link }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Trafik Yol Durumu</label>
                                                <input type="text" class="form-control" name="traffic_link"
                                                    placeholder="Link"
                                                    @if (isset($jsondata->traffic_link)) value="{{ $jsondata->traffic_link }}" @endif>
                                            </div>
                                        </div>

                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="form-label">Etiketler</label>
                                                <input type="text" class="form-control" name="keyword1"
                                                    placeholder="Etiket-1"
                                                    @if (isset($jsondata->keyword1)) value="{{ $jsondata->keyword1 }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="form-label invisible">Etiketler</label>
                                                <input type="text" class="form-control" name="keyword2"
                                                    placeholder="Etiket-2"
                                                    @if (isset($jsondata->keyword2)) value="{{ $jsondata->keyword2 }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">

                                                <input type="text" class="form-control" name="keyword3"
                                                    placeholder="Etiket-3"
                                                    @if (isset($jsondata->keyword3)) value="{{ $jsondata->keyword3 }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">

                                                <input type="text" class="form-control" name="keyword4"
                                                    placeholder="Etiket-4"
                                                    @if (isset($jsondata->keyword4)) value="{{ $jsondata->keyword4 }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">

                                                <input type="text" class="form-control" name="keyword5"
                                                    placeholder="Etiket-5"
                                                    @if (isset($jsondata->keyword5)) value="{{ $jsondata->keyword5 }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">

                                                <input type="text" class="form-control" name="keyword6"
                                                    placeholder="Etiket-6"
                                                    @if (isset($jsondata->keyword6)) value="{{ $jsondata->keyword6 }}" @endif>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer"> <button type="submit" class="btn btn-primary"> <i
                                            class="ti-save-alt"></i> Kaydet </button> </div>
                            </div>

                            <div class="tab-pane" id="colorsetting">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Header Menü <i class="fa fa-info-circle"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Anasayfa üst kısımdaki header menü renk seçeneğidir. Kategoriler için kategori ayarlarındaki renk seçeneği kullanılmalıdır."></i>
                                                </label>
                                                <input type="color" class="form-control p-1" style="height: 34px"
                                                    placeholder="Renk seçimi" name="headercolor"
                                                    @if (isset($jsondata->headercolor)) value="{{ $jsondata->headercolor }}" @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Header Menü Yazı Rengi </label>
                                                <input type="color" class="form-control p-1" style="height: 34px"
                                                    placeholder="Renk seçimi" name="headercolortext"
                                                    @if (isset($jsondata->headercolortext)) value="{{ $jsondata->headercolortext }}" @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Son Dakika Bandı <i class="fa fa-info-circle"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Son dakika bandı arka plan için geçerlidir."></i> </label>
                                                <input type="color" class="form-control p-1" style="height: 34px"
                                                    placeholder="Renk seçimi" name="sondakikacolor"
                                                    @if (isset($jsondata->sondakikacolor)) value="{{ $jsondata->sondakikacolor }}" @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Son Dakika Bandı Yazı Rengi </label>
                                                <input type="color" class="form-control p-1" style="height: 34px"
                                                    placeholder="Renk seçimi" name="sondakikacolortext"
                                                    @if (isset($jsondata->sondakikacolortext)) value="{{ $jsondata->sondakikacolortext }}" @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Manşet Numara Arka Plan <i
                                                        class="fa fa-info-circle" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Manşet alt kısım numara arkası arka plan rengini değiştirir."></i>
                                                </label>
                                                <input type="color" class="form-control p-1" style="height: 34px"
                                                    placeholder="Renk seçimi" name="mansetnumaracolor"
                                                    @if (isset($jsondata->mansetnumaracolor)) value="{{ $jsondata->mansetnumaracolor }}" @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Manşet Numara Rengi </label>
                                                <input type="color" class="form-control p-1" style="height: 34px"
                                                    placeholder="Renk seçimi" name="mansetnumaracolortext"
                                                    @if (isset($jsondata->mansetnumaracolortext)) value="{{ $jsondata->mansetnumaracolortext }}" @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Para Piyasası Arka Plan </label>
                                                <input type="color" class="form-control p-1" style="height: 34px"
                                                    placeholder="Renk seçimi" name="parapiyasacolor"
                                                    @if (isset($jsondata->parapiyasacolor)) value="{{ $jsondata->parapiyasacolor }}" @endif>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Para Piyasası Başlık Rengi </label>
                                                <input type="color" class="form-control p-1" style="height: 34px"
                                                    placeholder="Renk seçimi" name="parapiyasacolortext"
                                                    @if (isset($jsondata->parapiyasacolortext)) value="{{ $jsondata->parapiyasacolortext }}" @endif>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="box-footer"> <button type="submit" class="btn btn-primary"> <i
                                            class="ti-save-alt"></i> Kaydet </button> </div>
                            </div>

                            <div class="tab-pane" id="modulsetting">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">API SERVİSİ <i class="fa fa-info-circle"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="TÜM APİ SERVİSLERİNİ KAPATMAYA VEYA AÇMAYA YARAR. MODÜL ÖZELİNDE AÇIK OLSA DAHİ GENEL DURUM KAPALIYSA SERVİS SİTEDE GÖRÜNMEZ VE YANIT VERMEZ."></i>
                                                </label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="apiservicestatus">
                                                    <option value="0"
                                                        @if (isset($jsondata->apiservicestatus) and $jsondata->apiservicestatus == 0) selected @endif>AÇIK</option>
                                                    <option value="1"
                                                        @if (isset($jsondata->apiservicestatus) and $jsondata->apiservicestatus == 1) selected @endif>KAPALI</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Eczane Modülü (YAKINDA)</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="pharmacy_status">
                                                    <option value="0"
                                                        @if (isset($jsondata->pharmacy_status) and $jsondata->pharmacy_status == 0) selected @endif>AÇIK</option>
                                                    <option value="1"
                                                        @if (isset($jsondata->pharmacy_status) and $jsondata->pharmacy_status == 1) selected @endif>KAPALI</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Yazar Modülü</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="author_status">
                                                    <option value="0"
                                                        @if (isset($jsondata->author_status) and $jsondata->author_status == 0) selected @endif>AÇIK</option>
                                                    <option value="1"
                                                        @if (isset($jsondata->author_status) and $jsondata->author_status == 1) selected @endif>KAPALI</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Para Piyasası Modülü</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="currency_status">
                                                    <option value="0"
                                                        @if (isset($jsondata->currency_status) and $jsondata->currency_status == 0) selected @endif>AÇIK</option>
                                                    <option value="1"
                                                        @if (isset($jsondata->currency_status) and $jsondata->currency_status == 1) selected @endif>KAPALI</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Hava Durumu Modülü</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="weather_status">
                                                    <option value="0"
                                                        @if (isset($jsondata->weather_status) and $jsondata->weather_status == 0) selected @endif>AÇIK</option>
                                                    <option value="1"
                                                        @if (isset($jsondata->weather_status) and $jsondata->weather_status == 1) selected @endif>KAPALI</option>
                                                </select>
                                            </div>
                                        </div>




                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Hava Durumu İl Seçimi</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="weather_city" id="weather_city">
                                                    <option value="0" data-ekdeger="0">Seçilmedi</option>
                                                    <option value="Adana" data-ekdeger="1">Adana</option>
                                                    <option value="Adıyaman" data-ekdeger="2">Adıyaman</option>
                                                    <option value="Afyonkarahisar " data-ekdeger="3">Afyonkarahisar
                                                    </option>
                                                    <option value="Ağrı" data-ekdeger="4">Ağrı</option>
                                                    <option value="Amasya" data-ekdeger="5">Amasya</option>
                                                    <option value="Ankara" data-ekdeger="6">Ankara</option>
                                                    <option value="Antalya" data-ekdeger="7">Antalya</option>
                                                    <option value="Artvin" data-ekdeger="8">Artvin</option>
                                                    <option value="Aydın" data-ekdeger="9">Aydın</option>
                                                    <option value="Balıkesir" data-ekdeger="10">Balıkesir</option>
                                                    <option value="Bilecik" data-ekdeger="11">Bilecik</option>
                                                    <option value="Bingöl" data-ekdeger="12">Bingöl</option>
                                                    <option value="Bitlis" data-ekdeger="13">Bitlis</option>
                                                    <option value="Bolu" data-ekdeger="14">Bolu</option>
                                                    <option value="Burdur" data-ekdeger="15">Burdur</option>
                                                    <option value="Bursa" data-ekdeger="16">Bursa</option>
                                                    <option value="Çanakkale" data-ekdeger="17">Çanakkale</option>
                                                    <option value="Çankırı" data-ekdeger="18">Çankırı</option>
                                                    <option value="Çorum" data-ekdeger="19">Çorum</option>
                                                    <option value="Denizli" data-ekdeger="20">Denizli</option>
                                                    <option value="Diyarbakır" data-ekdeger="21">Diyarbakır</option>
                                                    <option value="Edirne" data-ekdeger="22">Edirne</option>
                                                    <option value="Elazığ" data-ekdeger="23">Elazığ</option>
                                                    <option value="Erzincan" data-ekdeger="24">Erzincan</option>
                                                    <option value="Erzurum" data-ekdeger="25">Erzurum</option>
                                                    <option value="Eskişehir" data-ekdeger="26">Eskişehir</option>
                                                    <option value="Gaziantep" data-ekdeger="27">Gaziantep</option>
                                                    <option value="Giresun" data-ekdeger="28">Giresun</option>
                                                    <option value="Gümüşhane" data-ekdeger="29">Gümüşhane</option>
                                                    <option value="Hakkari" data-ekdeger="30">Hakkari</option>
                                                    <option value="Hatay" data-ekdeger="31">Hatay</option>
                                                    <option value="Isparta" data-ekdeger="32">Isparta</option>
                                                    <option value="Mersin" data-ekdeger="33">Mersin</option>
                                                    <option value="İstanbul" data-ekdeger="34">İstanbul</option>
                                                    <option value="İzmir" data-ekdeger="35">İzmir</option>
                                                    <option value="Kars" data-ekdeger="36">Kars</option>
                                                    <option value="Kastamonu" data-ekdeger="37">Kastamonu</option>
                                                    <option value="Kayseri" data-ekdeger="38">Kayseri</option>
                                                    <option value="Kırklareli" data-ekdeger="39">Kırklareli</option>
                                                    <option value="Kırşehir" data-ekdeger="40">Kırşehir</option>
                                                    <option value="Kocaeli" data-ekdeger="41">Kocaeli</option>
                                                    <option value="Konya" data-ekdeger="42">Konya</option>
                                                    <option value="Kütahya" data-ekdeger="43">Kütahya</option>
                                                    <option value="Malatya" data-ekdeger="44">Malatya</option>
                                                    <option value="Manisa" data-ekdeger="45">Manisa</option>
                                                    <option value="Kahramanmaraş" data-ekdeger="46">Kahramanmaraş</option>
                                                    <option value="Mardin" data-ekdeger="47">Mardin</option>
                                                    <option value="Muğla" data-ekdeger="48">Muğla</option>
                                                    <option value="Muş" data-ekdeger="49">Muş</option>
                                                    <option value="Nevşehir" data-ekdeger="50">Nevşehir</option>
                                                    <option value="Niğde" data-ekdeger="51">Niğde</option>
                                                    <option value="Ordu" data-ekdeger="52">Ordu</option>
                                                    <option value="Rize" data-ekdeger="53">Rize</option>
                                                    <option value="Sakarya" data-ekdeger="54">Sakarya</option>
                                                    <option value="Samsun" data-ekdeger="55">Samsun</option>
                                                    <option value="Siirt" data-ekdeger="56">Siirt</option>
                                                    <option value="Sinop" data-ekdeger="57">Sinop</option>
                                                    <option value="Sivas" data-ekdeger="58">Sivas</option>
                                                    <option value="Tekirdağ" data-ekdeger="59">Tekirdağ</option>
                                                    <option value="Tokat" data-ekdeger="60">Tokat</option>
                                                    <option value="Trabzon" data-ekdeger="61">Trabzon</option>
                                                    <option value="Tunceli" data-ekdeger="62">Tunceli</option>
                                                    <option value="Şanlıurfa" data-ekdeger="63">Şanlıurfa</option>
                                                    <option value="Uşak" data-ekdeger="64">Uşak</option>
                                                    <option value="Van" data-ekdeger="65">Van</option>
                                                    <option value="Yozgat" data-ekdeger="66">Yozgat</option>
                                                    <option value="Zonguldak" data-ekdeger="67">Zonguldak</option>
                                                    <option value="Aksaray" data-ekdeger="68">Aksaray</option>
                                                    <option value="Bayburt" data-ekdeger="69">Bayburt</option>
                                                    <option value="Karaman" data-ekdeger="70">Karaman</option>
                                                    <option value="Kırıkkale" data-ekdeger="71">Kırıkkale</option>
                                                    <option value="Batman" data-ekdeger="72">Batman</option>
                                                    <option value="Şırnak" data-ekdeger="73">Şırnak</option>
                                                    <option value="Bartın" data-ekdeger="74">Bartın</option>
                                                    <option value="Ardahan" data-ekdeger="75">Ardahan</option>
                                                    <option value="Iğdır" data-ekdeger="76">Iğdır</option>
                                                    <option value="Yalova" data-ekdeger="77">Yalova</option>
                                                    <option value="Karabük" data-ekdeger="78">Karabük</option>
                                                    <option value="Kilis" data-ekdeger="79">Kilis</option>
                                                    <option value="Osmaniye" data-ekdeger="80">Osmaniye</option>
                                                    <option value="Düzce" data-ekdeger="81">Düzce</option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Namaz Vakitleri Modülü</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="prayer_status">
                                                    <option value="0"
                                                        @if (isset($jsondata->prayer_status) and $jsondata->prayer_status == 0) selected @endif>AÇIK</option>
                                                    <option value="1"
                                                        @if (isset($jsondata->prayer_status) and $jsondata->prayer_status == 1) selected @endif>KAPALI</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Namaz Vakitleri İl Seçimi</label>
                                                <select class="form-control select2" style="width: 100%;"
                                                    name="prayer_city" id="prayer_city">
                                                    <option value="0" data-ekdeger="0">Seçilmedi</option>
                                                    <option value="Adana" data-ekdeger="1">Adana</option>
                                                    <option value="Adıyaman" data-ekdeger="2">Adıyaman</option>
                                                    <option value="Afyonkarahisar " data-ekdeger="3">Afyonkarahisar
                                                    </option>
                                                    <option value="Ağrı" data-ekdeger="4">Ağrı</option>
                                                    <option value="Amasya" data-ekdeger="5">Amasya</option>
                                                    <option value="Ankara" data-ekdeger="6">Ankara</option>
                                                    <option value="Antalya" data-ekdeger="7">Antalya</option>
                                                    <option value="Artvin" data-ekdeger="8">Artvin</option>
                                                    <option value="Aydın" data-ekdeger="9">Aydın</option>
                                                    <option value="Balıkesir" data-ekdeger="10">Balıkesir</option>
                                                    <option value="Bilecik" data-ekdeger="11">Bilecik</option>
                                                    <option value="Bingöl" data-ekdeger="12">Bingöl</option>
                                                    <option value="Bitlis" data-ekdeger="13">Bitlis</option>
                                                    <option value="Bolu" data-ekdeger="14">Bolu</option>
                                                    <option value="Burdur" data-ekdeger="15">Burdur</option>
                                                    <option value="Bursa" data-ekdeger="16">Bursa</option>
                                                    <option value="Çanakkale" data-ekdeger="17">Çanakkale</option>
                                                    <option value="Çankırı" data-ekdeger="18">Çankırı</option>
                                                    <option value="Çorum" data-ekdeger="19">Çorum</option>
                                                    <option value="Denizli" data-ekdeger="20">Denizli</option>
                                                    <option value="Diyarbakır" data-ekdeger="21">Diyarbakır</option>
                                                    <option value="Edirne" data-ekdeger="22">Edirne</option>
                                                    <option value="Elazığ" data-ekdeger="23">Elazığ</option>
                                                    <option value="Erzincan" data-ekdeger="24">Erzincan</option>
                                                    <option value="Erzurum" data-ekdeger="25">Erzurum</option>
                                                    <option value="Eskişehir" data-ekdeger="26">Eskişehir</option>
                                                    <option value="Gaziantep" data-ekdeger="27">Gaziantep</option>
                                                    <option value="Giresun" data-ekdeger="28">Giresun</option>
                                                    <option value="Gümüşhane" data-ekdeger="29">Gümüşhane</option>
                                                    <option value="Hakkari" data-ekdeger="30">Hakkari</option>
                                                    <option value="Hatay" data-ekdeger="31">Hatay</option>
                                                    <option value="Isparta" data-ekdeger="32">Isparta</option>
                                                    <option value="Mersin" data-ekdeger="33">Mersin</option>
                                                    <option value="İstanbul" data-ekdeger="34">İstanbul</option>
                                                    <option value="İzmir" data-ekdeger="35">İzmir</option>
                                                    <option value="Kars" data-ekdeger="36">Kars</option>
                                                    <option value="Kastamonu" data-ekdeger="37">Kastamonu</option>
                                                    <option value="Kayseri" data-ekdeger="38">Kayseri</option>
                                                    <option value="Kırklareli" data-ekdeger="39">Kırklareli</option>
                                                    <option value="Kırşehir" data-ekdeger="40">Kırşehir</option>
                                                    <option value="Kocaeli" data-ekdeger="41">Kocaeli</option>
                                                    <option value="Konya" data-ekdeger="42">Konya</option>
                                                    <option value="Kütahya" data-ekdeger="43">Kütahya</option>
                                                    <option value="Malatya" data-ekdeger="44">Malatya</option>
                                                    <option value="Manisa" data-ekdeger="45">Manisa</option>
                                                    <option value="Kahramanmaraş" data-ekdeger="46">Kahramanmaraş
                                                    </option>
                                                    <option value="Mardin" data-ekdeger="47">Mardin</option>
                                                    <option value="Muğla" data-ekdeger="48">Muğla</option>
                                                    <option value="Muş" data-ekdeger="49">Muş</option>
                                                    <option value="Nevşehir" data-ekdeger="50">Nevşehir</option>
                                                    <option value="Niğde" data-ekdeger="51">Niğde</option>
                                                    <option value="Ordu" data-ekdeger="52">Ordu</option>
                                                    <option value="Rize" data-ekdeger="53">Rize</option>
                                                    <option value="Sakarya" data-ekdeger="54">Sakarya</option>
                                                    <option value="Samsun" data-ekdeger="55">Samsun</option>
                                                    <option value="Siirt" data-ekdeger="56">Siirt</option>
                                                    <option value="Sinop" data-ekdeger="57">Sinop</option>
                                                    <option value="Sivas" data-ekdeger="58">Sivas</option>
                                                    <option value="Tekirdağ" data-ekdeger="59">Tekirdağ</option>
                                                    <option value="Tokat" data-ekdeger="60">Tokat</option>
                                                    <option value="Trabzon" data-ekdeger="61">Trabzon</option>
                                                    <option value="Tunceli" data-ekdeger="62">Tunceli</option>
                                                    <option value="Şanlıurfa" data-ekdeger="63">Şanlıurfa</option>
                                                    <option value="Uşak" data-ekdeger="64">Uşak</option>
                                                    <option value="Van" data-ekdeger="65">Van</option>
                                                    <option value="Yozgat" data-ekdeger="66">Yozgat</option>
                                                    <option value="Zonguldak" data-ekdeger="67">Zonguldak</option>
                                                    <option value="Aksaray" data-ekdeger="68">Aksaray</option>
                                                    <option value="Bayburt" data-ekdeger="69">Bayburt</option>
                                                    <option value="Karaman" data-ekdeger="70">Karaman</option>
                                                    <option value="Kırıkkale" data-ekdeger="71">Kırıkkale</option>
                                                    <option value="Batman" data-ekdeger="72">Batman</option>
                                                    <option value="Şırnak" data-ekdeger="73">Şırnak</option>
                                                    <option value="Bartın" data-ekdeger="74">Bartın</option>
                                                    <option value="Ardahan" data-ekdeger="75">Ardahan</option>
                                                    <option value="Iğdır" data-ekdeger="76">Iğdır</option>
                                                    <option value="Yalova" data-ekdeger="77">Yalova</option>
                                                    <option value="Karabük" data-ekdeger="78">Karabük</option>
                                                    <option value="Kilis" data-ekdeger="79">Kilis</option>
                                                    <option value="Osmaniye" data-ekdeger="80">Osmaniye</option>
                                                    <option value="Düzce" data-ekdeger="81">Düzce</option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">COLLECT API ( https://collectapi.com/ ) <i
                                                        class="fa fa-info-circle" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Hesap > Profil > API Token bölümünde yazan token numarasını ekleyin"></i></label>
                                                <input type="text" class="form-control" name="weateher_token"
                                                    @if (isset($jsondata->weateher_token)) value="{{ $jsondata->weateher_token }}" @endif>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-4">
                                            <div class="form-group mb-0">
                                                <label class="form-label">
                                                    Anasayfa Youtube Embed Linkleri
                                                </label>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Embed Link 1</label>
                                                <input type="text" class="form-control" placeholder="Embed Link"
                                                    name="social_media_link1"
                                                    @if (isset($jsondata->social_media_link1)) value="{{ $jsondata->social_media_link1 }}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Embed Link 1</label>
                                                <input type="text" class="form-control" placeholder="Embed Link"
                                                    name="social_media_link2"
                                                    @if (isset($jsondata->social_media_link2)) value="{{ $jsondata->social_media_link2 }}" @endif>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                                <div class="box-footer"> <button type="submit" class="btn btn-primary"> <i
                                            class="ti-save-alt"></i> Kaydet </button> </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12 col-lg-5 col-xl-4 d-none">
                <div class="box box-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-img bbsr-0 bber-0"
                        style="background: url('../images/gallery/full/10.jpg') center center;" data-overlay="5">
                        <h3 class="widget-user-username text-white">Michael Jorden</h3>
                        <h6 class="widget-user-desc text-white">Designer</h6>
                    </div>
                    <div class="widget-user-image">
                        <img class="rounded-circle" src="../images/user3-128x128.jpg" alt="User Avatar">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">12K</h5>
                                    <span class="description-text">FOLLOWERS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 be-1 bs-1">
                                <div class="description-block">
                                    <h5 class="description-header">550</h5>
                                    <span class="description-text">FOLLOWERS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">158</h5>
                                    <span class="description-text">TWEETS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
                <div class="box">
                    <div class="box-body box-profile">
                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <p>Email :<span class="text-gray ps-10">David@yahoo.com</span> </p>
                                    <p>Phone :<span class="text-gray ps-10">+11 123 456 7890</span></p>
                                    <p>Address :<span class="text-gray ps-10">123, Lorem Ipsum, Florida, USA</span></p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="pb-15">
                                    <p class="mb-10">Social Profile</p>
                                    <div class="user-social-acount">
                                        <button class="btn btn-circle btn-social-icon btn-facebook"><i
                                                class="fa fa-facebook"></i></button>
                                        <button class="btn btn-circle btn-social-icon btn-twitter"><i
                                                class="fa fa-twitter"></i></button>
                                        <button class="btn btn-circle btn-social-icon btn-instagram"><i
                                                class="fa fa-instagram"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div>
                                    <div class="map-box">
                                        <iframe
                                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2805244.1745767146!2d-86.32675167439648!3d29.383165774894163!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c1766591562abf%3A0xf72e13d35bc74ed0!2sFlorida%2C+USA!5e0!3m2!1sen!2sin!4v1501665415329"
                                            width="100%" height="100" frameborder="0" style="border:0"
                                            allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div class="box">
                    <div class="box-body">
                        <div class="flexbox align-items-baseline mb-20">
                            <h6 class="text-uppercase ls-2">Friends</h6>
                            <small>20</small>
                        </div>
                        <div class="gap-items-2 gap-y">
                            <a class="avatar" href="#"><img src="../images/avatar/1.jpg" alt="..."></a>
                            <a class="avatar" href="#"><img src="../images/avatar/3.jpg" alt="..."></a>
                            <a class="avatar" href="#"><img src="../images/avatar/4.jpg" alt="..."></a>
                            <a class="avatar" href="#"><img src="../images/avatar/5.jpg" alt="..."></a>
                            <a class="avatar" href="#"><img src="../images/avatar/6.jpg" alt="..."></a>
                            <a class="avatar" href="#"><img src="../images/avatar/7.jpg" alt="..."></a>
                            <a class="avatar" href="#"><img src="../images/avatar/8.jpg" alt="..."></a>
                            <a class="avatar avatar-more" href="#">+15</a>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a class="text-uppercase d-blockls-1 text-fade" href="#">Invite People</a>
                    </div>
                </div>
                <div class="box box-inverse" style="background-color: #3b5998">
                    <div class="box-header no-border">
                        <span class="fa fa-facebook fs-30"></span>
                        <div class="box-tools pull-right">
                            <h5 class="box-title">Facebook feed</h5>
                        </div>
                    </div>

                    <blockquote class="blockquote blockquote-inverse no-border m-0 py-15">
                        <p>Holisticly benchmark plug imperatives for multifunctional deliverables. Seamlessly incubate cross
                            functional action.</p>
                        <div class="flexbox">
                            <time class="text-white" datetime="2017-11-21 20:00">21 November, 2021</time>
                            <span><i class="fa fa-heart"></i> 75</span>
                        </div>
                    </blockquote>
                </div>

            </div>

        </div>

    </section>



@endsection



@section('custom_js')
    <script src="{{ asset('backend/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
    <script src="{{ asset('backend/js/pages/advanced-form-element.js') }}"></script>

    <script>
        const input = document.getElementById('limitedInput');

        input.addEventListener('input', function() {
            if (input.value.length > 9) {
                input.value = input.value.slice(0, 9); // Fazla karakterleri keser
            }
        });
    </script>
    <script>
        $('#trend_news_status').change(function() {
            console.log('merhaba');

            if ($('#trend_news_status').val() == '1') {
                $('#trend_news_count').prop('disabled', true);
            } else {
                $('#trend_news_count').prop('disabled', false);
            }
        });
    </script>

    @if (isset($jsondata->weather_city))
        <script type="text/javascript">
            $(document).ready(function() {
                $('#weather_city option[value="{{ $jsondata->weather_city }}"]').attr('selected', 'selected');
                $('#weather_city option[value="{{ $jsondata->weather_city }}"]').trigger('change');
            })
        </script>
    @endif

    @if (isset($jsondata->prayer_city))
        <script type="text/javascript">
            $(document).ready(function() {
                $('#prayer_city option[value="{{ $jsondata->prayer_city }}"]').attr('selected', 'selected');
                $('#prayer_city option[value="{{ $jsondata->prayer_city }}"]').trigger('change');
            })
        </script>
    @endif
    <script>
        document.getElementById('settingsForm').addEventListener('submit', function() {
            const keywords = ['keyword1', 'keyword2', 'keyword3', 'keyword4', 'keyword5', 'keyword6'];

            keywords.forEach(function(name) {
                const input = document.querySelector(`input[name="${name}"]`);
                if (input && input.value.trim() !== '') {
                    input.value = input.value.toLocaleLowerCase(
                        'tr'); // Türkçe karakter duyarlı küçük harf çevirme
                }
            });
        });
    </script>
@endsection
