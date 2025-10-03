@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Üye Ekle</h4>
                    </div>
                    <!-- /.box-header -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Hata!</strong> Aşağıdaki hataları düzeltin.<br><br>
                            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
                        </div>
                    @endif

                    <form class="form" action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Ad Soyad</label>
                                        <input type="text" class="form-control" placeholder="Ad Soyad" name="name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">E posta</label>
                                        <input type="text" class="form-control" placeholder="Email" name="email">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Şifre</label>
                                        <div class="input-group">
                                            <input type="password" id="main-password-input" class="form-control"
                                                placeholder="Şifre" name="password">
                                            <span class="input-group-text" id="toggle-password" style="cursor: pointer;">
                                                <i data-feather="eye-off"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Durumu</label>
                                        <div class="form-group">
                                            <select class="form-control select2" style="width: 100%;" name="status">
                                                <option value="0" selected="selected">Standart Üye</option>
                                                <option value="1">Yönetici</option>
                                                <option value="2">Editör</option>
                                                <option value="3">Köşe Yazarı</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" id="password-input" class="form-control"
                                            placeholder="Güçlü Şifre" readonly
                                            style="background-color: #f8f9fa; cursor: not-allowed;">
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <button type="button" id="generate-password" class="btn btn-primary py-1 px-2 me-2"
                                        style="margin-top:1px;background: linear-gradient(to right, #4776E6, #8E54E9); border: none;">Güçlü
                                        Şifre Oluştur</button>
                                    <button type="button" id="use-password" class="btn btn-success py-1 d-none"
                                        style="box-shadow: 0 2px 5px rgba(0,0,0,0.2);border:0">Kullan</button>
                                </div>


                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div id="password-strength" class="d-none">
                                        <div class="progress" style="height: 8px;margin-bottom: 4px;">
                                            <div id="password-progress" class="progress-bar bg-success" role="progressbar"
                                                style="width: 100%;" aria-valuenow="100" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                        <small id="password-strength-text" class="form-text text-muted mt-1">Mükemmel şifre!
                                            (10+ karakter, büyük ve küçük harfler, sayılar ve özel karakterler)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Telefon</label>
                                        <input type="text" class="form-control" placeholder="Telefon" name="phone">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label d-block">Üye Avatarı</label>
                                        <label class="file">
                                            <input type="file" id="file" name="avatar">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Üye Hakkında</label>
                                <textarea rows="5" class="form-control" placeholder="Kişi öz bilgi" name="about"></textarea>
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


@section('custom_js')
    <script src="{{ asset('backend/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
    <script src="{{ asset('backend/js/pages/advanced-form-element.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password-input');
            const mainPasswordInput = document.getElementById('main-password-input');
            const generateBtn = document.getElementById('generate-password');
            const useBtn = document.getElementById('use-password');
            const passwordStrength = document.getElementById('password-strength');
            const passwordProgress = document.getElementById('password-progress');
            const passwordStrengthText = document.getElementById('password-strength-text');
            const togglePassword = document.getElementById('toggle-password');
            // const toggleGeneratedPassword = document.getElementById('toggle-generated-password');
            let generatedPassword = '';

            // Feather Icons yüklendiğinde çalıştır
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Şifre göster/gizle fonksiyonu
            function togglePasswordVisibility(inputElement, toggleElement) {
                const type = inputElement.getAttribute('type') === 'password' ? 'text' : 'password';
                inputElement.setAttribute('type', type);

                // İkonu değiştir
                const icon = toggleElement.querySelector('i');
                if (type === 'password') {
                    toggleElement.innerHTML = '<i data-feather="eye-off"></i>';
                } else {
                    toggleElement.innerHTML = '<i data-feather="eye"></i>';
                }

                // Feather Icons'u yeniden çalıştır
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }

            // Ana şifre göster/gizle butonu tıklama olayı
            if (togglePassword) {
                togglePassword.addEventListener('click', function () {
                    togglePasswordVisibility(mainPasswordInput, togglePassword);
                });
            }

            // Oluşturulan şifre göster/gizle butonu tıklama olayı
            // if (toggleGeneratedPassword) {
            //    toggleGeneratedPassword.addEventListener('click', function() {
            //        togglePasswordVisibility(passwordInput, toggleGeneratedPassword);
            //    });
            // }

            // Güçlü şifre oluşturma fonksiyonu
            function generateStrongPassword() {
                const length = 12; // Minimum 10 karakter, güvenlik için 12 yaptım
                const uppercaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                const lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';
                const numberChars = '0123456789';
                const specialChars = '!@#$%^&*_+-:;,.';

                const allChars = uppercaseChars + lowercaseChars + numberChars + specialChars;
                let password = '';

                // En az bir tane her türden karakter ekleyelim
                password += uppercaseChars.charAt(Math.floor(Math.random() * uppercaseChars.length));
                password += lowercaseChars.charAt(Math.floor(Math.random() * lowercaseChars.length));
                password += numberChars.charAt(Math.floor(Math.random() * numberChars.length));
                password += specialChars.charAt(Math.floor(Math.random() * specialChars.length));

                // Kalan karakterleri rastgele ekleyelim
                for (let i = 4; i < length; i++) {
                    password += allChars.charAt(Math.floor(Math.random() * allChars.length));
                }

                // Şifreyi karıştıralım
                return password.split('').sort(() => 0.5 - Math.random()).join('');
            }

            // Şifre oluşturma butonu tıklama olayı
            generateBtn.addEventListener('click', function () {
                // Yeni şifre oluştur
                generatedPassword = generateStrongPassword();

                // Şifreyi input alanına yaz (text olarak göster)
                passwordInput.value = generatedPassword;

                // Buton metnini ve görünürlükleri güncelle
                if (generateBtn.textContent !== 'Tekrar') {
                    generateBtn.textContent = 'Tekrar';
                    generateBtn.style.background = 'linear-gradient(to right, #3494E6, #EC6EAD)';
                }

                // Kullan butonunu göster
                useBtn.classList.remove('d-none');

                // Şifre gücü göstergesini göster
                passwordStrength.classList.remove('d-none');

                // Değişen şifre görsel efekti
                passwordInput.style.animation = 'pulse 0.5s';
                setTimeout(() => {
                    passwordInput.style.animation = '';
                }, 500);
            });

            // Kullan butonu tıklama olayı
            useBtn.addEventListener('click', function () {
                // Form içindeki gerçek şifre alanına değeri atayalım
                if (mainPasswordInput) {
                    mainPasswordInput.value = generatedPassword;

                    // Ana şifre alanını göster (opsiyonel)
                    mainPasswordInput.type = 'text';

                    // Ana şifre alanı göz ikonunu güncelle
                    if (togglePassword) {
                        togglePassword.innerHTML = '<i data-feather="eye"></i>';
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    }

                    // Ana şifre alanına odaklan ve seç
                    mainPasswordInput.focus();
                    mainPasswordInput.select();
                }

                // Kullanıldı mesajı göster
                const originalText = useBtn.textContent;
                useBtn.textContent = 'Kullanıldı';
                useBtn.classList.add('bg-success');

                setTimeout(() => {
                    useBtn.textContent = originalText;
                    useBtn.classList.remove('bg-success');
                }, 3000);
            });
        });
    </script>

    <style>
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
                background-color: #e8f0fe;
            }

            100% {
                transform: scale(1);
            }
        }

        #password-input:read-only {
            background-color: #f8f9fa;
            border-color: #ced4da;
        }

        #generate-password:hover,
        #use-password:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .btn {
            transition: all 0.3s ease;
        }

        #password-strength {
            transition: all 0.3s ease;
        }
    </style>
@endsection