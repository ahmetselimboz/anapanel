@extends('backend.layout')

@section('title', 'Plugin Yönetimi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Plugin Yönetimi</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.plugins.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i> Yeni Plugin Ekle
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    
                    <!-- Kurulu Plugin'ler -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>📦 Kurulu Plugin'ler</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Plugin Adı</th>
                                            <th>Başlık</th>
                                            <th>Versiyon</th>
                                            <th>Yazar</th>
                                            <th>Durum</th>
                                            <th>Dosya Durumu</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($installedPlugins as $plugin)
                                        <tr>
                                            <td>{{ $plugin->name }}</td>
                                            <td>{{ $plugin->title }}</td>
                                            <td>{{ $plugin->version }}</td>
                                            <td>{{ $plugin->author }}</td>
                                            <td>
                                                @if($plugin->status === 'active')
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-secondary">Pasif</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(File::exists(base_path("plugins/{$plugin->name}/src")))
                                                    <span class="badge badge-success">Dosyalar Mevcut</span>
                                                @else
                                                    <span class="badge badge-warning">Sadece Config</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    
                                                    
                                                    @if($plugin->status === 'active')
                                                        <button class="btn btn-warning btn-sm" onclick="disablePlugin('{{ $plugin->name }}')">
                                                            <i class="fa fa-pause"></i> Devre Dışı
                                                        </button>
                                                    @else
                                                        <button class="btn btn-success btn-sm" onclick="enablePlugin('{{ $plugin->name }}')">
                                                            <i class="fa fa-play"></i> Etkinleştir
                                                        </button>
                                                    @endif
                                                    
                                                    <a href="{{ route('admin.plugins.settings', $plugin->name) }}" class="btn btn-info btn-sm">
                                                        <i class="fa fa-cog"></i> Ayarlar
                                                    </a>
                                                    
                                                    <button class="btn btn-danger btn-sm" onclick="uninstallPlugin('{{ $plugin->name }}')">
                                                        <i class="fa fa-trash"></i> Kaldır
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Henüz kurulu plugin bulunmuyor.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Mevcut Plugin'ler -->
                    <div class="row">
                        <div class="col-12">
                            <h4>📁 Mevcut Plugin'ler</h4>
                            <div class="row">
                                @forelse($availablePlugins as $plugin)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="card-title">{{ $plugin['title'] }}</h5>
                                                <a href="{{ route('admin.plugins.edit', $plugin['name']) }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit"></i> Düzenle
                                                </a>
                                            </div>  
                                            <p class="card-text">{{ $plugin['description'] }}</p>

                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <strong>Versiyon:</strong> {{ $plugin['version'] }}<br>
                                                    <strong>Yazar:</strong> {{ $plugin['author'] }}
                                                </small>
                                            </div>
                                            
                                            @if($plugin['installed'])
                                                <div class="badge badge-info">
                                                    <i class="fa fa-info-circle"></i> Bu plugin zaten kurulu.
                                                </div>
                                            @else
                                                <button class="btn btn-primary btn-block" onclick="installPlugin('{{ $plugin['name'] }}')">
                                                    <i class="fa fa-download"></i> Kur
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <i class="fa fa-exclamation-triangle"></i> Mevcut plugin bulunamadı.
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Yükleniyor...</span>
                </div>
                <p class="mt-2">İşlem yapılıyor, lütfen bekleyin...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
function showLoading() {
    $('#loadingModal').modal('show');
}

function hideLoading() {
    $('#loadingModal').modal('hide');
}

function showMessage(message, type = 'success') {
    

    //toastr[type](message); not work in this template
    //so we use this code
    if (type === 'success') {
        toastr.success(message);
    } else {
        toastr.error(message);
    }
    
}

function installPlugin(pluginName) {
    if (!confirm('Bu plugin\'i kurmak istediğinizden emin misiniz?')) {
        return;
    }

    showLoading();

    $.ajax({
        url: '{{ route("admin.plugins.install", ":plugin") }}'.replace(':plugin', pluginName),
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            hideLoading();
            if (response.success) {
                showMessage(response.message, 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                showMessage(response.message, 'error');
            }
        },
        error: function(xhr) {
            hideLoading();
            let message = 'Bir hata oluştu!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            showMessage(message, 'error');
        }
    });
}

function uninstallPlugin(pluginName) {
    if (!confirm('Bu plugin\'i kaldırmak istediğinizden emin misiniz? Bu işlem geri alınamaz!')) {
        return;
    }

    showLoading();

    $.ajax({
        url: '{{ route("admin.plugins.uninstall", ":plugin") }}'.replace(':plugin', pluginName),
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            hideLoading();
            if (response.success) {
                showMessage(response.message, 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                showMessage(response.message, 'error');
            }
        },
        error: function(xhr) {
            hideLoading();
            let message = 'Bir hata oluştu!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            showMessage(message, 'error');
        }
    });
}

function enablePlugin(pluginName) {
    showLoading();

    $.ajax({
        url: '{{ route("admin.plugins.enable", ":plugin") }}'.replace(':plugin', pluginName),
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            hideLoading();
            if (response.success) {
                showMessage(response.message, 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                showMessage(response.message, 'error');
            }
        },
        error: function(xhr) {
            hideLoading();
            let message = 'Bir hata oluştu!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            showMessage(message, 'error');
        }
    });
}

function disablePlugin(pluginName) {
    if (!confirm('Bu plugin\'i devre dışı bırakmak istediğinizden emin misiniz?')) {
        return;
    }

    showLoading();

    $.ajax({
        url: '{{ route("admin.plugins.disable", ":plugin") }}'.replace(':plugin', pluginName),
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            hideLoading();
            if (response.success) {
                showMessage(response.message, 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                showMessage(response.message, 'error');
            }
        },
        error: function(xhr) {
            hideLoading();
            let message = 'Bir hata oluştu!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            showMessage(message, 'error');
        }
    });
}
</script>
@endsection 