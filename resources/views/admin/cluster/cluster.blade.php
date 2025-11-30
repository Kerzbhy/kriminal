@extends('layouts.app')

@section('page-title', 'Clustering')

@section('content')
    <div class="container-fluid py-2">
        <div class="row">
            {{-- Header --}}
            <div class="ms-3">
                <h3 class="mb-0 h4 font-weight-bolder">Clustering</h3>
                <p class="mb-4">
                    Mengelompokkan Data Kriminal menggunakan DBSCAN
                </p>
            </div>

            <div class="col-12">
                
                {{-- === 1. CARD PENGUJIAN VALIDITAS (DAVIES-BOULDIN INDEX) === --}}
                {{-- Hanya tampil jika data hasil dan nilai DBI tersedia --}}
                @if(isset($hasilClusterTabel) && isset($dbiScore) && $dbiScore > 0)
                    <div class="card shadow mb-4 border-0">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                
                                {{-- Keterangan Indikator --}}
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-lg bg-gradient-success shadow text-center border-radius-xl me-3 d-flex align-items-center justify-content-center text-white" style="width: 50px; height: 50px;">
                                        <i class="fas fa-check-double fa-lg"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 font-weight-bold text-dark">Pengujian Validitas (Davies-Bouldin Index)</h6>
                                        <p class="text-xs text-muted mb-0">
                                            Indikator Evaluasi: Semakin rendah nilai (mendekati 0), semakin baik kualitas cluster.
                                        </p>
                                    </div>
                                </div>

                                {{-- Nilai Skor & Status --}}
                                <div class="text-end">
                                    {{-- Warna Teks Angka --}}
                                    <h3 class="font-weight-bolder mb-0 {{ $dbiScore < 1.0 ? 'text-success' : ($dbiScore < 1.5 ? 'text-warning' : 'text-danger') }}">
                                        {{ $dbiScore }}
                                    </h3>
                                    
                                    {{-- Badge Status --}}
                                    <span class="badge {{ $dbiScore < 1.0 ? 'bg-success' : ($dbiScore < 1.5 ? 'bg-warning' : 'bg-danger') }}">
                                        @if($dbiScore < 1.0) 
                                            <i class="fas fa-star me-1"></i>Sangat Baik 
                                        @elseif($dbiScore < 1.5) 
                                            Cukup Baik
                                        @else 
                                            Buruk (Kurang Valid)
                                        @endif
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif
                {{-- === AKHIR CARD VALIDITAS === --}}


                {{-- === 2. CARD TABEL HASIL CLUSTERING === --}}
                <div class="card shadow">

                    <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                        <h6 class="mb-0 font-weight-bold">
                            <i class="fas fa-project-diagram me-2"></i> Hasil Clustering
                        </h6>
                        <div>
                            {{-- Tombol Proses --}}
                            <button type="button" class="btn btn-sm btn-info mb-0" data-toggle="modal" data-target="#modalProses">
                                <i class="fas fa-sync-alt me-2"></i>
                                {{ isset($hasilClusterTabel) ? 'Proses Ulang' : 'Proses' }}
                            </button>

                            {{-- Tombol Reset --}}
                            @isset($hasilClusterTabel)
                                <a href="{{ route('cluster.reset') }}" class="btn btn-sm btn-danger mb-0 ms-2">
                                    <i class="fas fa-trash-alt me-2"></i> Reset
                                </a>
                            @endisset
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Notifikasi Error/Success --}}
                        @if(session('success'))
                            <div class="alert alert-success text-white">{{ session('success') }}</div> 
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger text-white">{{ session('error') }}</div> 
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger text-white">
                                <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
                            </div>
                        @endif

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
                                                <td>{{ $item['kecamatan'] }}</td>
                                                <td class="text-center fw-bold">{{  $item['jumlah_kejadian'] }}</td>
                                                <td class="text-center">
                                                    @if($item['cluster_id'] == -1)
                                                        <span class="badge bg-secondary">NOISE</span>
                                                    @elseif($item['cluster_id'] == 0)
                                                        <span class="badge bg-success">{{ $item['cluster_label'] }}</span>
                                                    @elseif($item['cluster_id'] == 1)
                                                        <span class="badge bg-warning">{{ $item['cluster_label'] }}</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ $item['cluster_label'] }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center p-4">
                                                    Hasil clustering tidak valid (Data mungkin kosong atau Epsilon terlalu kecil/besar).
                                                </td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center p-5 text-muted">
                                                <i class="fas fa-info-circle fa-2x mb-3"></i><br>
                                                Silakan klik tombol <strong>"Proses"</strong> untuk memulai.
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
                                <i class="fas fa-chart-scatter me-2"></i>Visualisasi Sebaran Data (Scatter Plot)
                            </h6>
                        </div>
                        <div class="card-body">
                            {{-- Container canvas agar responsif --}}
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
    <div class="modal fade" id="modalProses" tabindex="-1" role="dialog" aria-labelledby="modalProsesLabel" aria-hidden="true">
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
                            <strong>Tips:</strong> Data dinormalisasi (0-1). Gunakan nilai Epsilon kecil (misal: 0.05 - 0.2).
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
@endsection

@section('scripts')
    {{-- Memuat pustaka Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Pastikan script hanya berjalan jika controller mengirimkan data hasil
        @isset($hasilClusterChart)

            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('chartClustering');

                if (ctx) {
                    const data = {
                        datasets: @json($hasilClusterChart)
                    };

                    const config = {
                        type: 'scatter',
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false, 
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { usePointStyle: true }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    callbacks: {
                                        label: function (context) {
                                            let label = context.dataset.label || '';
                                            if (label) { label += ': '; }
                                            if (context.parsed.y !== null && context.parsed.x !== null) {
                                                // Tampilkan koordinat
                                                label += `(Lon: ${context.parsed.x.toFixed(4)}, Lat: ${context.parsed.y.toFixed(4)})`;
                                            }
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    title: { display: true, text: 'Longitude' },
                                    ticks: { font: {size: 10} }
                                },
                                y: {
                                    title: { display: true, text: 'Latitude' },
                                    ticks: { font: {size: 10} }
                                }
                            }
                        }
                    };

                    new Chart(ctx, config);
                }
            });
        @endisset
    </script>
@endsection