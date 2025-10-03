<style>
    .tooltip-container {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .tooltip {
        visibility: hidden;
        background-color: #333;
        color: #fff;
        text-align: left;
        padding: 10px;
        border-radius: 6px;
        width: 300px;
        position: absolute;
        top: 35px;
        /*left: -60px;*/
        z-index: 10;
        opacity: 0;
        transition: opacity 0.3s, visibility 0.3s;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        font-size: 14px;
        line-height: 1.5;
    }

    .tooltip-container:hover .tooltip {
        visibility: visible;
        opacity: 1;
    }
</style>
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-primary">
                <h4 id="modal-title" class="text-center m-2"></h4>
                <button type="button" id="close-model" class="btn-close text-white" data-bs-dismiss="modal"
                    aria-label="Kapat"></button>
            </div>
            <hr class="mt-0">
            <div class="modal-body" id="modalBody">
                <div class="row">
                    <div class="col-12 col-md-4 mb-3">
                        <div class="card border border-info shadow-lg">
                            <div class="row">
                                <div class="col-5 d-flex align-items-center justify-content-center">
                                    <i data-feather="map-pin" class="text-info" style="width: 100%; height: 40%;"></i>
                                </div>
                                <div class="col-7 p-0 d-flex flex-column align-items-center justify-content-center p-3">
                                    <h3 class="ms-2">
                                        <strong id="statsViews" class="card-title">
                                            <span class="loading-spinner spinner-border text-info"></span>
                                        </strong>
                                    </h3>
                                    <span class="text-muted" style="font-size: 12px">Görüntüleme Sayısı</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mb-3">
                        <div class="card border border-danger shadow-lg">
                            <div class="row">
                                <div class="col-5 d-flex align-items-center justify-content-center">
                                    <i data-feather="user" class="text-danger" style="width: 100%; height: 40%;"></i>
                                </div>
                                <div class="col-7 p-0 d-flex flex-column align-items-center justify-content-center p-3">
                                    <h3 class="ms-2">
                                        <strong id="statsActiveUsers" class="card-title">
                                            <span class="loading-spinner spinner-border text-info"></span>
                                        </strong>
                                    </h3>
                                    <span class="text-muted" style="font-size: 12px">Etkin Kullanıcı Sayısı</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mb-3">
                        <div class="card border border-success shadow-lg">
                            <div class="row">
                                <div class="col-5 d-flex align-items-center justify-content-center">
                                    <i data-feather="activity" class="text-success"
                                        style="width: 100%; height: 40%;"></i>
                                </div>
                                <div class="col-7 p-0 d-flex flex-column align-items-center justify-content-center p-3">
                                    <h3 class="ms-2">
                                        <strong id="statsEventCount" class="card-title">
                                            <span class="loading-spinner spinner-border text-info"></span>
                                        </strong>
                                    </h3>
                                    <span class="text-muted" style="font-size: 12px">Etkinlik Sayısı</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4" id="googleCard">
                    <div class="col-12 col-md-6 mb-3">
                        <h5 class="text-center">Cihaz Türlerine Göre Görüntüleme Sayısı</h5>
                        <div id="deviceCategoryChart"></div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <h5 class="text-center">Şehirlere Göre Görüntüleme Sayısı</h5>
                        <div id="cityChart"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-secondary" id="close-model" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        let deviceChart, cityChart;

        $(".openModal").click(function () {
            $("#myModal").modal("show");
            let url = $(this).data('url');
            let title = $(this).data('title');

            $("#modal-title").text(title);


            // Önce mevcut verileri temizleyip spinner'ları göster
            $(".card-title").html('<span class="loading-spinner spinner-border text-info"></span>');
            $("#deviceCategoryChart").html('<div class="text-center d-flex align-items-center justify-content-center" style="height:300px"  ><span class="spinner-border text-info"></span></div>');
            $("#cityChart").html('<div class="text-center d-flex align-items-center justify-content-center" style="height:300px"><span class="spinner-border text-info"></span></div>');

            $.ajax({
                url: "/secure/post-stats?url=" + encodeURIComponent(url),
                method: "GET",
                dataType: "json",
                success: function (data) {
                    console.log(data)
                    if (data[0]?.rows?.length > 0) {
                        const row = data[0].rows[0]; // İlk satırı alıyoruz
                        $("#statsViews").text(row.metricValues[0].value || "0");
                        $("#statsActiveUsers").text(row.metricValues[1].value || "0");
                        $("#statsEventCount").text(row.metricValues[2].value || "0");
                    } else {
                        $("#statsViews").text("0");
                        $("#statsActiveUsers").text("0");
                        $("#statsEventCount").text("0");
                    }


                    if (data[1].length > 0) {
                        let deviceLabels = [];
                        let deviceViews = [];
                        let label;
                        data[1].forEach(item => {
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
                            deviceViews.push(parseInt(item.views));

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

                    if (data[2].length > 0) {

                        // Grafik için hazırlanan veri
                        const chartData = {
                            labels: data[2].map(item => item.city === "(not set)" ? "Bilinmiyor" : item.city),
                            series: data[2].map(item => parseInt(item.screenPageViews))
                        };

                        if (cityChart) cityChart.destroy();

                        $("#cityChart").empty();
                        // Grafik seçenekleri
                        const options = {
                            chart: {
                                type: 'bar',
                                height: 350,
                                toolbar: {
                                    show: false
                                }
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: true, // Yatay bar chart
                                    columnWidth: '25%',

                                },
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                show: true,
                                width: 2,
                                colors: ['transparent']
                            },
                            xaxis: {
                                categories: chartData.labels,
                            },
                            yaxis: {},
                            fill: {
                                opacity: 1
                            },
                            tooltip: {
                                y: {
                                    formatter: function (val) {
                                        return val + " Görüntüleme Sayısı";
                                    }
                                }
                            }
                        };

                        // Grafik oluşturma
                        cityChart = new ApexCharts(document.querySelector("#cityChart"), {
                            series: [{
                                name: 'Görüntüleme Sayısı',
                                data: chartData.series
                            }],
                            ...options
                        });

                        cityChart.render();

                    } else {
                        $("#cityChart").html('<p class="text-danger text-center d-flex align-items-center justify-content-center" style="height: 100%">Veri yok!</p>');
                    }


                },
                error: function (xhr, status, error) {
                    let errorMessage = xhr.responseJSON?.error || xhr.statusText;
                    let settingsUrl = "{{ route('settings') }}";
                    let googleConnectUrl = "{{ route('google.connect') }}";

                    // Oturum sona erme veya token ile ilgili hatalar
                    if (xhr.responseJSON?.error === "invalid json token") {
                        $("#modalBody").html(`
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
                                            <h5 class="card-title"><i class="ti-stats-up text-primary me-2"></i> İstatistiklere Erişin</h5>
                                            <p>Google Analytics ile haber istatistiklerinizi görüntüleyebilirsiniz:</p>
                                            <ul>
                                                <li class="mb-2">Haberin görüntülenme sayısı</li>
                                                <li class="mb-2">Ziyaretçi türleri ve cihaz bilgileri</li>
                                                <li class="mb-2">Şehirlere göre okuyucu analizi</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <div class="card bg-light border-0">
                                        <div class="card-body text-center">
                                            <img src="{{ asset('backend/assets/analytics-icon.svg') }}" alt="Analytics" width="100" class="mb-3">
                                            <h5>Google Analytics Entegrasyonu</h5>
                                            <p class="mb-0 text-muted">Haber performansınızı analiz edin</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                    // Mülk ID bulunamadı hataları
                    else if (errorMessage.includes("404") || errorMessage.includes("Not Found")) {
                        $("#modalBody").html(`
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
                            let errorJSON = JSON.parse(errorMessage)?.error;

                            // Kullanıcı doğrulama hataları
                            if (errorJSON?.status === "UNAUTHENTICATED") {
                                $("#modalBody").html(`
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
                                $("#modalBody").html(`
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
                                `);
                            }
                            // Mülk ID sorunları
                            else if (errorJSON?.message && errorJSON.message.includes("property")) {
                                $("#modalBody").html(`
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
                                `);
                            }
                            // Genel hatalar
                            else {
                                $("#modalBody").html(`
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
                            $("#modalBody").html(`
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
                                            <h5 class="card-title"><i class="ti-stats-up text-primary me-2"></i> İstatistiklere Erişin</h5>
                                            <p>Google Analytics ile haber istatistiklerinizi görüntüleyebilirsiniz:</p>
                                            <ul>
                                                <li class="mb-2">Haberin görüntülenme sayısı</li>
                                                <li class="mb-2">Ziyaretçi türleri ve cihaz bilgileri</li>
                                                <li class="mb-2">Şehirlere göre okuyucu analizi</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <div class="card bg-light border-0">
                                        <div class="card-body text-center">
                                            <img src="{{ asset('backend/assets/analytics-icon.svg') }}" alt="Analytics" width="100" class="mb-3">
                                            <h5>Google Analytics Entegrasyonu</h5>
                                            <p class="mb-0 text-muted">Haber performansınızı analiz edin</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `);
                        }
                    }
                }
            });
        });

        $("#close-model").click(function () {
            $(".card-title").html('<span class="loading-spinner spinner-border text-info"></span>');
            $("#deviceCategoryChart").html('<div class="text-center d-flex align-items-center justify-content-center" style="height: 300px" ><span class="spinner-border text-info"></span></div>');
            $("#cityChart").html('<div class="text-center d-flex align-items-center justify-content-center" style="height:300px"><span class="spinner-border text-info"></span></div>');
            if (deviceChart) deviceChart.destroy();
            if (cityChart) cityChart.destroy();
        });
    });
</script>