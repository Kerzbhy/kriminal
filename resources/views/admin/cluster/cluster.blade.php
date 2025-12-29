@extends('layouts.app')

@section('page-title', 'Clustering')

@section('content')
    <div class="container-fluid py-2">
        <div class="row">
            {{-- Header --}}
            <div class="ms-3">
                <h3 class="mb-0 h4 font-weight-bolder">Clustering</h3>
                <p class="mb-4">Mengelompokkan Data Kriminal menggunakan DBSCAN</p>
            </div>

            <div class="col-12">

                {{-- === 1. CARD PENGUJIAN VALIDITAS (DAVIES-BOULDIN INDEX) === --}}
                @if(isset($hasilClusterTabel) && isset($dbiScore) && $dbiScore > 0)
                    <div class="card shadow mb-4 border-0">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-lg bg-gradient-success shadow text-center border-radius-xl me-3 d-flex align-items-center justify-content-center text-white"
                                        style="width: 50px; height: 50px;">
                                        <i class="fas fa-check-double fa-lg"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 font-weight-bold text-dark">Pengujian Validitas (Davies-Bouldin Index)
                                        </h6>
                                        <p class="text-xs text-muted mb-0">Indikator Evaluasi: Semakin rendah nilai (mendekati
                                            0), semakin baik kualitas cluster.</p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h3
                                        class="font-weight-bolder mb-0 {{ $dbiScore < 1.0 ? 'text-success' : ($dbiScore < 1.5 ? 'text-warning' : 'text-danger') }}">
                                        {{ $dbiScore }}
                                    </h3>
                                    <span
                                        class="badge {{ $dbiScore < 1.0 ? 'bg-success' : ($dbiScore < 1.5 ? 'bg-warning' : 'bg-danger') }}">
                                        {{ $dbiScore < 1.0 ? 'Sangat Baik' : ($dbiScore < 1.5 ? 'Cukup Baik' : 'Buruk') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- === 2. CARD TABEL HASIL CLUSTERING === --}}
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                        <h6 class="mb-0 font-weight-bold text-dark">
                            <i class="fas fa-project-diagram me-2"></i> Hasil Clustering
                        </h6>
                        <div>
                            <button type="button" class="btn btn-sm btn-info mb-0" data-toggle="modal"
                                data-target="#modalProses">
                                <i class="fas fa-sync-alt me-2"></i>
                                {{ isset($hasilClusterTabel) ? 'Proses Ulang' : 'Proses' }}
                            </button>
                            @isset($hasilClusterTabel)
                                <a href="{{ route('cluster.reset') }}" class="btn btn-sm btn-danger mb-0 ms-2">
                                    <i class="fas fa-trash-alt me-2"></i> Reset
                                </a>
                            @endisset
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Notifikasi --}}
                        @if(session('success'))
                        <div class="alert alert-success text-white">{{ session('success') }}</div> @endif
                        @if(session('error'))
                        <div class="alert alert-danger text-white">{{ session('error') }}</div> @endif
                        @if($errors->any())
                            <div class="alert alert-danger text-white">
                                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div> @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle" id="dataTable">
                                <thead class="text-center bg-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Wilayah</th>
                                        <th width="15%">Jumlah Kejadian</th>
                                        <th width="15%">Cluster</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($hasilClusterTabel)
                                        @forelse ($hasilClusterTabel as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    {{-- MODIFIKASI: Nama Wilayah jadi Link Klik --}}
                                                    {{-- Kita kirim data detail (list kejadian) lewat json_encode --}}
                                                    <a href="javascript:void(0)" class="text-dark fw-bold text-decoration-none"
                                                        onclick="showDetail('{{ $item['kecamatan'] }}', '{{ $item['cluster_label'] }}', {{ json_encode($item['list_detail'] ?? []) }})">
                                                        <i class="fas fa-search-plus me-1"></i> {{ $item['kecamatan'] }}
                                                    </a>
                                                </td>
                                                <td class="text-center fw-bold">{{  $item['jumlah_kejadian'] }}</td>
                                                <td class="text-center">
                                                    @php $cid = $item['cluster_id']; @endphp
                                                    @if($cid == -1) <span class="badge bg-secondary">NOISE</span>
                                                    @elseif($cid == 0) <span
                                                        class="badge bg-success">{{ $item['cluster_label'] }}</span>
                                                    @elseif($cid == 1) <span
                                                        class="badge bg-warning ">{{ $item['cluster_label'] }}</span>
                                                    @else <span class="badge bg-danger">{{ $item['cluster_label'] }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center p-4">Hasil clustering tidak valid.</td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center p-5 text-muted">Silakan klik tombol
                                                <strong>"Proses"</strong>.
                                            </td>
                                        </tr>
                                    @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- === 3. CARD VISUALISASI CHART === --}}
                @isset($hasilClusterChart)
                    <div class="card mt-4 shadow mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 font-weight-bold text-dark">
                                <i class="fas fa-chart-scatter me-2"></i>Visualisasi Sebaran Data
                            </h6>
                        </div>
                        <div class="card-body">
                            <div style="position: relative; height: 400px; width: 100%;">
                                <canvas id="chartClustering"></canvas>
                            </div>
                        </div>
                    </div>
                @endisset
            </div>
        </div>
    </div>

    {{-- MODAL INPUT PARAMETER --}}
    <div class="modal fade" id="modalProses" tabindex="-1" role="dialog" aria-labelledby="modalProsesLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('cluster.proses') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="modalProsesLabel">Parameter Proses Clustering</h5>
                        {{-- Tombol Close di Bootstrap 5 biasanya btn-close, atau pakai data-dismiss kalau versi lama --}}
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info text-white text-xs mb-3">
                            <i class="fas fa-lightbulb me-1"></i>
                            <strong>Tips:</strong> Data dinormalisasi (0-1). Gunakan nilai Epsilon kecil (misal: 0.05 -
                            0.2).
                        </div>

                        <div class="form-group mb-3">
                            <label for="eps" class="fw-bold">Nilai Epsilon (eps)</label>
                            <input type="number" step="0.001" class="form-control" name="eps" value="{{ old('eps') }}"
                                placeholder="Contoh: 0.05" required>
                            <small class="text-muted">Jarak radius pencarian tetangga.</small>
                        </div>

                        <div class="form-group">
                            <label for="min_samples" class="fw-bold">Minimum Points</label>
                            <input type="number" class="form-control" name="min_samples" value="{{ old('min_samples') }}"
                                placeholder="Contoh: 3" required>
                            <small class="text-muted">Minimal jumlah titik untuk membentuk cluster.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info">Proses Analisa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL DATA (POPUP) --}}
    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-info-circle me-2 text-info"></i>Detail Wilayah: <span id="detailTitle"
                            class="text-dark"></span>
                        <span id="detailBadge" class="badge ms-2"></span>
                    </h5>
                    {{-- Tombol Close X Manual jika bs5 belum load sempurna --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="background: none; border: none; font-size: 1.5rem;"
                        onclick="$('#modalDetail').modal('hide')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- Tabel List --}}
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold border-bottom pb-2">Daftar Kejadian</h6>
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-sm table-bordered mb-0 small">
                                    <thead class="bg-light text-dark">
                                        <tr>
                                            <th>Jenis Kejahatan</th>
                                            <th class="text-end">Kerugian</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detailTableBody"></tbody>
                                </table>
                            </div>
                        </div>
                        {{-- Peta Mini --}}
                        <div class="col-md-6">
                            <h6 class="fw-bold border-bottom pb-2">Lokasi Titik</h6>
                            <div id="miniMap"
                                style="height: 300px; width: 100%; border-radius: 8px; border: 1px solid #ddd;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"
                        onclick="$('#modalDetail').modal('hide')">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- 2. JEMBATAN DATA (PHP ke JS) --}}
    {{-- Kirim data chart hanya jika ada --}}
    <script>
        @isset($hasilClusterChart)
            window.clusterChartData = @json($hasilClusterChart);
        @else
            window.clusterChartData = null;
        @endisset
    </script>

    {{-- 3. PANGGIL FILE JS BARU --}}
    <script src="{{ asset('admin2/assets/js/minimap.js') }}"></script>
@endsection