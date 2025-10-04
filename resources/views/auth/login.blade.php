@extends('layouts.appauth')

@section('title', 'GİRİŞ | VMG MEDYA')

@section('content')
    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">
            <div class="col-12">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0 mb-3">
                                <h2 class="text-primary">Sisteme Giriş2</h2>
                            </div>
                            <div class="p-40">
                                <form method="post" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-transparent"><i class="fa fa-user"></i></span>
                                            <input placeholder="E posta adresi" id="email" type="email" class="form-control ps-15 bg-transparent @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                            @error('email') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text  bg-transparent"><i class="fa fa-lock"></i></span>
                                            <input placeholder="Şifre" id="password" type="password" class="form-control ps-15 bg-transparent @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                            @error('password') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 d-none">
                                            <div class="checkbox">
                                                <input class="form-check-input" type="checkbox" name="remember" id="basic_checkbox_1" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="basic_checkbox_1"> Beni hatırla </label>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-6 d-none">
                                            <div class="fog-pwd text-end">
                                                @if (Route::has('password.request'))
                                                    <a class="hover-warning" href="{{ route('password.request') }}">
                                                        Şifremi Unuttum!
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-12 text-center mt-3">
                                            <button type="submit" class="btn btn-danger mt-10">Giriş Yap</button>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


