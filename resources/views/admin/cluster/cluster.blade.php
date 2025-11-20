@extends('layouts.app')

@section('page-title', 'Clustering')

@section('content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="ms-3">
                <h3 class="mb-0 h4 font-weight-bolder">Clustering</h3>
                <p class="mb-4">
                    Mengelompokkan Data Kriminal menggunakan DBSCAN
                </p>
            </div>
            <div class="col-12">
                <div class="card shadow">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold">Hasil Clustering</h6>
                        <div>
                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                data-target="#modalProses">
                                <i class="fas fa-spinner me-2"></i>
                                {{ isset($hasilClusterTabel) ? 'Proses Ulang' : 'Proses' }}
                            </button>
                            @isset($hasilClusterTabel)
                                <a href="{{ route('cluster.reset') }}" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt me-2"></i>
                                    Reset Hasil
                                </a>
                            @endisset
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div> @endif
                        @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div> @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="dataTable">
                                <thead class="text-center"> {{-- Menghapus thead-dark untuk tema terang standar --}}
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Wilayah</th>
                                        <th>Jumlah Kejadian</th>
                                        <th>Cluster</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($hasilClusterTabel)
                                        {{-- Gunakan logika pengurutan dari controller --}}
                                        @forelse ($hasilClusterTabel as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item['kecamatan'] }}</td>
                                                <td class="text-center">{{  $item['jumlah_kejadian'] }}</td>
                                                <td class="text-center">
                                                    @if($item['cluster_id'] == -1)
                                                        <span class="badge bg-secondary">NOISE</span>
                                                    @else
                                                        {{-- Logika warna badge dinamis --}}
                                                        @php
                                                            $colors = ['success', 'primary', 'info', 'warning', 'danger'];
                                                            $color = $colors[$item['cluster_id'] % count($colors)];
                                                        @endphp
                                                        <span class="badge bg-{{ $color }}">{{ $item['cluster_label'] }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">Hasil clustering tidak menemukan cluster apa pun
                                                    (semua data dianggap noise). Coba ubah parameter.</td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">Silakan klik tombol "Proses" untuk memulai
                                                clustering.</td>
                                        </tr>
                                    @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @isset($hasilClusterChart)
                    <div class="card mt-4 shadow">
                        <div class="card-header">
                            <h6 class="mb-0 font-weight-bold">Visualisasi Clustering</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="chartClustering" height="400"></canvas>
                        </div>
                    </div>
                @endisset
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalProses" tabindex="-1" role="dialog" aria-labelledby="modalProsesLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('cluster.proses') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Parameter Proses Clustering</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="eps">Nilai Epsilon (eps)</label>
                            <input type="number" step="0.1" class="form-control" name="eps" value="{{ old('eps') }}"
                                placeholder="Contoh: 0.5" required>
                        </div>
                        <div class="form-group">
                            <label for="min_samples">Minimum Points</label>
                            <input type="number" class="form-control" name="min_samples" value="{{ old('min_samples') }}"
                                placeholder="Contoh: 2" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info">Proses</button>
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

            // Bungkus semua logika Chart.js di dalam 'DOMContentLoaded'
            // Ini untuk memastikan elemen <canvas> sudah ada di halaman sebelum script ini dijalankan
            document.addEventListener('DOMContentLoaded', function () {

                // Ambil elemen canvas
                const ctx = document.getElementById('chartClustering');

                // Cek sekali lagi jika elemennya benar-benar ada
                if (ctx) {
                    const data = {
                        datasets: @json($hasilClusterChart)
                    };

                    const config = {
                        type: 'scatter',
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false, // Tambahan: Agar tinggi canvas fleksibel
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                // Tambahan: Tooltip yang lebih informatif
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
                                            let label = context.dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            if (context.parsed.y !== null && context.parsed.x !== null) {
                                                label += `(Lat: ${context.parsed.y.toFixed(4)}, Lon: ${context.parsed.x.toFixed(4)})`;
                                            }
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Longitude'
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Latitude'
                                    }
                                }
                            }
                        }
                    };

                    // Buat grafik baru
                    new Chart(ctx, config);
                }
            });
        @endisset
    </script>
@endsection