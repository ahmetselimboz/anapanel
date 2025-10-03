@extends('backend.layout')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Eklentiler</h4>
                    <div class="box-controls pull-right">
                        <div class="btn-group">
                            <a href="{{ route('plugin.create') }}" type="button" class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i> Eklenti Ekle
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FİLTRE VE ARAMA --}}
        <div class="col-12">
            <div class="box">
                <div class="box-body">
                    <form method="GET" action="{{ route('plugin.index') }}" class="row">
                        <div class="col-md-3">
                            <select name="filter" class="form-control">
                                <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>Tümü</option>
                                <option value="active" {{ request('filter') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="installed" {{ request('filter') == 'installed' ? 'selected' : '' }}>Yüklü</option>
                                <option value="available" {{ request('filter') == 'available' ? 'selected' : '' }}>Mevcut</option>
                                <option value="inactive" {{ request('filter') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Eklenti ara..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-search"></i> Ara
                            </button>
                            <a href="{{ route('plugin.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-refresh"></i> Temizle
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- İSTATİSTİKLER --}}
        <div class="col-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="box box-primary">
                        <div class="box-body text-center">
                            <h3>{{ $stats['total'] }}</h3>
                            <p>Toplam Eklenti</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box box-success">
                        <div class="box-body text-center">
                            <h3>{{ $stats['active'] }}</h3>
                            <p>Aktif Eklenti</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box box-warning">
                        <div class="box-body text-center">
                            <h3>{{ $stats['installed'] }}</h3>
                            <p>Yüklü Eklenti</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box box-info">
                        <div class="box-body text-center">
                            <h3>{{ $stats['available'] }}</h3>
                            <p>Mevcut Eklenti</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- EKLENTİ LİSTESİ --}}
        <div class="col-12">
            <div class="row">
                @forelse($plugins as $plugin)
                <div class="col-md-6 col-lg-3">
                    <div class="box">
                        <div class="flexbox align-items-center px-20 pt-20">
                            <div class="dropdown">
                                <a data-bs-toggle="dropdown" href="#"><i class="fa fa-ellipsis-v text-muted"></i></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ route('plugin.edit', $plugin->id) }}" class="dropdown-item">
                                        <i class="fa fa-pencil"></i> Düzenle
                                    </a>
                                    @if($plugin->is_installed)
                                    {{-- @if($plugin->is_active)
                                                 <a href="{{ route('plugin.deactivate', $plugin->id) }}" class="dropdown-item">
                                    <i class="fa fa-pause"></i> Deaktifleştir
                                    </a>
                                    @else
                                    <a href="{{ route('plugin.activate', $plugin->id) }}" class="dropdown-item">
                                        <i class="fa fa-play"></i> Aktifleştir
                                    </a>
                                    @endif --}}
                                    <a href="{{ route('plugin.uninstall', $plugin->id) }}" class="dropdown-item">
                                        <i class="fa fa-download"></i> Kaldır
                                    </a>
                                    @else
                                    {{-- <a href="{{ route('plugin.install', $plugin->id) }}" class="dropdown-item">
                                    <i class="fa fa-download"></i> Yükle
                                    </a>--}}
                                    @endif
                                    <a href="{{ route('plugin.destroy', $plugin->id) }}" class="dropdown-item text-danger">
                                        <i class="fa fa-trash"></i> Sil
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="box-body text-center pt-1 pb-15">
                            @if($plugin->icon)
                            <img class="avatar avatar-xxl" src="{{ asset('storage/' . $plugin->icon) }}" width="80" height="80"
                                onerror="this.onerror=null;this.src='/backend/assets/icons/plugin.png'" alt="">
                            @else
                            <i class="fa fa-puzzle-piece fa-3x text-muted"></i>
                            @endif

                            <h5 class="mt-10 mb-1">
                                <a class="hover-primary" href="">{{ $plugin->name }}</a>
                            </h5>

                            <p class="text-muted mb-2">{!! Str::limit($plugin->description, 100) !!}</p>

                     {{--      <div class="mb-2">
                                <span class="badge {{ $plugin->getStatusClass() }}">
                                    {{ $plugin->getStatusText() }}
                                </span>
                            </div>--}}
                            <div class="mb-2">
    <span class="badge badge-{{ $plugin->getStatusClass() }}">
        {{ $plugin->getStatusText() }}
    </span>
</div>

<div class="mb-2 row">
    <div class="col-md-4 text-start">
        <small class="text-muted">
            <i class="fa fa-user"></i> {{ $plugin->author }}
        </small>
    </div>
    
    <div class="col-md-8 text-end">
        @if($plugin->is_installed)
            @if($plugin->is_active)
                <span class="badge badge-success">Aktif</span>
            @else
                <span class="badge badge-warning">Pasif</span>
            @endif
        @else
            <!-- <span class="badge badge-secondary">Yüklü Değil</span> -->
        @endif
        
        <span class="badge badge-info">{{ $plugin->getFormattedVersion() }}</span>
    </div>
</div>

                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="box">
                    <div class="box-body text-center py-4">
                        <i class="fa fa-puzzle-piece fa-3x text-muted mb-3"></i>
                        <h4>Henüz eklenti bulunmuyor</h4>
                        <p class="text-muted">İlk eklentinizi eklemek için yukarıdaki "Eklenti Ekle" butonunu kullanın.</p>
                        <a href="{{ route('plugin.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Eklenti Ekle
                        </a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        {{-- SAYFALAMA --}}
        @if($plugins->hasPages())
        <div class="mt-3">
            {{ $plugins->links() }}
        </div>
        @endif
    </div>
    </div>
</section>
@endsection

@section('custom_js')
<script>
    $(document).ready(function() {
        // Tooltip'leri etkinleştir
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Dropdown menülerini etkinleştir
        $('.dropdown-toggle').dropdown();
    });
</script>
@endsection