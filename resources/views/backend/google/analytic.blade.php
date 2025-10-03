@extends('backend.layout')

@section("custom_css")
    <style>
        .tooltip-container-stats {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .tooltip-stats {
            visibility: hidden;
            background-color: #333;
            color: #fff;
            text-align: left;
            padding: 10px;
            border-radius: 6px;
            width: 300px;
            position: absolute;
            top: -130px;
            /*left: -60px;*/
            z-index: 10;
            opacity: 0;
            transition: opacity 0.3s, visibility 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 14px;
            line-height: 1.5;
        }

        .tooltip-container-stats:hover .tooltip-stats {
            visibility: visible;
            opacity: 1;
        }
    </style>
@endsection


@section('content')
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12 pt-4">
                    <div class="box">
                        <div
                            class="box-header with-border d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                            <h4 class="box-title">Google Analitik</h4>
                            <div class="d-flex flex-column flex-sm-row align-items-center mt-2 mt-lg-0">
                                <p class="m-0 me-2 text-muted">
                                    <small>
                                        <i class="ti-user me-1"></i> {{ session('google_user_email') }}
                                        <a href="{{ route('logoutGoogle') }}"
                                            class="btn btn-sm btn-danger ms-2" id="logoutGoogle">Çıkış Yap</a>
                                    </small>
                                </p>
                            </div>
                            <div class="d-flex flex-column flex-sm-row align-items-center mt-4 mt-lg-0" id="date-box">
                                <p class="m-0 me-2 w-100">Tarih Alanı:</p>
                                <select class="form-select w-75" aria-label="Default select example" id="date-select">
                                    <option value="1">Son 1 Gün</option>
                                    <option value="7">Son 7 Gün</option>
                                    <option value="30" selected>Son 30 Gün</option>
                                </select>
                            </div>
                        </div>
                        <div class="box-body no-padding" id="statsBody">
                            @if(session('google_user_email'))
                                <div class="alert alert-info alert-dismissible fade show mx-3 mt-3" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <i class="ti-info-alt me-2"></i>Google Analytics verilerini
                                    <strong>{{ session('google_user_email') }}</strong> hesabıyla görüntülüyorsunuz.
                                    <br>
                                    <small>Farklı bir hesap kullanmak için önce <a href="{{ route('logoutGoogle') }}"
                                            class="alert-link">çıkış yapın</a>, sonra <a href="{{ route('google.connect') }}"
                                            class="alert-link">yeniden giriş yapın</a>.</small>
                                    <br>
                                    <small><strong>Not:</strong> Google Analytics verilerine erişmek için Google Analytics
                                        hesabınızda ilgili mülke erişim izninizin olması gerekir.</small>
                                </div>
                            @endif
                            <div class="row px-3 pt-4">
                                <div class="col-12">
                                    <h4>Özet</h4>
                                </div>
                                <div class="col-12 col-md-4 col-lg-2 px-1">
                                    <div class="card p-3 rounded-lg">
                                        <span class="text-muted">Etkin Kullanıcı Sayısı</span>
                                        <h1 class="text-end" id="activeUsers"></h1>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-2 px-1">
                                    <div class="card p-3 rounded-lg">
                                        <span class="text-muted">Görüntüleme Sayısı</span>
                                        <h1 class="text-end" id="screenPageViews"></h1>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-2 px-1">
                                    <div class="card p-3 rounded-lg">
                                        <span class="text-muted">Yeni Kullanıcı Sayısı</span>
                                        <h1 class="text-end" id="newUsers"></h1>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-2 px-1">
                                    <div class="card p-3 rounded-lg">
                                        <span class="text-muted">Toplam Kullanıcı Sayısı</span>
                                        <h1 class="text-end" id="totalUsers"></h1>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-2 px-1">
                                    <div class="card p-3 rounded-lg">
                                        <span class="text-muted">Etkinlik Sayısı</span>
                                        <h1 class="text-end" id="eventCount"></h1>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-2 px-1">
                                    <div class="card p-3 rounded-lg">
                                        <span class="text-muted">Ortalama Oturum Süresi</span>
                                        <h1 class="text-end" id="averageSessionDuration"></h1>
                                    </div>
                                </div>
                            </div>
                            <hr class="mx-3">
                            <div class="row px-3 py-4">
                                <div class="col-12 mb-4">
                                    <h4>Kategorilere Göre Görüntüleme Sayıları</h4>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 my-4 mt-lg-0">
                                    <h5 class="text-center">Cihaz Türleri</h5>
                                    <div id="deviceCategoryChart"></div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 my-4 mb-lg-3">
                                    <h5 class="text-center">İşletim Sistemi</h5>
                                    <div id="operatingSystemChart"></div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 my-4 mb-lg-3">
                                    <h5 class="text-center">Tarayıcı Bilgisi</h5>
                                    <div id="browserChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


@section('custom_js')
    <script src="{{ asset('backend/assets/vendor_plugins/iCheck/icheck.js') }}"></script>
    <script src="{{ asset('backend/js/pages/app-contact.js') }}"></script>

    <script src="{{ asset('backend/assets/vendor_components/apexcharts-bundle/dist/apexcharts.js') }}"></script>
    <script src="{{ asset('backend/js/pages/widgets.js') }}"></script>
    <script>
        // Hata verisi çekme işlemi
        async function allData(date) {
            try {
                const response = await $.ajax({
                    url: "/secure/analytic-page-all-data?date=" + date,
                    type: "GET",
                    dataType: "json",
                });

                const data = response[0] || {};



                $("#activeUsers").text(data.activeUsers || 0);
                $("#screenPageViews").text(data.screenPageViews || 0);
                $("#newUsers").text(data.newUsers || 0);
                $("#totalUsers").text(data.totalUsers || 0);
                $("#eventCount").text(data.eventCount || 0);
                $("#averageSessionDuration").text(
                    `00:${data.averageSessionDurationMinutes || 0}:${data.averageSessionDurationSeconds || 0}`
                );

                return null; // Hata olmadığında null döndürüyoruz.
            } catch (xhr) {
              
                return xhr.responseJSON?.error || xhr.statusText;
            }
        }

        async function deviceCategory(date) {
            try {
                const response = await $.ajax({
                    url: "/secure/analytic-page-device-category?date=" + date,
                    type: "GET",
                    dataType: "json",
                });

                const data = response || {};



                if (data.length > 0) {
                    let deviceChart;
                    let deviceLabels = [];
                    let deviceViews = [];
                    let label;
                    data.forEach(item => {
                        if (item.deviceCategory === "desktop") {
                            label = "Masaüstü";
                        } else if (item.deviceCategory === "mobile") {
                            label = "Mobil";
                        } else if (item.deviceCategory === "tablet") {
                            label = "Tablet";
                        } else {
                            label = item.deviceCategory;
                        }

                        deviceLabels.push(label);
                        deviceViews.push(parseInt(item.screenPageViews));

                    });

                    // Eğer eski grafik varsa önce temizle
                    if (deviceChart) {
                        deviceChart.destroy();
                    }

                    $("#deviceCategoryChart").empty();
                    // 📌 ApexCharts ile pasta grafiği oluştur
                    let options = {
                        series: deviceViews,
                        chart: {
                            type: "pie",
                            height: 300
                        },
                        labels: deviceLabels,
                        colors: ["#007bff", "#28a745", "#ffc107"], // Renkler özelleştirilebilir
                        legend: {
                            position: "bottom"
                        }
                    };

                    deviceChart = new ApexCharts(document.querySelector("#deviceCategoryChart"), options);
                    deviceChart.render();
                } else {
                    $("#deviceCategoryChart").html('<p class="text-danger text-center d-flex align-items-center justify-content-center" style="height: 100%">Veri yok!</p>');
                }

                return null;
            } catch (xhr) {
                return xhr.responseJSON?.error || xhr.statusText;
            }
        }

        async function operatingSystem(date) {
            try {
                const response = await $.ajax({
                    url: "/secure/analytic-page-operating-system?date=" + date,
                    type: "GET",
                    dataType: "json",
                });

                const data = response || {};



                if (data.length > 0) {
                    let systemChart;
                    let systemLabels = [];
                    let systemViews = [];
                    let label;
                    data.forEach(item => {
                        systemLabels.push(item.operatingSystem);
                        systemViews.push(parseInt(item.screenPageViews));

                    });

                    // Eğer eski grafik varsa önce temizle
                    if (systemChart) {
                        systemChart.destroy();
                    }

                    $("#operatingSystemChart").empty();
                    // 📌 ApexCharts ile pasta grafiği oluştur
                    let options = {
                        series: systemViews,
                        chart: {
                            type: "pie",
                            height: 300
                        },
                        labels: systemLabels,
                        colors: ["#007bff", "#28a745", "#ffc107"], // Renkler özelleştirilebilir
                        legend: {
                            position: "bottom"
                        }
                    };

                    systemChart = new ApexCharts(document.querySelector("#operatingSystemChart"), options);
                    systemChart.render();
                } else {
                    $("#operatingSystemChart").html('<p class="text-danger text-center d-flex align-items-center justify-content-center" style="height: 100%">Veri yok!</p>');
                }

                return null;
            } catch (xhr) {
                return xhr.responseJSON?.error || xhr.statusText;
            }
        }

        async function browser(date) {
            try {
                const response = await $.ajax({
                    url: "/secure/analytic-page-browser?date=" + date,
                    type: "GET",
                    dataType: "json",
                });

                const data = response || {};



                if (data.length > 0) {
                    let browserChart;
                    let browserLabels = [];
                    let browserViews = [];

                    data.forEach(item => {
                        browserLabels.push(item.browser);
                        browserViews.push(parseInt(item.screenPageViews));

                    });

                    // Eğer eski grafik varsa önce temizle
                    if (browserChart) {
                        browserChart.destroy();
                    }

                    $("#browserChart").empty();
                    // 📌 ApexCharts ile pasta grafiği oluştur
                    let options = {
                        series: browserViews,
                        chart: {
                            type: "pie",
                            height: 300
                        },
                        labels: browserLabels,
                        colors: ["#007bff", "#28a745", "#ffc107"], // Renkler özelleştirilebilir
                        legend: {
                            position: "bottom"
                        }
                    };

                    browserChart = new ApexCharts(document.querySelector("#browserChart"), options);
                    browserChart.render();
                } else {
                    $("#browserChart").html('<p class="text-danger text-center d-flex align-items-center justify-content-center" style="height: 100%">Veri yok!</p>');
                }

                return null;
            } catch (xhr) {
                return xhr.responseJSON?.error || xhr.statusText;
            }
        }




        // Ana işlem akışı
        async function handleErrors(date) {
            const results = await Promise.all([
                allData(date),
                deviceCategory(date),
                operatingSystem(date),
                browser(date),
            ]);

            const errorList = results.filter(result => result); // Hata içeren sonuçları filtrele
 
            if (errorList.length === 0) return; // Hata yoksa işlem sonlanır

            errorData = errorList[0]

            if (errorData !== undefined) {
                $("#date-box").remove()
            }

            let settingsUrl = "{{ route('settings') }}";
            let googleConnectUrl = "{{ route('google.connect') }}";

            // Oturum sona erme veya token ile ilgili hatalar
            if (errorData === "invalid json token" || errorData === "OK") {
                $("#logoutGoogle").remove();
                $("#statsBody").html(`
                            <div class="alert alert-warning mx-3 mt-3">
                                <h4 class="alert-heading"><i class="ti-lock me-2"></i> Google Hesabı Doğrulama Gerekli</h4>
                                <p>Google Analytics verilerini görüntülemek için Google hesabınızla giriş yapmanız gerekiyor.</p>
                                <hr>
                                <p class="mb-0">
                                    <a href="${googleConnectUrl}" class="btn btn-primary">
                                        <i class="ti-google me-1"></i> Google Hesabınla Giriş Yap
                                    </a>
                                </p>
                            </div>
                            <div class="row mx-1 mt-4">
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title"><i class="ti-stats-up text-primary me-2"></i> Analitik Verilerinize Erişin</h5>
                                            <p>Google Analytics entegrasyonu ile şunları yapabilirsiniz:</p>
                                            <ul>
                                                <li class="mb-2">Web sitenizin ziyaretçi sayılarını takip edin</li>
                                                <li class="mb-2">Kullanıcıların site içi davranışlarını inceleyin</li>
                                                <li class="mb-2">Trafik kaynaklarını analiz edin</li>
                                                <li class="mb-2">Kullanıcı demografik verilerini görüntüleyin</li>
                                                <li>Gerçek zamanlı verilere erişin</li>
                                            </ul>
                                            <div class="mt-3">
                                                <a href="${googleConnectUrl}" class="btn btn-primary btn-lg w-100">
                                                    <i class="ti-google me-1"></i> Google ile Giriş Yap
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="card bg-light border-0">
                                                <div class="card-body text-center">
                                                    <img src="{{ asset('backend/assets/analytics-icon.svg') }}" alt="Analytics" width="100" class="mb-3">
                                                    <h5>Google Analytics Entegrasyonu</h5>
                                                    <p class="mb-0 text-muted">Trafik verilerinize anında erişin</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="card bg-light border-0">
                                                <div class="card-body">
                                                    <h5 class="card-title"><i class="ti-help-alt text-info me-2"></i> Ne Yapmanız Gerekiyor?</h5>
                                                    <ol class="ps-3 mb-0">
                                                        <li class="mb-2">Google Analytics'e erişim yetkisi olan hesabınızla giriş yapın</li>
                                                        <li class="mb-2">Site ayarlarında doğru Google Analytics Mülk ID'sini ayarlayın</li>
                                                        <li>Artık analitik verilerinizi görebilirsiniz</li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
            }
            // Mülk ID bulunamadı hataları
            else if (errorData.includes("404") || errorData.includes("Not Found")) {
                $("#statsBody").html(`
                                                <div class="alert alert-danger mx-3 mt-3">
                                                    <h4 class="alert-heading"><i class="ti-na me-2"></i> Mülk Kimliği Bulunamadı!</h4>
                                                    <p>Google Analytics mülk kimliği eksik veya hatalı. Ayarlardan doğru mülk kimliğini eklemeniz gerekiyor.</p>
                                                    <hr>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>Gerekli Adımlar:</strong>
                                                            <ol class="mb-0 ps-3">
                                                                <li>Ayarlar sayfasına gidin</li>
                                                                <li>"Google Analitik Mülk Kimliği" alanını doldurun</li>
                                                                <li>Değişiklikleri kaydedin</li>
                                                            </ol>
                                                        </div>
                                                        <a href="${settingsUrl}" class="btn btn-primary">
                                                            <i class="ti-settings me-1"></i> Ayarlara Git
                                                        </a>
                                                    </div>
                                                </div>
                                            `);
            }
            else {
                try {
                  
                    const errorJSON = JSON.parse(errorData)?.error;

                    

                    // Kullanıcı doğrulama hataları
                    if (errorJSON?.status === "UNAUTHENTICATED") {
                        $("#logoutGoogle").remove();
                        $("#statsBody").html(`
                                        <div class="alert alert-warning mx-3 mt-3">
                                            <h4 class="alert-heading"><i class="ti-face-sad me-2"></i> Oturum Süresi Doldu</h4>
                                            <p>Google Analytics oturumunuz sona ermiş. Verileri görüntülemek için yeniden giriş yapmanız gerekiyor.</p>
                                            <hr>
                                            <p class="mb-0">
                                                <a href="${googleConnectUrl}" class="btn btn-primary">
                                                    <i class="ti-reload me-1"></i> Yeniden Giriş Yap
                                                </a>
                                            </p>
                                        </div>
                                        <div class="row mx-1 mt-4">
                                            <div class="col-md-7">
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-body">
                                                        <h5 class="card-title"><i class="ti-info-alt text-primary me-2"></i> Google Hesabıyla Giriş Yapma</h5>
                                                        <p>Google Analytics verilerini görüntülemek için şunlara dikkat edin:</p>
                                                        <ul class="ps-3">
                                                            <li class="mb-2">Google Analytics'e erişim yetkisi olan bir Google hesabı kullanın</li>
                                                            <li class="mb-2">Güvenlik nedeniyle, oturumunuz belirli bir süre sonra otomatik olarak sonlanır</li>
                                                            <li class="mb-2">Erişim reddi alıyorsanız, Google Analytics'te hesabınıza izin verildiğinden emin olun</li>
                                                            
                                                        </ul>
                                                        <div class="mt-3">
                                                            <a href="${googleConnectUrl}" class="btn btn-primary">
                                                                <i class="ti-google me-1"></i> Google ile Giriş Yap
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 mt-3 mt-md-0">
                                                <div class="text-center p-4 bg-light rounded">
                                                    <img src="{{ asset('backend/assets/analytics-icon.svg') }}" alt="Analytics" width="120" class="mb-3">
                                                    <h5>Google Analytics'e bağlanın</h5>
                                                    <p class="text-muted">Trafik istatistiklerinizi görüntülemek için Google Analytics hesabınıza bağlanmanız gerekiyor.</p>
                                                    <div class="d-grid mt-3">
                                                        <a href="https://analytics.google.com/analytics/web/" target="_blank" class="btn btn-outline-secondary mt-2">
                                                            <i class="ti-new-window me-1"></i> Google Analytics'i Kontrol Et
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `);
                    }
                    // Erişim izni hataları
                    else if (errorJSON?.status === "PERMISSION_DENIED") {
                        $("#statsBody").html(`
                                            <div class="alert alert-danger mx-3 mt-3">
                                                <h4 class="alert-heading"><i class="ti-lock me-2"></i> Erişim İzni Reddedildi</h4>
                                                <p><strong>{{ session('google_user_email') }}</strong> hesabınız ile bu Analytics verilerine erişim izniniz bulunmuyor.</p>
                                                <hr>
                                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                                    <div>
                                                        <strong>Çözüm Önerileri:</strong>
                                                        <ol class="mb-0 ps-3">
                                                            <li>Emailinizin ({{ session('google_user_email') }}) erişim iznine sahip olduğuna emin olun</li>
                                                            <li>Erişim izni verilmiş ise                                    
                                                                <a href="${settingsUrl}" class="">
                                                                    Ayarlardan
                                                                </a> 
                                                                mülk kimliğinin doğru olduğundan emin olun
                                                            </li>
                                                        </ol>
                                                    </div>
                                                    <div class="mt-3 mt-md-0">
                                                        <a href="${googleConnectUrl}" class="btn btn-primary">
                                                            <i class="ti-reload me-1"></i> Farklı Hesapla Giriş Yap
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mx-1 mt-4">
                                                <div class="col-12 col-md-7">
                                                    <div class="card border-0 bg-light">
                                                        <div class="card-body">
                                                            <h5 class="card-title"><i class="ti-info-alt text-info me-2"></i> Google Analytics'te İzin Ayarları</h5>
                                                            <div class="card-text">
                                                                <div class="steps">
                                                                    <div class="step d-flex mb-3">
                                                                        <div class="step-number me-3">
                                                                            <span class="badge bg-info rounded-circle">1</span>
                                                                        </div>
                                                                        <div>
                                                                            <strong>Google Analytics'e giriş yapın</strong>
                                                                            <p class="text-muted small mb-0">analytics.google.com adresinden hesabınıza giriş yapın</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="step d-flex mb-3">
                                                                        <div class="step-number me-3">
                                                                            <span class="badge bg-info rounded-circle">2</span>
                                                                        </div>
                                                                        <div>
                                                                            <strong>Yönetici bölümüne gidin</strong>
                                                                            <p class="text-muted small mb-0">Sol alt köşedeki "Yönetici" bölümüne tıklayın</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="step d-flex mb-3">
                                                                        <div class="step-number me-3">
                                                                            <span class="badge bg-info rounded-circle">3</span>
                                                                        </div>
                                                                        <div>
                                                                            <strong>Hesap erişimi yönetimi</strong>
                                                                            <p class="text-muted small mb-0">"Hesap erişimi yönetimi" seçeneğini tıklayın</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="step d-flex">
                                                                        <div class="step-number me-3">
                                                                            <span class="badge bg-info rounded-circle">4</span>
                                                                        </div>
                                                                        <div>
                                                                            <strong>Kullanıcı ekleyin</strong>
                                                                            <p class="text-muted small mb-0">"+" butonuna tıklayarak kullanıcı ekleyin ve e-posta adresini girin</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-5 mt-3 mt-md-0">
                                                    <div class="text-center p-4">
                                                        <img src="{{ asset('backend/assets/analytics-icon.svg') }}" alt="Analytics" width="150" class="mb-3 opacity-40">
                                                        <div class="mt-3">
                                                            <a href="https://analytics.google.com/analytics/web/" target="_blank" class="btn btn-outline-secondary mt-2">
                                                                <i class="ti-new-window me-1"></i> Google Analytics'i Aç
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        `);
                    }
                    // Mülk ID sorunları
                    else if (errorJSON?.message && errorJSON.message.includes("property")) {
                        $("#statsBody").html(`
                                            <div class="alert alert-warning mx-3 mt-3">
                                                <h4 class="alert-heading"><i class="ti-alert me-2"></i> Mülk Kimliği Eşleşmiyor</h4>
                                                <p>Ayarlarda tanımladığınız Google Analytics mülk kimliği <strong>Google hesabınızla eşleşmiyor</strong> veya hesabınızın bu mülke erişim izni yok.</p>
                                                <hr>
                                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                                    <p class="mb-2 mb-md-0">Google Analytics'teki mülk kimliğinizi kontrol edin ve ayarlarda güncelleyin.</p>
                                                    <a href="${settingsUrl}" class="btn btn-warning">
                                                        <i class="ti-settings me-1"></i> Ayarları Düzenle
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row mx-1 mt-4">
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body">
                                                            <h5 class="card-title"><i class="ti-check text-success me-2"></i> Doğru Mülk Kimliği Nasıl Bulunur?</h5>
                                                            <ol class="ps-3 mb-0">
                                                                <li class="mb-2">Google Analytics hesabınıza giriş yapın</li>
                                                                <li class="mb-2">Sol menüden "Yönetici" seçeneğine tıklayın</li>
                                                                <li class="mb-2">Hesap > Mülk sütununda istediğiniz mülkü seçin</li>
                                                                <li class="mb-2">Mülk Ayarları > Mülk Kimliği değerini kopyalayın</li>
                                                                <li>Bu kimliği ayarlar sayfasına ekleyin</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-3 mt-md-0">
                                                    <div class="card bg-light border-0">
                                                        <div class="card-body">
                                                            <h5 class="card-title"><i class="ti-info-alt text-info me-2"></i> Mülk Kimliği Nedir?</h5>
                                                            <p>Mülk kimliği, <strong>Google Analytics hesabınızdaki</strong> belirli bir web sitesi veya uygulamayı tanımlayan benzersiz bir ID'dir.</p>
                                                            <p class="mb-0">Örnek bir mülk kimliği: <code>123456789</code> (sayılardan oluşur)</p>
                                                            <div class="text-center mt-3">
                                                                <a href="https://analytics.google.com/analytics/web/" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                    <i class="ti-new-window me-1"></i> Google Analytics'i Aç
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        `);
                    }
                    // Genel hatalar
                    else {
                        $("#statsBody").html(`
                                                        <div class="alert alert-danger mx-3 mt-3">
                                                            <h4 class="alert-heading"><i class="ti-na me-2"></i> Hata Oluştu</h4>
                                                            <p>Google Analytics verilerine erişirken bir hata oluştu</p>
                                                            <hr>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <p class="mb-0">Lütfen daha sonra tekrar deneyin veya yöneticinizle iletişime geçin.</p>
                                                                <a href="${googleConnectUrl}" class="btn btn-primary">
                                                                    <i class="ti-reload me-1"></i> Yeniden Dene
                                                                </a>
                                                            </div>
                                                        </div>
                                                    `);
                    }
                } catch (error) {
                    // JSON parse hatası - genel hata mesajı
                    $("#logoutGoogle").remove();
                    $("#statsBody").html(`
                                             <div class="alert alert-warning mx-3 mt-3">
                                <h4 class="alert-heading"><i class="ti-lock me-2"></i> Google Hesabı Doğrulama Gerekli</h4>
                                <p>Google Analytics verilerini görüntülemek için Google hesabınızla giriş yapmanız gerekiyor.</p>
                                <hr>
                                <p class="mb-0">
                                    <a href="${googleConnectUrl}" class="btn btn-primary">
                                        <i class="ti-google me-1"></i> Google Hesabınla Giriş Yap
                                    </a>
                                </p>
                            </div>
                            <div class="row mx-1 mt-4">
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title"><i class="ti-stats-up text-primary me-2"></i> Analitik Verilerinize Erişin</h5>
                                            <p>Google Analytics entegrasyonu ile şunları yapabilirsiniz:</p>
                                            <ul>
                                                <li class="mb-2">Web sitenizin ziyaretçi sayılarını takip edin</li>
                                                <li class="mb-2">Kullanıcıların site içi davranışlarını inceleyin</li>
                                                <li class="mb-2">Trafik kaynaklarını analiz edin</li>
                                                <li class="mb-2">Kullanıcı demografik verilerini görüntüleyin</li>
                                              
                                            </ul>
                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="card bg-light border-0">
                                                <div class="card-body text-center">
                                                    <img src="{{ asset('backend/assets/analytics-icon.svg') }}" alt="Analytics" width="100" class="mb-3">
                                                    <h5>Google Analytics Entegrasyonu</h5>
                                                    <p class="mb-0 text-muted">Trafik verilerinize anında erişin</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="card bg-light border-0">
                                                <div class="card-body">
                                                    <h5 class="card-title"><i class="ti-help-alt text-info me-2"></i> Ne Yapmanız Gerekiyor?</h5>
                                                    <ol class="ps-3 mb-0">
                                                        <li class="mb-2">Google Analytics'e erişim yetkisi olan hesabınızla giriş yapın</li>
                                                        <li class="mb-2">Site ayarlarında doğru Google Analytics Mülk ID'sini ayarlayın</li>
                                                        <li>Artık analitik verilerinizi görebilirsiniz</li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                                `);
                }
            }
        }

        function spinners() {

            $("#activeUsers").html('<div class="text-center d-flex align-items-center justify-content-end" ><span class="spinner-border text-info"></span></div>');
            $("#screenPageViews").html('<div class="text-center d-flex align-items-center justify-content-end" ><span class="spinner-border text-info"></span></div>');
            $("#newUsers").html('<div class="text-center d-flex align-items-center justify-content-end" ><span class="spinner-border text-info"></span></div>');
            $("#totalUsers").html('<div class="text-center d-flex align-items-center justify-content-end"><span class="spinner-border text-info"></span></div>');
            $("#eventCount").html('<div class="text-center d-flex align-items-center justify-content-end"><span class="spinner-border text-info"></span></div>');
            $("#averageSessionDuration").html('<div class="text-center d-flex align-items-center justify-content-end" ><span class="spinner-border text-info"></span></div>');
            $("#deviceCategoryChart").html('<div class="text-center d-flex align-items-center justify-content-center" style="height:300px"  ><span class="spinner-border text-info"></span></div>');
            $("#operatingSystemChart").html('<div class="text-center d-flex align-items-center justify-content-center" style="height:300px"  ><span class="spinner-border text-info"></span></div>');
            $("#browserChart").html('<div class="text-center d-flex align-items-center justify-content-center" style="height:300px"  ><span class="spinner-border text-info"></span></div>');
        }

        // Sayfa yüklendiğinde çalıştır
        $(document).ready(() => {


            spinners();


            let selectedValue = $('#date-select').val(); // Başlangıçta seçili değeri al
            handleErrors(selectedValue); // İlk başta çalıştır

            $('#date-select').on('change', function () {



                selectedValue = $(this).val();
                spinners();
                handleErrors(selectedValue); // Değişiklik olduğunda çalıştır
            });

        });


    </script>

@endsection