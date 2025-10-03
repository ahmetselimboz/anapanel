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

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<div class="col-12 pt-4">
    <div class="box">
        <div class="box-header with-border">
            <h4 class="box-title">İstatistikler</h4>
        </div>
        <div class="box-body no-padding" id="statsBody">
            <div class="row my-4">
                <div class="col-12 col-md-4 mb-3 mb-md-0">
                    <h5 class="text-center">Son 1 Gün</h5>
                    <div id="last1DayChart" style="width: 100%; height: 250px"></div>
                    <p class="text-center">Görüntülenme Sayısı: <span id="last1DayViews">Yükleniyor...</span></p>
                </div>
                <div class="col-12 col-md-4 mb-3 mb-md-0">
                    <h5 class="text-center">Son 7 Gün</h5>
                    <div id="last7DayChart" style="width: 100%; height: 250px"></div>
                    <p class="text-center">Görüntülenme Sayısı: <span id="last7DayViews">Yükleniyor...</span></p>
                </div>
                <div class="col-12 col-md-4">
                    <h5 class="text-center">Son 30 Gün</h5>
                    <div id="last30DayChart" style="width: 100%; height: 250px"></div>
                    <p class="text-center">Görüntülenme Sayısı: <span id="last30DayViews">Yükleniyor...</span></p>
                </div>
            </div>

            <div class="row my-4">
                <div id="chartdiv" style="width: 100%; height: 500px;"></div>
            </div>
        </div>
    </div>
</div>


