<!-- Video Popup Modal -->
@php
    $currentRoute = Route::currentRouteName(); // örnek: 'home'
    $showRoutes = ['post.create', 'post.edit'];
@endphp
<div id="videoModal" class="modal" data-current-route="{{ $currentRoute }}">
    <div class="modal-contents">
        <span class="close-btn">&times;</span>
        <div class="video-controls">
            <button id="playPauseBtn" class="control-btn">
                <i data-feather="play" class="control-btn-icon"></i>
            </button>
            <button id="muteBtn" class="control-btn">
                <i data-feather="volume-2" class="control-btn-icon"></i>
            </button>
        </div>
        <video id="videoFrame" style="height: 100%;border-radius: 10px;display: none;">
            <source id="videoSource" src="{{ asset('video.mp4') }}" type="video/mp4">
            Tarayıcınız video etiketini desteklemiyor.
        </video>
        <div class="video-image-container">
            <img src="{{ asset('/robot_big.png') }}" alt="Asistan" style="height: 100%; border-radius: 10px;">
        </div>
        <div class="command-input-container">
            <input class="command-input" type="text" id="commandInput" placeholder="Size nasıl yardımcı olabilirim?"
                autofocus>
            <i data-feather="mic" class="input-mic-icon"></i>
            <button class="command-input-btn">Tekrar Dene (3)</button>
        </div>
    </div>

</div>

<!-- Sabit Video Butonu -->
<button id="fixedVideoBtn" class="fixed-video-btn">
    <div class="video-btn-content">
        <img src="{{ asset('/robot.png') }}" alt="Tanıtımı Aç">
    </div>
    <p class="video-btn-text">Tanıtımı Aç</p>
</button>

