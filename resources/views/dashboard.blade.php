@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
    <div class="container-fluid py-2">

        {{-- Judul Halaman --}}
        <div class="row">
            <div class="ms-3">
                <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
                <p class="mb-4">Ringkasan Data Kriminalitas</p>
            </div>

            {{-- CARD 1: TOTAL KEJADIAN --}}
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-2 ps-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-sm mb-0 text-capitalize text-secondary font-weight-bold">Total Kasus</p>
                                <h4 class="mb-0">{{ $totalKejadian }} <small class="text-sm text-muted">Data</small></h4>
                            </div>
                            <div
                                class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                <i class="material-symbols-rounded opacity-10">folder_open</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-2 ps-3">
                        <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">Update </span> Real-time</p>
                    </div>
                </div>
            </div>

            {{-- CARD 2: WILAYAH PALING RAWAN --}}
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-2 ps-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-sm mb-0 text-capitalize text-secondary font-weight-bold">Wilayah Rawan</p>
                                <h4 class="mb-0" style="font-size: 1.2rem">{{ $namaWilayahRawan }}</h4>
                            </div>
                            <div
                                class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                <i class="material-symbols-rounded opacity-10">location_on</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-2 ps-3">
                        <p class="mb-0 text-sm"><span class="text-danger font-weight-bolder">Terbanyak </span> Kasus</p>
                    </div>
                </div>
            </div>

            {{-- CARD 3: JENIS KEJAHATAN DOMINAN --}}
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-2 ps-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-sm mb-0 text-capitalize text-secondary font-weight-bold">Kasus Dominan</p>
                                <h4 class="mb-0" style="font-size: 1.2rem">{{ $namaJenisDominan }}</h4>
                            </div>
                            <div
                                class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                <i class="material-symbols-rounded opacity-10">warning</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-2 ps-3">
                        <p class="mb-0 text-sm"><span class="text-warning font-weight-bolder">Peringatan </span> Utama</p>
                    </div>
                </div>
            </div>

            {{-- CARD 4: TOTAL KERUGIAN --}}
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-2 ps-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-sm mb-0 text-capitalize text-secondary font-weight-bold">Total Kerugian</p>
                                <h4 class="mb-0 " style="font-size: 1.1rem">
                                    Rp {{ number_format($totalKerugian, 0, ',', '.') }}
                                </h4>
                            </div>
                            <div
                                class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                <i class="material-symbols-rounded opacity-10">attach_money</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-2 ps-3">
                        <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">Estimasi </span> Materiil</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW KEDUA: GRAFIK (SAYA LEBARKAN AGAR LEBIH BAGUS) --}}
        <div class="row mt-4">
            {{-- Grafik Bar Chart --}}
            <div class="col-lg-8 col-md-12 mb-4">
                <div class="card h-100">
                    {{-- Header Judul --}}
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0">Statistik Kasus per Kecamatan</h6>
                        </div>
                    </div>

                    {{-- Body Card dengan Chart --}}
                    <div class="card-body p-3">
                        {{-- Wadah Chart: Kita beri tinggi fix agar tidak meleber --}}
                        <div class="chart" style="height: 300px;">
                            <canvas id="chart-bars" class="chart-canvas" height="300"></canvas>
                        </div>

                        <hr class="dark horizontal my-3">

                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-sm my-auto me-1 text-success">check_circle</i>
                            <p class="mb-0 text-sm text-secondary">
                                Data update otomatis dari database
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel Informasi Tambahan (Opsional: Cuaca / List Ranking) --}}
            <div class="col-lg-4 col-md-12 mb-4">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <h6>Wilayah Paling Rawan (Top 5)</h6>
                    </div>
                    <div class="card-body p-3">
                        {{-- Jika ada data labels dan values dari Controller --}}
                        @if(count($grafikLabels) > 0)
                            <ul class="list-group">
                                @foreach($grafikLabels->take(5) as $index => $kecamatan)
                                    @php $nilai = $grafikValues[$index]; @endphp
                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="icon icon-shape icon-sm me-3 bg-gradient-{{ $index == 0 ? 'danger' : ($index == 1 ? 'warning' : 'dark') }} shadow text-center">
                                                <i class="material-symbols-rounded opacity-10 text-white">
                                                    {{ $index == 0 ? 'priority_high' : 'analytics' }}
                                                </i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-dark text-sm">{{ $kecamatan }}</h6>
                                                <span class="text-xs">{{ $nilai }} Kejadian</span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-center mt-4">Belum ada data kriminal.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- 1. Load Library Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- 2. JEMBATAN DATA (Wajib ada agar file terpisah bisa baca data dari Controller) --}}
    <script>
        window.dashboardData = {
            labels: @json($grafikLabels),
            values: @json($grafikValues)
        };
    </script>

    {{-- 3. Load File JS Terpisah (PATH SESUAI SCREENSHOT) --}}
    <script src="{{ asset('admin2/assets/js/chart.js') }}"></script>
@endsection