<script>


    $(document).ready(function () {

        $("#last1DayChart").html('<div class="text-center d-flex align-items-center justify-content-center" style="height:300px"><span class="spinner-border text-info"></span></div>');
        $("#last7DayChart").html('<div class="text-center d-flex align-items-center justify-content-center" style="height:300px"><span class="spinner-border text-info"></span></div>');
        $("#last30DayChart").html('<div class="text-center d-flex align-items-center justify-content-center" style="height:300px"><span class="spinner-border text-info"></span></div>');
        $("#chartdiv").html('<div class="text-center d-flex align-items-center justify-content-center" style="height:300px"><span class="spinner-border text-info"></span></div>');

        $.ajax({
            url: "/secure/homepage-stats",
            type: "GET",
            dataType: "json",
            success: function (response) {
                console.log(response)

                let last1DaySum = response[1].reduce((sum, item) => sum + Number(item.screenPageViews), 0);
                $("#last1DayViews").text(last1DaySum);

                let last7DaySum = response[2].reduce((sum, item) => sum + Number(item.screenPageViews), 0);
                $("#last7DayViews").text(last7DaySum);

                let last30DaySum = response[3].reduce((sum, item) => sum + Number(item.screenPageViews), 0);
                $("#last30DayViews").text(last30DaySum);


                // Grafikleri güncelle
                updateCharts(response);
            },
            error: function (xhr, status, error) {
                let errorMessage = xhr.responseJSON?.error || xhr.statusText;
                let settingsUrl = "{{ route('settings') }}";
                let googleConnectUrl = "{{ route('google.connect') }}";

                // Oturum sona erme veya token ile ilgili hatalar
                if (xhr.responseJSON?.error === "invalid json token") {
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
                                        <h5 class="card-title"><i class="ti-stats-up text-primary me-2"></i> Site İstatistiklerine Erişin</h5>
                                        <p>Google Analytics entegrasyonu ile şunları görüntüleyebilirsiniz:</p>
                                        <ul>
                                            <li class="mb-2">Günlük, haftalık ve aylık görüntülenme sayıları</li>
                                            <li class="mb-2">Dünya genelinde ziyaretçi dağılımı</li>
                                            <li class="mb-2">En çok okunan içerikler</li>
                                            <li>Gerçek zamanlı ziyaretçi verileri</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <div class="card bg-light border-0">
                                    <div class="card-body text-center">
                                        <img src="{{ asset('backend/assets/analytics-icon.svg') }}" alt="Analytics" width="100" class="mb-3">
                                        <h5>Google Analytics Entegrasyonu</h5>
                                        <p class="mb-0 text-muted">Site performansınızı analiz edin</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                }
                // Mülk ID bulunamadı hataları
                else if (errorMessage.includes("404") || errorMessage.includes("Not Found")) {
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
                else {
                    try {
                        let errorJSON = JSON.parse(errorMessage)?.error;

                        // Kullanıcı doğrulama hataları
                        if (errorJSON?.status === "UNAUTHENTICATED") {
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
                                                    <li class="mb-2">Google Analytics'e erişim yetkisi olan bir hesap kullanın</li>
                                                    <li class="mb-2">Güvenlik nedeniyle, oturumunuz belirli bir süre sonra sonlanır</li>
                                                    <li>Erişim reddi alıyorsanız, Google Analytics'te hesabınıza izin verildiğinden emin olun</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 mt-3 mt-md-0">
                                        <div class="text-center p-4 bg-light rounded">
                                            <img src="{{ asset('backend/assets/analytics-icon.svg') }}" alt="Analytics" width="80" class="mb-3">
                                            <h5>Google Analytics'e bağlanın</h5>
                                            <p class="text-muted small">İstatistikleri görüntülemek için giriş yapın</p>
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
                                                <li>Google Analytics hesabınızda erişim iznine sahip olduğunuz bir mülk ID'si kullanın</li>
                                                <li>Google Analytics'te bu mülk için size izin verilmesini isteyin</li>
                                                <li>Erişim izniniz olan başka bir Google hesabıyla giriş yapın</li>
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
                                                    <div class="steps ">
                                                        <div class="step d-flex mb-3">
                                                            <div class="step-number me-3">
                                                                <span class="badge bg-info rounded-circle">1</span>
                                                            </div>
                                                            <div>
                                                                <strong class="text-dark">Google Analytics'e giriş yapın</strong>
                                                                <p class="text-muted small mb-0">analytics.google.com adresinden hesabınıza giriş yapın</p>
                                                            </div>
                                                        </div>
                                                        <div class="step d-flex mb-3">
                                                            <div class="step-number me-3">
                                                                <span class="badge bg-info rounded-circle">2</span>
                                                            </div>
                                                            <div>
                                                                <strong class="text-dark">Yönetici bölümüne gidin</strong>
                                                                <p class="text-muted small mb-0">Sol alt köşedeki "Yönetici" bölümüne tıklayın</p>
                                                            </div>
                                                        </div>
                                                        <div class="step d-flex mb-3">
                                                            <div class="step-number me-3">
                                                                <span class="badge bg-info rounded-circle">3</span>
                                                            </div>
                                                            <div>
                                                                <strong class="text-dark">Hesap erişimi yönetimi</strong>
                                                                <p class="text-muted small mb-0">"Hesap erişimi yönetimi" seçeneğini tıklayın</p>
                                                            </div>
                                                        </div>
                                                        <div class="step d-flex">
                                                            <div class="step-number me-3">
                                                                <span class="badge bg-info rounded-circle">4</span>
                                                            </div>
                                                            <div>
                                                                <strong class="text-dark">Kullanıcı ekleyin</strong>
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
                                    <p>Google Analytics verilerine erişirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.</p>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-0">Sorun devam ederse yöneticinize başvurun.</p>
                                        <a href="${googleConnectUrl}" class="btn btn-primary">
                                            <i class="ti-reload me-1"></i> Yeniden Dene
                                        </a>
                                    </div>
                                </div>
                            `);
                        }
                    } catch (parseError) {
                        // JSON parse hatası - genel hata mesajı
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
                                        <h5 class="card-title"><i class="ti-stats-up text-primary me-2"></i> Site İstatistiklerine Erişin</h5>
                                        <p>Google Analytics entegrasyonu ile anasayfa istatistiklerinizi görüntüleyebilirsiniz:</p>
                                        <ul>
                                            <li class="mb-2">Günlük, haftalık ve aylık görüntülenme sayıları</li>
                                            <li class="mb-2">Dünya genelinde ziyaretçi dağılımı</li>
                                            <li>Site performansı ve etkileşim verileri</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <div class="card bg-light border-0">
                                    <div class="card-body text-center">
                                        <img src="{{ asset('backend/assets/analytics-icon.svg') }}" alt="Analytics" width="100" class="mb-3">
                                        <h5>Google Analytics Entegrasyonu</h5>
                                        <p class="mb-0 text-muted">Site performansınızı analiz edin</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `);
                    }
                }
            }
        });


        function updateCharts(data) {
            console.log("data: ", data)

            let countryData = data[0];
            let last1DayData = data[1];
            let last7DayData = data[2];
            let last30DayData = data[3];

            // **1 Günlük Pie Chart**
            updatePieChart("#last1DayChart", last1DayData);

            // **7 Günlük Pie Chart**
            updatePieChart("#last7DayChart", last7DayData);

            // **30 Günlük Pie Chart**
            updatePieChart("#last30DayChart", last30DayData);

            // **Ülke Bazlı AMCharts Haritası**
            updateMapChart(countryData);
        }

        function updatePieChart(elementId, chartData) {
            if (chartData.length > 0) {
                let labels = [];
                let views = [];

                chartData.forEach(item => {
                    labels.push(item.pageTitle === "(not set)" ? "Bilinmiyor" : item.pageTitle);
                    views.push(parseInt(item.screenPageViews));

                });

                // Eğer tüm değerler 0 ise, grafik boş gözükmesin diye bir veri ekle
                if (views.every(view => view === 0)) {
                    labels.push("Veri Yok");
                    views.push(0.1);
                }

                // Eski grafik varsa sil
                if (window[elementId]) {
                    window[elementId].destroy();
                }

                $(elementId).empty();

                let options = {
                    series: views,
                    chart: { type: "pie", height: 250 },
                    labels: labels,
                    legend: { show: false }
                };

                window[elementId] = new ApexCharts(document.querySelector(elementId), options);
                window[elementId].render();
            } else {
                $(elementId).html('<p class="text-danger text-center d-flex align-items-center justify-content-center" style="height: 100%">Ziyaret Yok!</p>');
            }
        }

        function updateMapChart(countryData) {

            $("#chartdiv").empty();

            am5.ready(function () {
                var root = am5.Root.new("chartdiv");
                root.setThemes([am5themes_Animated.new(root)]);

                // 🌍 Harita oluştur
                var chart = root.container.children.push(
                    am5map.MapChart.new(root, {
                        panX: "none", // 🔴 Sürükleme kapatıldı
                        panY: "none",
                        projection: am5map.geoMercator(),
                        zoomControl: false
                    })
                );


                // 🗺️ Dünya haritası ekle ve Antarktika'yı hariç tut
                var polygonSeries = chart.series.push(
                    am5map.MapPolygonSeries.new(root, {
                        geoJSON: am5geodata_worldLow,
                        exclude: ["AQ"] // ❌ Antarktika'yı kaldır
                    })
                );


                // ✨ Tooltip ekle
                polygonSeries.mapPolygons.template.set("interactive", true);


                let countryMap = {};
                countryData.forEach(country => {
                    let alpha2Code = convertToAlpha2(country.countryCode);
                    countryMap[alpha2Code] = {
                        name: country.country,
                        screenPageViews: country.screenPageViews
                    };
                });


                polygonSeries.mapPolygons.template.adapters.add("tooltipText", function (text, target) {
                    let id = target.dataItem?.get("id");
                    let countryInfo = countryMap[id] || {
                        name: target.dataItem?.dataContext?.name,
                        screenPageViews: 0
                    };

                    return `${countryInfo.name}: ${countryInfo.screenPageViews} Görüntüleme`;
                });


                polygonSeries.mapPolygons.template.adapters.add("fill", function (fill, target) {
                    let id = target.dataItem?.get("id");

                    // 🔄 **Ülke Kodunu Normalize Et** (Eğer eşleşme yoksa büyük harfe çevirip tekrar dene)
                    if (!countryMap[id]) {
                        id = id?.toUpperCase();
                    }
                    // if (!countryMap[id]) {
                    //     console.warn(`Ülke kodu eşleşmedi: ${id}`);
                    // }

                    let screenPageViews = parseInt(countryMap[id]?.screenPageViews) || 0;

                    // 🌎 Tüm kullanıcı verisini al (String -> Number dönüşümü ekledik)
                    let values = Object.values(countryMap).map(c => parseInt(c.screenPageViews) || 0);
                    let minUsers = Math.min(...values);
                    let maxUsers = Math.max(...values);

                    // ❗ Eğer tamamen boş harita varsa, tüm ülkeleri gri yap
                    if (maxUsers === 0) {
                        return am5.color("#e0e0e0");
                    }

                    // 🔢 **Doğru Normalizasyon (1 olanlar da maviye girsin)**
                    if (minUsers < 1) {
                        minUsers = 1; // En düşük değeri 1 kabul et
                    }

                    let normalizedValue = 0;
                    if (screenPageViews > 0 && maxUsers !== minUsers) {
                        normalizedValue = (screenPageViews - minUsers) / (maxUsers - minUsers || 1);
                    }
                    // Eğer hepsi aynı değerse, normalizasyonu ortada tut
                    if (maxUsers === minUsers && maxUsers > 0) {
                        normalizedValue = 0.5;
                    }

                    // 🎨 **Yeni Renk Skalası**
                    let startColor = { r: 48, g: 186, b: 255 };  // Açık Mavi
                    let midColor = { r: 0, g: 136, b: 204 };    // Orta Mavi
                    let endColor = { r: 0, g: 65, b: 102 };     // Koyu Mavi
                    let grayColor = { r: 224, g: 224, b: 224 };  // Gri

                    // 🛑 **Ziyaretçi Yoksa Gri Yap (Ama 1 olanlar dahil değil)**
                    if (screenPageViews === 0) {
                        return am5.color(rgbToHex(grayColor.r, grayColor.g, grayColor.b));
                    }

                    // 🔥 **Üçlü Renk Geçişi (Interpolasyon)**
                    let r, g, b;
                    if (normalizedValue < 0.5) {
                        let factor = normalizedValue * 2; // 0 ile 0.5 arasında
                        r = Math.round(startColor.r + (midColor.r - startColor.r) * factor);
                        g = Math.round(startColor.g + (midColor.g - startColor.g) * factor);
                        b = Math.round(startColor.b + (midColor.b - startColor.b) * factor);
                    } else {
                        let factor = (normalizedValue - 0.5) * 2; // 0.5 ile 1 arasında
                        r = Math.round(midColor.r + (endColor.r - midColor.r) * factor);
                        g = Math.round(midColor.g + (endColor.g - midColor.g) * factor);
                        b = Math.round(midColor.b + (endColor.b - midColor.b) * factor);
                    }

                    // 🎨 **RGB → HEX dönüşümü**
                    function rgbToHex(r, g, b) {
                        return "#" + [r, g, b].map(x => x.toString(16).padStart(2, "0")).join("");
                    }

                    let hexColor = rgbToHex(r, g, b);

                    return am5.color(hexColor);
                });


                // 🔥 Marka bilgisini kaldır
                root._logo.dispose();
            });

        }

        const countryCodeMap = {
            "USA": "US", // Amerika Birleşik Devletleri
            "GBR": "GB", // Birleşik Krallık
            "DEU": "DE", // Almanya
            "FRA": "FR", // Fransa
            "ESP": "ES", // İspanya
            "RUS": "RU", // Rusya
            "CHN": "CN", // Çin
            "IND": "IN", // Hindistan
            "BRA": "BR", // Brezilya
            "CAN": "CA", // Kanada
            "AUS": "AU", // Avustralya
            "JPN": "JP", // Japonya
            "TUR": "TR", // Türkiye
            "ITA": "IT", // İtalya
            "NLD": "NL", // Hollanda
            "MEX": "MX", // Meksika
            "KOR": "KR", // Güney Kore
            "SAU": "SA", // Suudi Arabistan
            "ARG": "AR", // Arjantin
            "ZAF": "ZA", // Güney Afrika
            "POL": "PL", // Polonya
            "ALB": "AL", // Arnavutluk
            "IRL": "IE", // İrlanda
            "SYC": "SC", // Seyşeller
            "(not set)": null // Geçersiz veri (haritaya eklenmeyecek)
        };

        function convertToAlpha2(countryCode) {
            return countryCodeMap[countryCode] || countryCode;  // Eğer eşleşme yoksa, orijinal kodu kullan
        }


    })


</script>