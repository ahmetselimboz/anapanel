@extends('backend.layout')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-lg-12 col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Üye Düzenle</h4>
                </div>
                <!-- /.box-header -->
                @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Hata!</strong> Aşağıdaki hataları düzeltin.<br><br>
                    <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
                </div>
                @endif

                <form class="form" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ad Soyad</label>
                                    <input type="text" class="form-control" placeholder="Ad Soyad" name="name" value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">E posta</label>
                                    <input type="text" class="form-control" placeholder="Email" name="email" value="{{ $user->email }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Şifre</label>
                                    <input type="password" class="form-control" placeholder="Şifre" name="password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Durumu</label>
                                    <div class="form-group">
                                        <select class="form-control select2" style="width: 100%;" name="status">
                                            <option value="0" @if($user->status==0) selected="selected" @endif>Standart Üye</option>
                                            @if (auth()->user()->status == 1)
                                            <option value="1" @if($user->status==1) selected="selected" @endif>Yönetici</option>
                                            @endif
                                            <option value="2" @if($user->status==2) selected="selected" @endif>Editör</option>
                                            <option value="3" @if($user->status==3) selected="selected" @endif>Köşe Yazarı</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Telefon</label>
                                    <input type="text" class="form-control" placeholder="Telefon" name="phone" value="{{ $user->phone }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label d-block">Üye Avatarı</label>
                                    <label class="file">
                                        <input type="file" id="file" name="avatar">
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                            <img src="{{asset($user->avatar)}}" alt="" class="img-thumbnail w-180">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Üye Hakkında</label>
                            <textarea rows="5" class="form-control" placeholder="Kişi öz bilgi" name="about">{{ $user->about }}</textarea>
                        </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary"> <i class="ti-save-alt"></i> Kaydet </button>
            </div>
            </form>

            @if(auth()->user()->id == $user->id)
           <div class="row box-footer" >
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Google Authenticator</label>
                        @if (auth()->user()->two_factor_enabled)
                            <a href="{{ route('2fa.disable') }}" class="btn btn-danger d-block">
                                İki Faktörlü Doğrulamayı Kaldır
                            </a>
                        @else
                            <button type="button" class="btn btn-primary d-block" data-bs-toggle="modal"
                                data-bs-target="#securityModal">İki Faktörlü
                                Doğrulamayı Aç</button>
                        @endif

                    </div>
                </div>

           @endif
        </div>
        <!-- /.box -->
    </div>
    </div>
</section>
@endsection


@section('custom_js')
<script src="{{ asset('backend/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
<script src="{{ asset('backend/js/pages/advanced-form-element.js') }}"></script>
@endsection