@extends('layouts.app')

@section('page-title', 'Peta')

@section('content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="ms-3 mb-3">
                <h3 class="mb-0 h4 font-weight-bolder">Peta</h3>
                <p class="mb-0">Menampilkan Visual Geografis Kota Kendari</p>
            </div>

            <div class="col-12">
                <div class="card shadow border-0" style="overflow: hidden;">
                    <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">

                        {{-- BAGIAN KIRI: JUDUL --}}
                        <h6 class="m-0 font-weight-bold text-dark">
                            <i class="fas fa-map-marked-alt me-2"></i>Visualisasi Peta
                        </h6>

                        {{-- BAGIAN KANAN: KONTROL DAN LEGEND --}}
                        <div class="d-flex flex-row align-items-center">

                            {{-- 1. GROUP SWITCH BUTTONS --}}
                            <div class="d-flex align-items-center border-end pe-3 me-3">

                                {{-- SWITCH 1: CLUSTER --}}
                                {{-- class 'me-4' memberikan jarak horizontal ke kanan --}}
                                <div class="form-check form-switch d-flex align-items-center p-0 m-0 me-4">
                                    <input class="form-check-input m-0 shadow-none" type="checkbox" id="switchCluster"
                                        checked>

                                    {{-- Text di sampingnya --}}
                                    <label class="form-check-label mb-0 ms-2" for="switchCluster"
                                        style="white-space: nowrap;">
                                        Titik Lokasi
                                    </label>
                                </div>

                                {{-- SWITCH 2: HEATMAP --}}
                                <div class="form-check form-switch d-flex align-items-center p-0 m-0">
                                    <input class="form-check-input m-0 shadow-none" type="checkbox" id="switchHeatmap">

                                    {{-- Text di sampingnya --}}
                                    <label class="form-check-label mb-0 ms-2" for="switchHeatmap"
                                        style="white-space: nowrap;">
                                        Heatmap
                                    </label>
                                </div>

                            </div>

                            {{-- 2. GROUP LEGEND BADGES (Dikembalikan ke sini) --}}
                            <div class="small d-flex align-items-center">
                                @if($isClustered)
                                    {{-- Legend C1 --}}
                                    <span class="badge me-1" style="background-color: #1cc88a; border: 1px solid #1cc88a;">
                                        <i class="fas fa-shield-alt me-1"></i>C1 (Aman)
                                    </span>
                                    {{-- Legend C2 --}}
                                    <span class="badge me-1" style="background-color: #f6c23e; border: 1px solid #f6c23e;">
                                        <i class="fas fa-exclamation-circle me-1"></i>C2 (Sedang)
                                    </span>
                                    {{-- Legend C3 --}}
                                    <span class="badge" style="background-color: #e74a3b; border: 1px solid #e74a3b;">
                                        <i class="fas fa-exclamation-triangle me-1"></i>C3 (Rawan)
                                    </span>
                                @else
                                    <span class="badge bg-primary">
                                        <i class="fas fa-map-marker-alt me-1"></i> Data Mentah
                                    </span>
                                @endif
                            </div>

                        </div>
                    </div>

                    {{-- Body Peta --}}
                    <div class="card-body p-0 position-relative">

                        {{-- 1. Overlay Loading (Akan disembunyikan via JS) --}}
                        <div id="map-loading"
                            class="d-flex align-items-center justify-content-center flex-column position-absolute w-100 h-100 bg-light"
                            style="z-index: 999; top:0; left:0; opacity: 0.8;">
                            <div class="spinner-border text-dark" role="status"></div>
                            <div class="mt-2 fw-bold text-dark">Memuat Peta...</div>
                        </div>

                        {{-- 2. Wadah Peta --}}
                        <div id="map" style="height: 600px; width: 100%; z-index: 1;"></div>

                    </div>

                    {{-- Footer Informasi Tambahan --}}
                    <div class="card-footer bg-light small">
                        Status Data:
                        @if($isClustered)
                            <strong class="text-success"><i class="fas fa-check-circle"></i> Hasil Clustering</strong>
                            (Menampilkan data berdasarkan tingkat kerawanan C1, C2, C3)
                        @else
                            <strong class="text-primary"><i class="fas fa-database"></i> Data Mentah</strong>
                            (Menampilkan semua data karena proses clustering belum dilakukan/direset)
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection




@section('scripts')

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

    <script>
        window.crimeData = @json($dataPeta ?? []); 
    </script>

    <script src="{{ asset('admin2/assets/js/map.js') }}"></script>
@endsection