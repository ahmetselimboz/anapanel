@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Eklenti Düzenle: {{ $plugin->name }}</h4>
                        <div class="box-controls pull-right">
                            <a href="{{ route('plugin.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left"></i> Geri Dön
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="box">
                    <div class="box-body">
                        <form method="POST" action="{{ route('plugin.update', $plugin->id) }}" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                {{-- TEMEL BİLGİLER --}}
                                <div class="col-md-8">
                                    <h5 class="mb-3">Temel Bilgiler</h5>
                                    
                                    <div class="form-group">
                                        <label for="name">Eklenti Adı <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name', $plugin->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Açıklama</label>
                                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                                  rows="4">{{ old('description', $plugin->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="version">Versiyon <span class="text-danger">*</span></label>
                                                <input type="text" name="version" id="version" class="form-control @error('version') is-invalid @enderror" 
                                                       value="{{ old('version', $plugin->version) }}" required>
                                                @error('version')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="author">Geliştirici</label>
                                                <input type="text" name="author" id="author" class="form-control @error('author') is-invalid @enderror" 
                                                       value="{{ old('author', $plugin->author) }}">
                                                @error('author')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="author_url">Geliştirici URL</label>
                                                <input type="url" name="author_url" id="author_url" class="form-control @error('author_url') is-invalid @enderror" 
                                                       value="{{ old('author_url', $plugin->author_url) }}">
                                                @error('author_url')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="plugin_url">Eklenti URL</label>
                                                <input type="url" name="plugin_url" id="plugin_url" class="form-control @error('plugin_url') is-invalid @enderror" 
                                                       value="{{ old('plugin_url', $plugin->plugin_url) }}">
                                                @error('plugin_url')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="documentation_url">Dokümantasyon URL</label>
                                        <input type="url" name="documentation_url" id="documentation_url" class="form-control @error('documentation_url') is-invalid @enderror" 
                                               value="{{ old('documentation_url', $plugin->documentation_url) }}">
                                        @error('documentation_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="license">Lisans</label>
                                                <select name="license" id="license" class="form-control @error('license') is-invalid @enderror">
                                                    <option value="">Lisans Seçin</option>
                                                    <option value="MIT" {{ old('license', $plugin->license) == 'MIT' ? 'selected' : '' }}>MIT</option>
                                                    <option value="GPL v2" {{ old('license', $plugin->license) == 'GPL v2' ? 'selected' : '' }}>GPL v2</option>
                                                    <option value="GPL v3" {{ old('license', $plugin->license) == 'GPL v3' ? 'selected' : '' }}>GPL v3</option>
                                                    <option value="Apache 2.0" {{ old('license', $plugin->license) == 'Apache 2.0' ? 'selected' : '' }}>Apache 2.0</option>
                                                    <option value="BSD" {{ old('license', $plugin->license) == 'BSD' ? 'selected' : '' }}>BSD</option>
                                                    <option value="Proprietary" {{ old('license', $plugin->license) == 'Proprietary' ? 'selected' : '' }}>Proprietary</option>
                                                </select>
                                                @error('license')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="license_url">Lisans URL</label>
                                                <input type="url" name="license_url" id="license_url" class="form-control @error('license_url') is-invalid @enderror" 
                                                       value="{{ old('license_url', $plugin->license_url) }}">
                                                @error('license_url')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- SAĞ SİDEBAR --}}
                                <div class="col-md-4">
                                    <h5 class="mb-3">Gereksinimler & Ayarlar</h5>
                                    
                                    <div class="form-group">
                                        <label for="requirements">Gereksinimler</label>
                                        <textarea name="requirements" id="requirements" class="form-control @error('requirements') is-invalid @enderror" 
                                                  rows="3" placeholder="PHP 8.0+, Laravel 10+">{{ old('requirements', $plugin->requirements) }}</textarea>
                                        @error('requirements')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="minimum_php_version">Min. PHP Versiyonu</label>
                                                <input type="text" name="minimum_php_version" id="minimum_php_version" class="form-control @error('minimum_php_version') is-invalid @enderror" 
                                                       value="{{ old('minimum_php_version', $plugin->minimum_php_version) }}" placeholder="8.0">
                                                @error('minimum_php_version')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="minimum_laravel_version">Min. Laravel Versiyonu</label>
                                                <input type="text" name="minimum_laravel_version" id="minimum_laravel_version" class="form-control @error('minimum_laravel_version') is-invalid @enderror" 
                                                       value="{{ old('minimum_laravel_version', $plugin->minimum_laravel_version) }}" placeholder="10.0">
                                                @error('minimum_laravel_version')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="icon">Eklenti İkonu</label>
                                        @if($plugin->icon)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $plugin->icon) }}" alt="Mevcut İkon" class="img-thumbnail" style="max-width: 100px;">
                                                <small class="d-block text-muted">Mevcut İkon</small>
                                            </div>
                                        @endif
                                        <input type="file" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" 
                                               accept="image/*">
                                        <small class="form-text text-muted">PNG, JPG, GIF, SVG (Max: 2MB)</small>
                                        @error('icon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="changelog">Değişiklik Geçmişi</label>
                                        <textarea name="changelog" id="changelog" class="form-control @error('changelog') is-invalid @enderror" 
                                                  rows="4" placeholder="v1.0.0 - İlk sürüm&#10;v1.1.0 - Yeni özellikler eklendi">{{ old('changelog', $plugin->changelog) }}</textarea>
                                        @error('changelog')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="download_count">İndirme Sayısı</label>
                                        <input type="number" name="download_count" id="download_count" class="form-control @error('download_count') is-invalid @enderror" 
                                               value="{{ old('download_count', $plugin->download_count) }}" min="0">
                                        @error('download_count')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="rating">Puan</label>
                                                <input type="number" name="rating" id="rating" class="form-control @error('rating') is-invalid @enderror" 
                                                       value="{{ old('rating', $plugin->rating) }}" min="0" max="5" step="0.01">
                                                @error('rating')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="rating_count">Puan Sayısı</label>
                                                <input type="number" name="rating_count" id="rating_count" class="form-control @error('rating_count') is-invalid @enderror" 
                                                       value="{{ old('rating_count', $plugin->rating_count) }}" min="0">
                                                @error('rating_count')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- DURUM BİLGİLERİ --}}
                                    <div class="mt-4">
                                        <h6>Durum Bilgileri</h6>
                                        <div class="form-group">
                                            <label>Durum</label>
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
                                        
                                        <div class="form-group">
                                            <label>Oluşturma Tarihi</label>
                                            <div>{{ $plugin->created_at->format('d.m.Y H:i') }}</div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Son Güncelleme</label>
                                            <div>{{ $plugin->updated_at->format('d.m.Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Değişiklikleri Kaydet
                                        </button>
                                        <a href="{{ route('plugin.index') }}" class="btn btn-secondary">
                                            <i class="fa fa-times"></i> İptal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
<script src="{{ asset('backend/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>

<script>
$(document).ready(function() {
    CKEDITOR.replace('description', {
        height: 200,
        toolbar: [
            { name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates'] },
            { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
            { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'SpellChecker', 'Scayt'] },
            '/',
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] },
            { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
            { name: 'insert', items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] },
            '/',
            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
            { name: 'tools', items: ['Maximize', 'ShowBlocks'] }
        ],
        removeButtons: '',
        filebrowserUploadUrl: "{{ route('ckeditorimageupload', ['_token' => csrf_token()]) }}",
        filebrowserUploadMethod: 'form'
    });
    // Form validation
    $('form').on('submit', function() {
        var isValid = true;
        
        // Required fields validation
        $('input[required], select[required], textarea[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            alert('Lütfen gerekli alanları doldurun.');
            return false;
        }
    });
    
    // File size validation
    $('#icon').on('change', function() {
        var file = this.files[0];
        if (file && file.size > 2 * 1024 * 1024) { // 2MB
            alert('Dosya boyutu 2MB\'dan büyük olamaz.');
            this.value = '';
        }
    });
});
</script>
@endsection 