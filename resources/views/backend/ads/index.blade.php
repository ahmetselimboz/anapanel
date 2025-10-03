@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Reklamlar</h4>
                    </div>

                    {{-- Sekmeler --}}
                    <div class="col-12">
                        <ul class="nav nav-tabs mt-3 mb-2" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#home">Ana Sayfa Reklamları</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#category">Kategori Reklamları</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#news">Haber Detay Reklamları</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#other">Diğer Reklamlar</a>
                            </li>
                        </ul>
                    </div>

                    {{-- İçerikler --}}
                    <div class="tab-content px-3">
                        {{-- ANA SAYFA --}}
                        <div id="home" class="tab-pane fade show active">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tr>
                                        <th>ID</th>
                                        <th>Başlık</th>
                                        <th>Reklam Tipi</th>
                                        <th>Yayın Durumu</th>
                                        <th>İşlemler</th>
                                    </tr>

                                    @forelse($ads as $ad)
                                        @if (($ad->id > 1 && $ad->id <= 5) || $ad->id == 24)
                                            <tr>
                                                <td>{{ $ad->id }}</td>
                                                <td>{{ $ad->title }}</td>
                                                <td>
                                                    @if ($ad->type == 0)
                                                        <span class="badge badge-success">Resim Reklam</span>
                                                    @elseif($ad->type == 1)
                                                        <span class="badge badge-primary">Kod Reklam</span>
                                                    @else
                                                        <span class="badge badge-danger">YOK</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($ad->publish == 0)
                                                        <span class="badge badge-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-danger">Pasif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('ads.edit', $ad->id) }}"
                                                        class="btn btn-sm btn-secondary" data-bs-toggle="tooltip"
                                                        title="Düzenle">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif

                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Reklam bulunamadı.</td>
                                        </tr>
                                    @endforelse
                                </table>
                            </div>

                        </div>

                        {{-- KATEGORİ --}}
                        <div id="category" class="tab-pane fade">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tr>
                                        <th>ID</th>
                                        <th>Başlık</th>
                                        <th>Reklam Tipi</th>
                                        <th>Yayın Durumu</th>
                                        <th>İşlemler</th>
                                    </tr>

                                    @forelse($ads as $ad)
                                        @if ($ad->id > 5 && $ad->id <= 7)
                                            <tr>
                                                <td>{{ $ad->id }}</td>
                                                <td>{{ $ad->title }}</td>
                                                <td>
                                                    @if ($ad->type == 0)
                                                        <span class="badge badge-success">Resim Reklam</span>
                                                    @elseif($ad->type == 1)
                                                        <span class="badge badge-primary">Kod Reklam</span>
                                                    @else
                                                        <span class="badge badge-danger">YOK</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($ad->publish == 0)
                                                        <span class="badge badge-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-danger">Pasif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('ads.edit', $ad->id) }}"
                                                        class="btn btn-sm btn-secondary" data-bs-toggle="tooltip"
                                                        title="Düzenle">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif

                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Reklam bulunamadı.</td>
                                        </tr>
                                    @endforelse
                                </table>
                            </div>

                        </div>

                        {{-- HABER DETAY --}}
                        <div id="news" class="tab-pane fade">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tr>
                                        <th>ID</th>
                                        <th>Başlık</th>
                                        <th>Reklam Tipi</th>
                                        <th>Yayın Durumu</th>
                                        <th>İşlemler</th>
                                    </tr>

                                    @forelse($ads as $ad)
                                        @if ($ad->id > 7 && $ad->id <= 10)
                                            <tr>
                                                <td>{{ $ad->id }}</td>
                                                <td>{{ $ad->title }}</td>
                                                <td>
                                                    @if ($ad->type == 0)
                                                        <span class="badge badge-success">Resim Reklam</span>
                                                    @elseif($ad->type == 1)
                                                        <span class="badge badge-primary">Kod Reklam</span>
                                                    @else
                                                        <span class="badge badge-danger">YOK</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($ad->publish == 0)
                                                        <span class="badge badge-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-danger">Pasif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('ads.edit', $ad->id) }}"
                                                        class="btn btn-sm btn-secondary" data-bs-toggle="tooltip"
                                                        title="Düzenle">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif

                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Reklam bulunamadı.</td>
                                        </tr>
                                    @endforelse
                                </table>
                            </div>

                        </div>

                        {{-- DİĞER --}}
                        <div id="other" class="tab-pane fade">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tr>
                                        <th>ID</th>
                                        <th>Başlık</th>
                                        <th>Reklam Tipi</th>
                                        <th>Yayın Durumu</th>
                                        <th>İşlemler</th>
                                    </tr>

                                    @forelse($ads as $ad)
                                        @if (
                                            !(
                                                ($ad->id > 1 && $ad->id <= 5) ||
                                                $ad->id == 24 ||
                                                ($ad->id > 5 && $ad->id <= 7) ||
                                                ($ad->id > 7 && $ad->id <= 10)
                                            ))
                                            @if (!($ad->id > 10 && $ad->id <= 13) && $ad->id != 22)
                                                <tr>
                                                    <td>{{ $ad->id }}</td>
                                                    <td>{{ $ad->title }}</td>
                                                    <td>
                                                        @if ($ad->type == 0)
                                                            <span class="badge badge-success">Resim Reklam</span>
                                                        @elseif($ad->type == 1)
                                                            <span class="badge badge-primary">Kod Reklam</span>
                                                        @else
                                                            <span class="badge badge-danger">YOK</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($ad->publish == 0)
                                                            <span class="badge badge-success">Aktif</span>
                                                        @else
                                                            <span class="badge badge-danger">Pasif</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('ads.edit', $ad->id) }}"
                                                            class="btn btn-sm btn-secondary" data-bs-toggle="tooltip"
                                                            title="Düzenle">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif

                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Reklam bulunamadı.</td>
                                        </tr>
                                    @endforelse
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