<style>
    .modal {
        /* display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%; */
        background-color: rgba(0, 0, 0, 0.9);
        /* opacity: 0;
        transition: opacity 0.3s ease; */
    }

    .modal.fade-in {
        opacity: 1;
    }

    .modal-contents {
        position: relative;
        margin: auto;
        /* padding: 20px; */
        width: fit-content;
        height: 80%;
        overflow: hidden;
        top: 50%;
        transform: translateY(-50%);
        border-radius: 10px;
    }



    .close-btn {
        position: absolute;
        color: #999999;
        top: 0px;
        right: 15px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        z-index: 15;
    }

    .video-container {
        position: relative;
        /* padding-bottom: 56.25%;
        height: 0; */
        overflow: hidden;
    }

    .video-btn-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 70px;

        transition: all 0.3s ease;
    }

    .video-btn-content img {
        transition: all 0.3s ease;
    }

    .fixed-video-btn {
        position: fixed;
        bottom: 10px;
        right: 4px;
        background-color: transparent;
        color: white;
        border: none;
        cursor: pointer;
        z-index: 999;
        /* box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); */
    }

    .video-btn-text {
        display: none;
        font-size: 14px;
        font-weight: bold;
        color: white;
        background-color: #0158b5;
    }

    .fixed-video-btn:hover {}

    .fixed-video-btn:hover .video-btn-content img {
        filter: drop-shadow(0px 0px 8px rgba(1, 88, 181, 0.8));
        transform: scale(1.05);
    }

    .video-image-container {
        width: auto;
        height: 100%;
        overflow: hidden;
        display: none;
        /* Varsayılan olarak gizli */
    }

    .video-controls {
        position: absolute;
        top: 13px;
        left: 10px;
        z-index: 15;
        display: flex;
        gap: 10px;
    }

    .control-btn {
        background-color: transparent;
        border: none;
        cursor: pointer;
        color: #999999;
    }

    .control-btn-icon {
        width: 22px;
    }

    .command-input-container {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 30px 15px;
        background: linear-gradient(0deg, #1c1c1c, transparent);
        border-radius: 10px;
        /* box-shadow: 0 0 3px 5px #00000052; */
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
    }

    .command-input-container.show {
        opacity: 1;
        transform: translateY(0);
    }

    .command-input {
        width: 100%;
        background: #e9e9e9;
        outline: 0;
        border: 1px solid #909090;
        padding: 10px 15px;
        border-radius: 5px;
        font-size: 12px;
        color: #454545;
    }

    .command-input-btn {
        position: absolute;
        bottom: 2px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #003166;
        color: white;
        border: none;
        cursor: pointer;
        padding: 3px 5px;
        border-radius: 5px;
        font-size: 12px;
        color: #fff;
        opacity: 0;
        transform: translateX(-50%) translateY(20px);
        transition: all 0.3s ease;
    }

    .command-input-btn:disabled {
        transform: translateX(-50%) translateY(0);
        cursor: default;
        opacity: 0.7;
    }

    .command-input-btn.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    .input-mic-icon {
        position: absolute;
        right: 25px;
        top: 37px;
        color: #999999;
        width: 18px;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .input-mic-icon.listening {
        color: #ff4444;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Route kontrolü
        const currentRoute = document.getElementById('videoModal').dataset.currentRoute;
        const showRoutes = ['post.create', 'post.edit'];
        const isSpecialRoute = showRoutes.includes(currentRoute);

        // iOS cihaz kontrolü
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        const videoFrame = document.getElementById('videoFrame');
        const videoSource = document.getElementById('videoSource');

        if (isIOS && videoFrame) {
            videoFrame.setAttribute('playsinline', '');
            videoFrame.setAttribute('muted', '');
            videoFrame.setAttribute('controls', '');
        }

        const modal = document.getElementById('videoModal');
        const closeBtn = document.querySelector('.close-btn');
        const fixedBtn = document.getElementById('fixedVideoBtn');
        const playPauseBtn = document.getElementById('playPauseBtn');
        const muteBtn = document.getElementById('muteBtn');
        const commandContainer = document.querySelector('.command-input-container');
        const videoControls = document.querySelector('.video-controls');
        const videoImageContainer = document.querySelector('.video-image-container');
        let isPlaying = true;
        let isMuted = false;
        const isFirstVisit = !sessionStorage.getItem('videoShown');

        // Feather ikonlarını başlat
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        function updateIcon(button, iconName) {
            if (button) {
                button.innerHTML = '';
                const newIcon = document.createElement('i');
                newIcon.setAttribute('data-feather', iconName);
                newIcon.className = 'control-btn-icon';
                button.appendChild(newIcon);
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }
        }

        // İlk ziyaret kontrolü ve görünürlük ayarları
        if (isSpecialRoute) {
            // Özel route'larda her zaman video görünürlüğünü ayarla
            if (commandContainer) commandContainer.style.display = 'none';
            if (videoFrame) videoFrame.style.display = 'block';
            if (videoControls) videoControls.style.display = 'flex';
            if (videoImageContainer) videoImageContainer.style.display = 'none';

            // Video kaynağını değiştir
            if (videoSource) {
                videoSource.src = "{{ asset('video2.mp4') }}";
                if (videoFrame) videoFrame.load();
            }
        } else if (isFirstVisit) {
            // Diğer routelarda ilk ziyarette video göster
            if (commandContainer) commandContainer.style.display = 'none';
            if (videoFrame) videoFrame.style.display = 'block';
            if (videoControls) videoControls.style.display = 'flex';
            if (videoImageContainer) videoImageContainer.style.display = 'none';
        } else {
            // Diğer routelarda ilk ziyaret değilse resim göster
            if (videoFrame) videoFrame.style.display = 'none';
            if (videoControls) videoControls.style.display = 'none';
            if (videoImageContainer) videoImageContainer.style.display = 'block';
        }

        // Play/Pause kontrolü
        if (playPauseBtn) {
            playPauseBtn.addEventListener('click', function () {
                if (isPlaying) {
                    videoFrame.pause();
                    updateIcon(playPauseBtn, 'pause');
                } else {
                    videoFrame.play();
                    updateIcon(playPauseBtn, 'play');
                }
                isPlaying = !isPlaying;
            });
        }

        // Ses kontrolü
        if (muteBtn) {
            muteBtn.addEventListener('click', function () {
                if (isMuted) {
                    videoFrame.muted = false;
                    updateIcon(muteBtn, 'volume-2');
                } else {
                    videoFrame.muted = true;
                    updateIcon(muteBtn, 'volume-x');
                }
                isMuted = !isMuted;
            });
        }

        // Session kontrolü
        if (isFirstVisit) {
            showVideo(false); // false = sessiz başlatma
            sessionStorage.setItem('videoShown', 'true');
        }

        if (fixedBtn) {
            fixedBtn.addEventListener('click', function () {
                showVideo(true); // true = sesli başlatma
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', hideVideo);
        }

        if (modal) {
            window.addEventListener('click', function (e) {
                if (e.target == modal) {
                    hideVideo();
                }
            });
        }

        function showVideo(shouldBeMuted) {
            if (modal) {
                modal.style.display = 'block';
                requestAnimationFrame(() => {
                    modal.classList.add('fade-in');
                });
            }

            // Route kontrolü
            const currentRoute = document.getElementById('videoModal').dataset.currentRoute;
            const showRoutes = ['post.create', 'post.edit'];
            const isSpecialRoute = showRoutes.includes(currentRoute);

            // Koşul 1: Özel route'lar için her zaman video2.mp4 göster
            if (isSpecialRoute) {
                if (videoSource) {
                    videoSource.src = "{{ asset('video2.mp4') }}";
                    if (videoFrame) {
                        videoFrame.load();
                        videoFrame.style.display = 'block';
                        videoFrame.play();
                        videoFrame.muted = shouldBeMuted;
                        isMuted = shouldBeMuted;
                    }
                }
                if (videoControls) {
                    videoControls.style.display = 'flex';
                }
                if (videoImageContainer) {
                    videoImageContainer.style.display = 'none';
                }
            }
            // Koşul 2: Diğer route'lar için ilk ziyarette video.mp4 göster
            else if (isFirstVisit) {
                if (videoSource) {
                    videoSource.src = "{{ asset('video.mp4') }}";
                    if (videoFrame) {
                        videoFrame.load();
                        videoFrame.style.display = 'block';
                        videoFrame.play();
                        videoFrame.muted = shouldBeMuted;
                        isMuted = shouldBeMuted;
                    }
                }
                if (videoControls) {
                    videoControls.style.display = 'flex';
                }
                if (videoImageContainer) {
                    videoImageContainer.style.display = 'none';
                }
            }
            // Koşul 3: Diğer route'lar için ilk ziyaret değilse sadece resim göster
            else {
                if (videoFrame) {
                    videoFrame.style.display = 'none';
                }
                if (videoControls) {
                    videoControls.style.display = 'none';
                }
                if (videoImageContainer) {
                    videoImageContainer.style.display = 'block';
                }

                // Command input container'ı göster
                if (commandContainer) {
                    commandContainer.style.display = 'block';
                    setTimeout(() => {
                        commandContainer.classList.add('show');
                    }, 1000);
                }
            }

            if (muteBtn) {
                updateIcon(muteBtn, shouldBeMuted ? 'volume-x' : 'volume-2');
            }

            isPlaying = true;
            if (playPauseBtn) {
                updateIcon(playPauseBtn, 'play');
            }
        }

        function hideVideo() {
            if (modal) {
                modal.classList.remove('fade-in');
                setTimeout(() => {
                    modal.style.display = 'none';
                    resetSystem(); // Modal tamamen kapandıktan sonra sistemi sıfırla
                }, 300);
            }
            if (videoFrame) {
                videoFrame.pause();
                videoFrame.currentTime = 0;
            }
            isPlaying = false;
            updateIcon(playPauseBtn, 'pause');

            // Video kapatıldığında session'ı güncelle
            if (isFirstVisit) {
                location.reload();
            }
        }

        // Sistemi sıfırlama fonksiyonu
        function resetSystem() {
            // Zamanlayıcıları temizle
            clearTimeout(inputTimeout);
            clearInterval(countdownInterval);

            // Input'u temizle
            if (commandInput) {
                commandInput.value = '';
            }

            // Butonu sıfırla
            if (retryButton) {
                retryButton.classList.remove('show');
                retryButton.disabled = false;
                countdownValue = 3;
                retryButton.textContent = `Tekrar Dene (${countdownValue})`;
            }

            // Input container'ı sıfırla
            if (commandContainer) {
                commandContainer.classList.remove('show');
            }

            // Route ve ziyaret durumuna göre görünürlükleri ayarla
            const currentRoute = document.getElementById('videoModal').dataset.currentRoute;
            const showRoutes = ['post.create', 'post.edit'];
            const isSpecialRoute = showRoutes.includes(currentRoute);

            if (isSpecialRoute) {
                // Özel routelarda video ayarları
                if (videoFrame) videoFrame.style.display = 'block';
                if (videoControls) videoControls.style.display = 'flex';
                if (videoImageContainer) videoImageContainer.style.display = 'none';
            } else if (!isFirstVisit) {
                // Diğer routelarda ilk ziyaret değilse resim göster
                if (videoFrame) videoFrame.style.display = 'none';
                if (videoControls) videoControls.style.display = 'none';
                if (videoImageContainer) videoImageContainer.style.display = 'block';
            }
        }

        // Mikrofon için değişkenler
        const micIcon = document.querySelector('.input-mic-icon');
        const commandInput = document.getElementById('commandInput');
        let recognition = null;

        // Input ve buton için değişkenler
        const retryButton = document.querySelector('.command-input-btn');
        let inputTimeout = null;
        let countdownInterval = null;
        let countdownValue = 3;

        // Buton sistemini başlat
        function startButtonSystem() {
            // Önceki zamanlayıcıları temizle
            clearTimeout(inputTimeout);
            clearInterval(countdownInterval);

            if (retryButton) {
                retryButton.disabled = false;
                retryButton.classList.remove('show');
                countdownValue = 3;
                retryButton.textContent = `Tekrar Dene (${countdownValue})`;
            }

            // 1.5 saniye sonra butonu göster
            inputTimeout = setTimeout(() => {
                if (retryButton) {
                    retryButton.classList.add('show');

                    // Geri sayımı başlat
                    countdownInterval = setInterval(() => {
                        countdownValue--;
                        retryButton.textContent = `Tekrar Dene (${countdownValue})`;

                        if (countdownValue <= 0) {
                            clearInterval(countdownInterval);
                            console.log('Yönlendiriliyor...');
                            retryButton.disabled = true;
                            retryButton.classList.remove('show');
                            retryButton.textContent = 'Yönlendiriliyor...';
                            // Komuttan hedef route'u çözümle
                            const commandText = commandInput.value.trim();
                            const targetRoute = resolveCommandToRoute(commandText);

                            setTimeout(() => {
                                if (targetRoute) {
                                    window.location.href = targetRoute;
                                } else {
                                    alert("Gidilecek sayfa bulunamadı. Lütfen geçerli bir komut girin.");
                                }
                            }, 1000);
                        }
                    }, 1000);
                }
            }, 1500);
        }

        // Input değişikliğini dinle
        if (commandInput) {
            commandInput.addEventListener('input', function () {
                if (this.value.trim() !== '') {
                    startButtonSystem();
                } else {
                    clearTimeout(inputTimeout);
                    clearInterval(countdownInterval);
                    if (retryButton) {
                        retryButton.classList.remove('show');
                    }
                }
            });
        }

        // Mikrofon sonucu için
        if ('webkitSpeechRecognition' in window) {
            recognition = new webkitSpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'tr-TR';

            recognition.onstart = function () {
                micIcon.classList.add('listening');
            };

            recognition.onend = function () {
                micIcon.classList.remove('listening');
            };

            recognition.onresult = function (event) {
                const transcript = event.results[0][0].transcript;
                commandInput.value = transcript;
                // Mikrofon sonucu geldiğinde buton sistemini başlat
                startButtonSystem();
            };

            recognition.onerror = function (event) {
                console.error('Ses tanıma hatası:', event.error);
                micIcon.classList.remove('listening');
            };
        }

        // Mikrofon ikonuna tıklama olayı
        if (micIcon) {
            micIcon.addEventListener('click', function () {

                if (recognition) {
                    if (micIcon.classList.contains('listening')) {
                        recognition.stop();
                    } else {
                        recognition.start();
                    }
                } else {
                    alert('Tarayıcınız ses tanıma özelliğini desteklemiyor.');
                }
            });
        }

        // Butona tıklama olayı
        if (retryButton) {
            retryButton.addEventListener('click', function () {
                if (!this.disabled) {
                    resetSystem();
                }
            });
        }

        function resolveCommandToRoute(commandText) {
            const lowerInput = commandText.toLowerCase();

            for (let route of commandRoutes) {
                for (let keyword of route.keywords) {
                    if (lowerInput.includes(keyword)) {
                        return route.url;
                    }
                }
            }

            return null; // Eşleşme yoksa null döndür
        }
    });

    const commandRoutes = [
        // Ana Sayfa
        {
            keywords: [
                "ana sayfa", "anasayfa", "siteye dön", "giriş ekranı", "dashboard", "yönetim paneli",
                "panel anasayfası", "kontrol paneli"
            ], url: '/secure/'
        },

        // Haber/Post
        {
            keywords: [
                "haber ekle", "yeni haber", "post oluştur", "yeni içerik", "haber gir", "içerik ekle",
                "haber yaz", "yeni post"
            ], url: '/secure/post/create'
        },
        {
            keywords: [
                "haberler", "tüm haberler", "içerik listesi", "haber listesi", "haberleri gör", "haber yönetimi",
                "postlar", "yazılar"
            ], url: '/secure/post'
        },

        // Kategori
        {
            keywords: [
                "kategori ekle", "yeni kategori", "kategori oluştur", "kategori gir", "kategori tanımla",
                "kategori yaz", "kategori giriş", "yeni kategori gir"
            ], url: '/secure/category/create'
        },
        {
            keywords: [
                "kategoriler", "kategori listesi", "kategori yönetimi", "tüm kategoriler", "kategori düzenle",
                "kategori işlemleri", "kategori bölümü", "kategori sayfası"
            ], url: '/secure/category'
        },

        // Kullanıcı
        {
            keywords: [
                "kullanıcı ekle", "yeni kullanıcı", "kullanıcı oluştur", "admin ekle", "editör ekle",
                "yeni kişi ekle", "üyelik oluştur", "kullanıcı kaydı"
            ], url: '/secure/users/create'
        },
        {
            keywords: [
                "kullanıcılar", "tüm kullanıcılar", "admin listesi", "üyeler", "kullanıcı yönetimi",
                "kullanıcı listesi", "kullanıcı işlemleri", "personel"
            ], url: '/secure/users'
        },

        // Yorum
        {
            keywords: [
                "yorumlar", "yorumlar listesi", "yorum kontrol", "yorum düzenle", "yorum işlemleri",
                "yorum yönetimi", "yorum ekranı", "gelen yorumlar"
            ], url: '/secure/comment'
        },

        // Video
        {
            keywords: [
                "video ekle", "yeni video", "video yükle", "tanıtım videosu", "video oluştur",
                "videolu içerik", "video giriş", "video paylaş"
            ], url: '/secure/video/create'
        },
        {
            keywords: [
                "videolar", "video listesi", "video galerisi", "video yönetimi", "video arşivi",
                "mevcut videolar", "video kontrol", "videolar sayfası"
            ], url: '/secure/video'
        },

        // Foto Galeri
        {
            keywords: [
                "galeri", "foto galeri", "fotoğraf galerisi", "resim yükle", "fotoğraf paylaş",
                "galeriye ekle", "resim ekle", "fotoğraf oluştur"
            ], url: '/secure/image-gallery'
        },

        // Sayfa
        {
            keywords: [
                "sayfa oluştur", "yeni sayfa", "sayfa ekle", "statik sayfa", "bilgi sayfası",
                "yeni içerik sayfası", "sayfa girişi", "sayfa yap"
            ], url: '/secure/page/create'
        },

        // Makale
        {
            keywords: [
                "makale ekle", "yeni makale", "makale oluştur", "article gir", "yazı yaz",
                "analiz yazısı", "içerik oluştur", "makale yaz"
            ], url: '/secure/article/create'
        },

        // Biyografi
        {
            keywords: [
                "biyografi oluştur", "biyografi ekle", "yeni biyografi", "kişi bilgisi", "hayat hikayesi",
                "biyografi giriş", "biyografi yazısı", "biyografi paylaş"
            ], url: '/secure/biography/create'
        },

        // E-Gazete
        {
            keywords: [
                "e-gazete", "gazete yükle", "gazete oluştur", "pdf gazete", "e gazete paylaş",
                "e-gazete ekle", "gazete arşivi", "sanat gazetesi"
            ], url: '/secure/enewspaper'
        },

        // Ayarlar
        {
            keywords: [
                "ayarlar", "site ayarları", "ayar ekranı", "genel ayarlar", "sistem ayarları",
                "yönetim ayarları", "ayar güncelle", "konfigürasyon"
            ], url: '/secure/settings'
        },

        // Gmail
        {
            keywords: [
                "gmail", "google mail", "posta kutusu", "gmail sayfası", "google mesaj",
                "google mail ayarı", "gmail post", "mail listesi"
            ], url: '/secure/gmail'
        },

        // Google Analytic
        {
            keywords: [
                "analitik", "istatistikler", "analytics", "sayfa analizi", "trafiği görüntüle",
                "analytic dashboard", "sayfa istatistikleri", "google analytics"
            ], url: '/secure/google-analytic'
        },

        // Menü
        {
            keywords: [
                "menüler", "menu işlemleri", "menu listesi", "yeni menü", "menu düzenle",
                "menü kontrol", "site menüsü", "navigasyon"
            ], url: '/secure/menus'
        },

        // Ajanslar
        {
            keywords: [
                "ajans", "ajans haberleri", "iha", "iha haber", "ajans bağlantısı", "ajans ekle",
                "ajans içeriği", "ajans modülü"
            ], url: '/secure/iha'
        },

        // JSON/Optimize
        {
            keywords: [
                "json sistemi", "json oluştur", "json dosya", "json verisi", "json generator",
                "json işlemi", "json güncelle", "sistem json"
            ], url: '/secure/jsonsystemcreate'
        },
        {
            keywords: [
                "optimize", "önbellek temizle", "cache temizle", "sistemi temizle", "optimize işlemi",
                "optimizasyon", "sistem hızlandır", "önbellek sıfırla"
            ], url: '/secure/optimize'
        },

        // Reklam
        {
            keywords: [
                "reklam", "reklam düzenle", "reklam yönetimi", "reklam paneli", "banner ayarları",
                "reklam listesi", "sponsor alanı", "ads bölümü"
            ], url: '/secure/ads'
        }
    ];


</script>