@extends('layouts.appauth')

@section('content')

    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">

            <div class="col-12">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0">
                                <h2 class="text-primary">Kayıt Sayfası</h2>
                            </div>
                            <div class="p-40">
                                <form method="post" action="{{ route('register') }}">
                                    @csrf
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <input placeholder="Ad Soyad" id="name" type="text" class="form-control ps-15 bg-transparent @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                            @error('name') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <input placeholder="E posta adresi" id="email" type="email" class="form-control ps-15 bg-transparent @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                            @error('email') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <input placeholder="Şifre" id="password" type="password" class="form-control ps-15 bg-transparent @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                            @error('password') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <input placeholder="Şifre Tekrar" id="password-confirm" type="password" class="form-control ps-15 bg-transparent" name="password_confirmation" required autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-info margin-top-10">Kayıt Ol</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="text-center">
                                    <p class="mt-15 mb-0">Hesabın var mı?<a href="{{ route('login') }}" class="text-danger ms-5"> Giriş</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
