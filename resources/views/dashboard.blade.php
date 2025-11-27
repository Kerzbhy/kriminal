@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
    <div class="container-fluid py-2">
        
        {{-- Title Row --}}
        <div class="row">
            <div class="ms-3">
                <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
                <p class="mb-3">
                    Selamat datang di panel kontrol utama aplikasi manajemen data kriminalitas.
                </p>
                <p class="mb-3">
                    @if($isTopsisData)
                        <span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> Data Terupdate</span> 
                    @else
                        <span class="text-warning fw-bold"><i class="fas fa-exclamation-circle me-1"></i> Data Belum Terupdate</span> 
                    @endif
                </p>
            </div>

            {{-- 1. Total Kasus --}}
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-2 ps-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-sm mb-0 text-capitalize text-secondary font-weight-bold">Total Kasus</p>
                                <h4 class="mb-0">{{ $totalKejadian }}</h4>
                            </div>
                            <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                <i class="material-symbols-rounded opacity-10">folder_open</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-2 ps-3"><p class="mb-0 text-sm"><span class="text-success font-weight-bolder">Update</span> Realtime</p></div>
                </div>
            </div>

            {{-- 2. Wilayah Paling Rawan (Card Ini Akan Berubah Labelnya) --}}
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-2 ps-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-sm mb-0 text-capitalize text-secondary font-weight-bold">
                                    {{ $isTopsisData ? 'Rank 1 TOPSIS' : 'Terbanyak Kasus' }}
                                </p>
                                <h4 class="mb-0" style="font-size: 1.2rem">{{ $namaWilayahRawan }}</h4>
                            </div>
                            <div class="icon icon-md icon-shape bg-gradient-{{ $isTopsisData ? 'danger' : 'dark' }} shadow-dark shadow text-center border-radius-lg">
                                <i class="material-symbols-rounded opacity-10">{{ $isTopsisData ? 'trophy' : 'location_on' }}</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-2 ps-3">
                        <p class="mb-0 text-sm">
                            @if($isTopsisData) 
                               <span class="text-danger font-weight-bolder">Prioritas Utama</span> Penanganan
                            @else
                                <span class="text-secondary">Berdasarkan Jumlah</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- 3. Jenis Dominan --}}
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-2 ps-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-sm mb-0 text-capitalize text-secondary font-weight-bold">Kasus Dominan</p>
                                <h4 class="mb-0" style="font-size: 1.2rem">{{ $namaJenisDominan }}</h4>
                            </div>
                            <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                <i class="material-symbols-rounded opacity-10">warning</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-2 ps-3"><p class="mb-0 text-sm"><span class="text-warning font-weight-bolder">Peringatan</span> Utama</p></div>
                </div>
            </div>

            {{-- 4. Kerugian --}}
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-2 ps-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-sm mb-0 text-capitalize text-secondary font-weight-bold">Kerugian</p>
                                <h4 class="mb-0" style="font-size: 1rem">
                                    Rp {{ number_format($totalKerugian, 0, ',', '.') }}
                                </h4>
                            </div>
                            <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                <i class="material-symbols-rounded opacity-10">attach_money</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-2 ps-3"><p class="mb-0 text-sm"><span class="text-success font-weight-bolder">Total</span> Estimasi</p></div>
                </div>
            </div>
        </div>

        {{-- ROW KEDUA --}}
        <div class="row mt-4">
            
            {{-- Grafik (Kiri) --}}
            <div class="col-lg-8 col-md-12 mb-4">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0">Statistik Kasus per Kecamatan</h6>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart" style="height: 300px;">
                            <canvas id="chart-bars" class="chart-canvas" height="300"></canvas>
                        </div>
                        <hr class="dark horizontal my-3">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-sm my-auto me-1 text-success">check_circle</i>
                            <p class="mb-0 text-sm text-secondary">Data update otomatis dari database</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- List Top 5 (Kanan) --}}
            <div class="col-lg-4 col-md-12 mb-4">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        @if($isTopsisData)
                            <h6 class="text-danger fw-bold"><i class="fas fa-trophy me-2"></i>Top 5 Prioritas</h6>
                            <p class="text-xs mb-0">Urutan kecamatan paling rawan berdasarkan hasil perhitungan metode TOPSIS.</p>
                        @else
                            <h6>Wilayah Terbanyak (Top 5)</h6>
                            <p class="text-xs mb-0 text-muted">Diurutkan berdasarkan jumlah kejadian terbanyak.</p>
                        @endif
                    </div>
                    <div class="card-body p-3">
                        @if(count($top5List) > 0)
                            <ul class="list-group">
                                @foreach($top5List as $index => $item)
                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex align-items-center">
                                            {{-- Icon Number 1, 2, 3... --}}
                                            <div class="icon icon-shape icon-sm me-3 bg-gradient-{{ $index == 0 ? 'danger' : ($index == 1 ? 'warning' : 'dark') }} shadow text-center text-white fw-bold">
                                                {{ $index + 1 }}
                                            </div>
                                            
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-dark text-sm">{{ $item['kecamatan'] }}</h6>
                                                
                                                {{-- Keterangan Nilai Berubah sesuai Data --}}
                                                @if($isTopsisData)
                                                    <span class="text-xs text-danger fw-bold">
                                                        Skor Preferensi: {{ number_format($item['nilai'], 4) }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-muted">
                                                        {{ $item['nilai'] }} Kejadian
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($isTopsisData && $index == 0)
                                            <span class="badge bg-danger d-flex align-items-center">RAWAN</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-center small">Belum ada data.</p>
                        @endif
                        
                        {{-- Tombol CTA ke menu Prioritas --}}
                        @if(!$isTopsisData)
                            <div class="mt-4 text-center">
                                <a href="{{ route('prioritas') }}" class="btn btn-sm btn-outline-primary">
                                    Hitung Prioritas Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    {{-- (SCRIPT CHART SAMA SEPERTI SEBELUMNYA, TIDAK PERLU DIUBAH) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.dashboardData = {
            labels: @json($grafikLabels),
            values: @json($grafikValues)
        };
    </script>
    <script src="{{ asset('admin2/assets/js/chart.js') }}"></script>
@endsection