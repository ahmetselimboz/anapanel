@extends('layouts.appauth')

@section('title', 'GİRİŞ | VMG MEDYA')

@section('content')
    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">
            <div class="col-12">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg p-5">
                            <div class="content-top-agile p-20 pb-0 mb-3">
                                <h2 class="text-primary">2FA Doğrulama</h2>
                            </div>
                         
                            
                            <form method="POST" action="{{ route('2fa.verify') }}">
                                @csrf
                               
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-transparent"><i class="fa fa-lock"></i></span>
                                        <input placeholder="Google Authenticator kodu" id="code" type="text" class="form-control ps-15 bg-transparent @error('code') is-invalid @enderror" name="code"  required  autofocus>
                                        @error('code') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror
                                    </div>
                                </div>
                                <div class="col-12 text-center mt-3">
                                    <button type="submit" class="btn btn-danger mt-10">Giriş Yap</button>
                                </div>
                  
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


