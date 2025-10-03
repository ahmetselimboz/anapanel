@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Üyeler</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                                <a href="{{ route('users.create') }}" type="button" class="btn btn-success btn-sm">
                                    <i class="fa fa-plus"></i> Üye Ekle
                                </a>
                                <a href="{{ route('users.trashed') }}" type="button" class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i> Çöp Kutusu
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB MENÜLERİ --}}
            <div class="col-12">
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#all">Tümü</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#admin">Yöneticiler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#editor">Editörler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#author">Köşe Yazarları</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#standard">Standart Üyeler</a>
                    </li>
                </ul>
            </div>

            {{-- TAB İÇERİKLERİ --}}
            <div class="col-12">
                <div class="tab-content">

                    {{-- Tüm Üyeler --}}
                    <div id="all" class="tab-pane fade show active">
                        <div class="row">
                            @foreach($users as $user)
                                 <div class="col-md-6 col-lg-3">
                    <div class="box">
                        <div class="flexbox align-items-center px-20 pt-20">
                            <label class="toggler toggler-danger fs-16">
                                <i class="fa fa-asdas asdas"></i>
                            </label>
                            <div class="dropdown">
                                <a data-bs-toggle="dropdown" href="#"><i class="fa fa-ellipsis-v text-muted"></i></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('users.edit', $user->id) }}" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"><i class="fa fa-pencil"></i> Düzenle</a>
                                    @if($user->status!=1)
                                        <a href="{{ route('users.destroy', $user->id) }}" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Çöpe At"><i class="fa fa-trash"></i> Çöpe At</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="box-body text-center pt-1 pb-15">
                            <a href="{{ route('users.edit', $user->id) }}">
                                <img class="avatar avatar-xxl" src="{{ asset($user->avatar) }}" width="150" height="100" onerror="this.onerror=null;this.src='/backend/assets/icons/avatar.png'" alt="">
                            </a>
                            <h5 class="mt-10 mb-1"><a class="hover-primary" href="#">{{ $user->name }}</a></h5>
                            @if($user->status==0)
                                <td><span class="badge  badge-secondary">Standart</span></td>
                            @elseif($user->status==1)
                                <td><span class="badge  badge-primary">YÖNETİCİ</span></td>
                            @elseif($user->status==2)
                                <td><span class="badge  badge-warning">Editör</span></td>
                            @elseif($user->status==3)
                                <td><span class="badge  badge-info">Köşe Yazarı</span></td>
                            @endif
                        </div>
                    </div>
                </div>

                            @endforeach
                        </div>
                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    </div>

                    {{-- Yöneticiler --}}
                    <div id="admin" class="tab-pane fade">
                        <div class="row">
                            @foreach($users->where('status', 1) as $user)
                                <div class="col-md-6 col-lg-3">
                                    <div class="box">
                                        <div class="flexbox align-items-center px-20 pt-20">
                                            <label class="toggler toggler-danger fs-16">
                                                <i class="fa fa-user"></i>
                                            </label>
                                            <div class="dropdown">
                                                <a data-bs-toggle="dropdown" href="#"><i class="fa fa-ellipsis-v text-muted"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="{{ route('users.edit', $user->id) }}" class="dropdown-item">
                                                        <i class="fa fa-pencil"></i> Düzenle
                                                    </a>
                                                    @if($user->status != 1)
                                                        <a href="{{ route('users.destroy', $user->id) }}" class="dropdown-item">
                                                            <i class="fa fa-trash"></i> Çöpe At
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-body text-center pt-1 pb-15">
                                            <a href="{{ route('users.edit', $user->id) }}">
                                                <img class="avatar avatar-xxl" src="{{ asset($user->avatar) }}" width="150" height="100"
                                                     onerror="this.onerror=null;this.src='/backend/assets/icons/avatar.png'" alt="">
                                            </a>
                                            <h5 class="mt-10 mb-1"><a class="hover-primary" href="#">{{ $user->name }}</a></h5>
                                
                                            @php
                                                $roles = ['Standart', 'YÖNETİCİ', 'Editör', 'Köşe Yazarı'];
                                                $colors = ['secondary', 'primary', 'warning', 'info'];
                                            @endphp
                                
                                            <span class="badge badge-{{ $colors[$user->status] ?? 'secondary' }}">
                                                {{ $roles[$user->status] ?? 'Bilinmiyor' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Editörler --}}
                    <div id="editor" class="tab-pane fade">
                        <div class="row">
                            @foreach($users->where('status', 2) as $user)
                                  <div class="col-md-6 col-lg-3">
                                    <div class="box">
                                        <div class="flexbox align-items-center px-20 pt-20">
                                            <label class="toggler toggler-danger fs-16">
                                                <i class="fa fa-user"></i>
                                            </label>
                                            <div class="dropdown">
                                                <a data-bs-toggle="dropdown" href="#"><i class="fa fa-ellipsis-v text-muted"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="{{ route('users.edit', $user->id) }}" class="dropdown-item">
                                                        <i class="fa fa-pencil"></i> Düzenle
                                                    </a>
                                                    @if($user->status != 1)
                                                        <a href="{{ route('users.destroy', $user->id) }}" class="dropdown-item">
                                                            <i class="fa fa-trash"></i> Çöpe At
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-body text-center pt-1 pb-15">
                                            <a href="{{ route('users.edit', $user->id) }}">
                                                <img class="avatar avatar-xxl" src="{{ asset($user->avatar) }}" width="150" height="100"
                                                     onerror="this.onerror=null;this.src='/backend/assets/icons/avatar.png'" alt="">
                                            </a>
                                            <h5 class="mt-10 mb-1"><a class="hover-primary" href="#">{{ $user->name }}</a></h5>
                                
                                            @php
                                                $roles = ['Standart', 'YÖNETİCİ', 'Editör', 'Köşe Yazarı'];
                                                $colors = ['secondary', 'primary', 'warning', 'info'];
                                            @endphp
                                
                                            <span class="badge badge-{{ $colors[$user->status] ?? 'secondary' }}">
                                                {{ $roles[$user->status] ?? 'Bilinmiyor' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Köşe Yazarları --}}
                    <div id="author" class="tab-pane fade">
                        <div class="row">
                            @foreach($users->where('status', 3) as $user)
                                  <div class="col-md-6 col-lg-3">
                                    <div class="box">
                                        <div class="flexbox align-items-center px-20 pt-20">
                                            <label class="toggler toggler-danger fs-16">
                                                <i class="fa fa-user"></i>
                                            </label>
                                            <div class="dropdown">
                                                <a data-bs-toggle="dropdown" href="#"><i class="fa fa-ellipsis-v text-muted"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="{{ route('users.edit', $user->id) }}" class="dropdown-item">
                                                        <i class="fa fa-pencil"></i> Düzenle
                                                    </a>
                                                    @if($user->status != 1)
                                                        <a href="{{ route('users.destroy', $user->id) }}" class="dropdown-item">
                                                            <i class="fa fa-trash"></i> Çöpe At
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-body text-center pt-1 pb-15">
                                            <a href="{{ route('users.edit', $user->id) }}">
                                                <img class="avatar avatar-xxl" src="{{ asset($user->avatar) }}" width="150" height="100"
                                                     onerror="this.onerror=null;this.src='/backend/assets/icons/avatar.png'" alt="">
                                            </a>
                                            <h5 class="mt-10 mb-1"><a class="hover-primary" href="#">{{ $user->name }}</a></h5>
                                
                                            @php
                                                $roles = ['Standart', 'YÖNETİCİ', 'Editör', 'Köşe Yazarı'];
                                                $colors = ['secondary', 'primary', 'warning', 'info'];
                                            @endphp
                                
                                            <span class="badge badge-{{ $colors[$user->status] ?? 'secondary' }}">
                                                {{ $roles[$user->status] ?? 'Bilinmiyor' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Standart Üyeler --}}
                    <div id="standard" class="tab-pane fade">
                        <div class="row">
                            @foreach($users->where('status', 0) as $user)
                                  <div class="col-md-6 col-lg-3">
                                    <div class="box">
                                        <div class="flexbox align-items-center px-20 pt-20">
                                            <label class="toggler toggler-danger fs-16">
                                                <i class="fa fa-user"></i>
                                            </label>
                                            <div class="dropdown">
                                                <a data-bs-toggle="dropdown" href="#"><i class="fa fa-ellipsis-v text-muted"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="{{ route('users.edit', $user->id) }}" class="dropdown-item">
                                                        <i class="fa fa-pencil"></i> Düzenle
                                                    </a>
                                                    @if($user->status != 1)
                                                        <a href="{{ route('users.destroy', $user->id) }}" class="dropdown-item">
                                                            <i class="fa fa-trash"></i> Çöpe At
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-body text-center pt-1 pb-15">
                                            <a href="{{ route('users.edit', $user->id) }}">
                                                <img class="avatar avatar-xxl" src="{{ asset($user->avatar) }}" width="150" height="100"
                                                     onerror="this.onerror=null;this.src='/backend/assets/icons/avatar.png'" alt="">
                                            </a>
                                            <h5 class="mt-10 mb-1"><a class="hover-primary" href="#">{{ $user->name }}</a></h5>
                                
                                            @php
                                                $roles = ['Standart', 'YÖNETİCİ', 'Editör', 'Köşe Yazarı'];
                                                $colors = ['secondary', 'primary', 'warning', 'info'];
                                            @endphp
                                
                                            <span class="badge badge-{{ $colors[$user->status] ?? 'secondary' }}">
                                                {{ $roles[$user->status] ?? 'Bilinmiyor' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection




