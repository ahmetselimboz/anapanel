@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Eklenti Detayları: {{ $plugin->name }}</h4>
                        <div class="box-controls pull-right">
                            <a href="{{ route('plugin.edit', $plugin->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-pencil"></i> Düzenle
                            </a>
                            <a href="{{ route('plugin.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left"></i> Geri Dön
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                @if($plugin->icon)
                                    <img src="{{ asset('storage/' . $plugin->icon) }}" alt="{{ $plugin->name }}" 
                                         class="img-thumbnail" style="max-width: 150px;">
                                @else
                                    <i class="fa fa-puzzle-piece fa-5x text-muted"></i>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h3>{{ $plugin->name }}</h3>
                                <p class="text-muted">{{ $plugin->description }}</p>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Versiyon:</strong></td>
                                                <td>{{ $plugin->getFormattedVersion() }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Geliştirici:</strong></td>
                                                <td>{{ $plugin->author ?? 'Belirtilmemiş' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Lisans:</strong></td>
                                                <td>{{ $plugin->license ?? 'Belirtilmemiş' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Durum:</strong></td>
                                                <td>
                                                    <span class="badge badge-{{ $plugin->getStatusClass() }}">
                                                        {{ $plugin->getStatusText() }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>İndirme:</strong></td>
                                                <td>{{ number_format($plugin->download_count) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Puan:</strong></td>
                                                <td>
                                                    @if($plugin->rating_count > 0)
                                                        <span class="text-warning">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fa fa-star{{ $i <= $plugin->rating ? '' : '-o' }}"></i>
                                                            @endfor
                                                            ({{ $plugin->rating_count }} değerlendirme)
                                                        </span>
                                                    @else
                                                        <span class="text-muted">Henüz değerlendirilmemiş</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Oluşturulma:</strong></td>
                                                <td>{{ $plugin->created_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Son Güncelleme:</strong></td>
                                                <td>{{ $plugin->updated_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- AÇIKLAMA --}}
                @if($plugin->description)
                    <div class="box">
                        <div class="box-header with-border">
                            <h5 class="box-title">Açıklama</h5>
                        </div>
                        <div class="box-body">
                            <p>{{ $plugin->description }}</p>
                        </div>
                    </div>
                @endif

                {{-- GEREKSİNİMLER --}}
                @if($plugin->requirements)
                    <div class="box">
                        <div class="box-header with-border">
                            <h5 class="box-title">Gereksinimler</h5>
                        </div>
                        <div class="box-body">
                            <p>{{ $plugin->requirements }}</p>
                        </div>
                    </div>
                @endif

                {{-- DEĞİŞİKLİK GEÇMİŞİ --}}
                @if($plugin->changelog)
                    <div class="box">
                        <div class="box-header with-border">
                            <h5 class="box-title">Değişiklik Geçmişi</h5>
                        </div>
                        <div class="box-body">
                            <pre>{{ $plugin->changelog }}</pre>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                {{-- DURUM BİLGİLERİ --}}
                <div class="box">
                    <div class="box-header with-border">
                        <h5 class="box-title">Durum Bilgileri</h5>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label>Mevcut Durum</label>
                            <div>
                                <span class="badge badge-{{ $plugin->getStatusClass() }}">
                                    {{ $plugin->getStatusText() }}
                                </span>
                            </div>
                        </div>
                        
                        @if($plugin->installed_at)
                            <div class="form-group">
                                <label>Yükleme Tarihi</label>
                                <div>{{ $plugin->installed_at->format('d.m.Y H:i') }}</div>
                            </div>
                        @endif
                        
                        @if($plugin->activated_at)
                            <div class="form-group">
                                <label>Aktivasyon Tarihi</label>
                                <div>{{ $plugin->activated_at->format('d.m.Y H:i') }}</div>
                            </div>
                        @endif
                        
                        @if($plugin->last_updated)
                            <div class="form-group">
                                <label>Son Güncelleme</label>
                                <div>{{ $plugin->last_updated->format('d.m.Y H:i') }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- İŞLEMLER --}}
                <div class="box">
                    <div class="box-header with-border">
                        <h5 class="box-title">İşlemler</h5>
                    </div>
                    <div class="box-body">
                        <div class="btn-group-vertical w-100">
                            @if($plugin->is_installed)
                                @if($plugin->is_active)
                                    <a href="{{ route('plugin.deactivate', $plugin->id) }}" class="btn btn-warning btn-sm mb-2">
                                        <i class="fa fa-pause"></i> Deaktifleştir
                                    </a>
                                @else
                                    <a href="{{ route('plugin.activate', $plugin->id) }}" class="btn btn-success btn-sm mb-2">
                                        <i class="fa fa-play"></i> Aktifleştir
                                    </a>
                                @endif
                                <a href="{{ route('plugin.uninstall', $plugin->id) }}" class="btn btn-info btn-sm mb-2">
                                    <i class="fa fa-download"></i> Kaldır
                                </a>
                            @else
                                <a href="{{ route('plugin.install', $plugin->id) }}" class="btn btn-primary btn-sm mb-2">
                                    <i class="fa fa-download"></i> Yükle
                                </a>
                            @endif
                            
                            <a href="{{ route('plugin.edit', $plugin->id) }}" class="btn btn-secondary btn-sm mb-2">
                                <i class="fa fa-pencil"></i> Düzenle
                            </a>
                            
                            <a href="{{ route('plugin.destroy', $plugin->id) }}" class="btn btn-danger btn-sm mb-2" 
                               onclick="return confirm('Bu eklentiyi silmek istediğinizden emin misiniz?')">
                                <i class="fa fa-trash"></i> Sil
                            </a>
                        </div>
                    </div>
                </div>

                {{-- AYARLAR --}}
                @if($plugin->settings)
                    <div class="box">
                        <div class="box-header with-border">
                            <h5 class="box-title">Ayarlar</h5>
                        </div>
                        <div class="box-body">
                            <ul class="list-unstyled">
                                @foreach($plugin->settings as $key => $value)
                                    <li>
                                        <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                        @if(is_bool($value))
                                            <span class="badge badge-{{ $value ? 'success' : 'danger' }}">
                                                {{ $value ? 'Aktif' : 'Pasif' }}
                                            </span>
                                        @elseif(is_array($value))
                                            <span class="text-muted">{{ json_encode($value) }}</span>
                                        @else
                                            <span class="text-muted">{{ $value }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- BAĞIMLILIKLAR --}}
                @if($plugin->dependencies)
                    <div class="box">
                        <div class="box-header with-border">
                            <h5 class="box-title">Bağımlılıklar</h5>
                        </div>
                        <div class="box-body">
                            <ul class="list-unstyled">
                                @foreach($plugin->dependencies as $dependency)
                                    <li>
                                        <i class="fa fa-link"></i> {{ $dependency }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
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
});
</script>
@endsection 