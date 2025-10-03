@extends('backend.layout')

@section('title', $plugin->title . ' - Plugin Ayarları')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $plugin->title }} - Plugin Ayarları</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.plugins.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Geri Dön
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <form method="POST" action="{{ route('admin.plugins.update-settings', $plugin->name) }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group">
                                    <label for="plugin_title">Plugin Başlığı</label>
                                    <input type="text" class="form-control" id="plugin_title" name="title" 
                                           value="{{ $plugin->settings['title'] ?? $plugin->title }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="plugin_description">Açıklama</label>
                                    <textarea class="form-control" id="plugin_description" name="description" rows="3">{{ $plugin->settings['description'] ?? $plugin->description }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="plugin_status">Durum</label>
                                    <select class="form-control" id="plugin_status" name="status">
                                        <option value="active" {{ $plugin->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ $plugin->status === 'inactive' ? 'selected' : '' }}>Pasif</option>
                                    </select>
                                </div>

                                <!-- Plugin'e özel ayarlar buraya eklenebilir -->
                                @if(isset($plugin->settings['custom_settings']))
                                    <div class="form-group">
                                        <label>Özel Ayarlar</label>
                                        @foreach($plugin->settings['custom_settings'] as $key => $value)
                                            <div class="form-group">
                                                <label for="custom_{{ $key }}">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                                                <input type="text" class="form-control" id="custom_{{ $key }}" 
                                                       name="custom_settings[{{ $key }}]" value="{{ $value }}">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Ayarları Kaydet
                                </button>
                            </form>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Plugin Bilgileri</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Plugin Adı:</strong></td>
                                            <td>{{ $plugin->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Versiyon:</strong></td>
                                            <td>{{ $plugin->version }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Yazar:</strong></td>
                                            <td>{{ $plugin->author }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kurulum Tarihi:</strong></td>
                                            <td>{{ $plugin->created_at->format('d.m.Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Son Güncelleme:</strong></td>
                                            <td>{{ $plugin->updated_at->format('d.m.Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">Hızlı İşlemler</h5>
                                </div>
                                <div class="card-body">
                                    @if($plugin->status === 'active')
                                        <button class="btn btn-warning btn-block mb-2" onclick="disablePlugin('{{ $plugin->name }}')">
                                            <i class="fa fa-pause"></i> Devre Dışı Bırak
                                        </button>
                                    @else
                                        <button class="btn btn-success btn-block mb-2" onclick="enablePlugin('{{ $plugin->name }}')">
                                            <i class="fa fa-play"></i> Etkinleştir
                                        </button>
                                    @endif
                                    
                                    <button class="btn btn-danger btn-block" onclick="uninstallPlugin('{{ $plugin->name }}')">
                                        <i class="fa fa-trash"></i> Plugin'i Kaldır
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function enablePlugin(pluginName) {
    if (!confirm('Bu plugin\'i etkinleştirmek istediğinizden emin misiniz?')) {
        return;
    }

    $.ajax({
        url: '{{ route("admin.plugins.enable", ":plugin") }}'.replace(':plugin', pluginName),
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            let message = 'Bir hata oluştu!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            toastr.error(message);
        }
    });
}

function disablePlugin(pluginName) {
    if (!confirm('Bu plugin\'i devre dışı bırakmak istediğinizden emin misiniz?')) {
        return;
    }

    $.ajax({
        url: '{{ route("admin.plugins.disable", ":plugin") }}'.replace(':plugin', pluginName),
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            let message = 'Bir hata oluştu!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            toastr.error(message);
        }
    });
}

function uninstallPlugin(pluginName) {
    if (!confirm('Bu plugin\'i kaldırmak istediğinizden emin misiniz? Bu işlem geri alınamaz!')) {
        return;
    }

    $.ajax({
        url: '{{ route("admin.plugins.uninstall", ":plugin") }}'.replace(':plugin', pluginName),
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                setTimeout(function() {
                    window.location.href = '{{ route("admin.plugins.index") }}';
                }, 1500);
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            let message = 'Bir hata oluştu!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            toastr.error(message);
        }
    });
}
</script>
@endsection 