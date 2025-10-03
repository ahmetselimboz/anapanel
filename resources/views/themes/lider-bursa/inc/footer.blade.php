
@if($routename!="frontend.index")
    @if(\Illuminate\Support\Facades\Storage::disk('public')->exists('main/sortable_list.json'))
        @foreach(\Illuminate\Support\Facades\Storage::disk('public')->json('main/sortable_list.json') as $block)
            @if($block["type"]=="menu" and ($block["design"]==1 or $block["design"]==2) )
                @include($theme_path.'.main.block_main_menu', [ 'menu_id' => $block["menu"], 'design' => $block["design"] ])
            @endif
        @endforeach
    @endif
@endif


<!-- Arama ve kayıt sayfaları burada -->
<div class="search-box" id="search-box">
    <div class="search-block bg-white mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="position-relative">
                        <div class="search-container">
                            <form action="{{ route('search.get') }}" method="get" id="search-form">
                                <div class="search-icon-left"><i class="icon-search-bx"></i></div>
                                <input name="search" type="text" id="search" class="search-input-text text-truncate"
                                       placeholder="Haber başlığı , Etiket  ve ya İd ile arama yapabilirsiniz"
                                       value="{{ request('search') }}">
                            </form>
                            <div class="close-btn" id="search-close"><i class="icon-close"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="sign-modal" id="signInModal">
    <div class="sign-close-button"><i class="modal-close"></i></div>

    <div class="position-absolute top-50 start-50 translate-middle">
        <div class="container sign-container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center"><img src="{{ asset('uploads/'.$settings['logo']) }}" alt="{{ $settings['title'] }}" class="brand-logo m-0 p-0" height="40"></div>
                </div>
                <div class="col-12 mb-sign"><div class="sign-modal-caption d-flex justify-content-center">ÜYE VE KÖŞE YAZARI GİRİŞİ</div></div>
                <div class="col-12">
                    <div class="alert alert-success loginsuccess d-none">GİRİŞ BAŞARILI YÖNLENDİRİLİYOR</div>
                    <div class="alert alert-danger loginerror d-none">GİRİŞ BAŞARISIZ ! </div>
                    <form id="userlogin" method="post">
                        @csrf
                        <div class=" mb-4"><input type="email" name="usermail" class="form-control sign-input" aria-describedby="E-posta" placeholder="E-posta ya da kullanıcı adresiniz"></div>
                        <div class="mb-4"><input type="password" name="userpassword" class="form-control sign-input" placeholder="Şifreniz"></div>
                        <input type="submit" value="Oturum aç" class="btn btn-primary w-100 sign-button mb-3" />
                    </form>
                </div>
                <div class="col-12"><div class="sign-footer"><div class="sign-link" id="signUpBtn">Şimdi üye ol</div></div></div>
            </div>
        </div>
    </div>
</div>

<div class="sign-modal" id="signUpModal">
    <div class="sign-close-button">
        <i class="modal-close"></i>
    </div>

    <div class="position-absolute top-50 start-50 translate-middle">
        <div class="container sign-container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('uploads/'.$settings['logo']) }}" alt="{{ $settings['title'] }}" class="brand-logo m-0 p-0" height="40">
                    </div>
                </div>
                <div class="col-12 mb-sign"><div class="sign-modal-caption d-flex justify-content-center">Yeni hesap oluşturun</div></div>

                <div class="col-12">
                    <div class="alert alert-success loginsuccessreg d-none">KAYIT BAŞARILI YÖNLENDİRİLİYOR</div>
                    <div class="alert alert-danger loginerrorreg d-none">KAYIT BAŞARISIZ ! </div>
                    <form id="userregister" method="post">
                        @csrf
                        <div class="mb-4">
                            <input type="text" name="usernamereg" class="form-control sign-input" aria-describedby="Ad Soyad" placeholder="Ad Soyad">
                        </div>
                        <div class="mb-4">
                            <input type="email" name="usermailreg" class="form-control sign-input" aria-describedby="E-Posta" placeholder="E-posta ya da kullanıcı adresiniz">
                        </div>
                        <div class="mb-4">
                            <input type="password" name="userpasswordreg" class="form-control sign-input" placeholder="Şifreniz">
                        </div>
                        <input type="submit" value="Hesabımı oluştur" class="btn btn-primary w-100 sign-button mb-3" />
                    </form>
                </div>
                <div class="col-12">
                    <a href="#" class="form-text sign-form-link">“Hesabımı oluştur” seçeneğine tıklamanız durumunda Hüküm ve Koşullarımızı, Çerez politikamızı ve
                        Gizlilik politikamızı okuduğunuzu ve kabul ettiğinizi doğrulamış olursunuz.</a>
                </div>
                <div class="col-12">
                    <div class="sign-footer">
                        <div class="sign-footer-caption mb-3">Zaten hesabın var mı?</div>
                        <div class="sign-link" id="signInBtn">Oturum açın</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>

.mobilboyutla h1 { font-size:40px !important; }
.mobilboyutla p { font-size:20px !important; }

@media only screen and (max-width: 1000px) {

	.mobilserbest { height:auto !important; }
	.mobilserbest2 { height:300px !important; }

	.mobilboyutla h1 { font-size:22px !important; }
	.mobilboyutla p { font-size:13px !important; }

}

.detail-content img { max-width:100% !important;}
.detail-content embed { max-width:100% !important;}
.detail-content iframe { max-width:100% !important;}

</style>
