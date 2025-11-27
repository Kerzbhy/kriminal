@extends('layouts.app')

@section('page-title', 'Visualisasi Peta')

@section('content')
    <div class="container-fluid py-2">
        <div class="row">
            {{-- Header Halaman --}}
            <div class="ms-3 mb-3">
                <h3 class="mb-0 h4 font-weight-bolder">Peta Persebaran</h3>
                <p class="mb-0">Menampilkan Visual Geografis Kota Kendari</p>
            </div>

            <div class="col-12">
                <div class="card shadow border-0" style="overflow: hidden;">
                    
                    {{-- Header Card + Legend Status --}}
                    <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-dark">
                             <i class="fas fa-map-marked-alt me-2"></i>Visualisasi Peta
                        </h6>
                        
                        {{-- Keterangan Warna (Legend) --}}
                        <div class="small">
                            @if($isClustered)
                                <span class="badge me-1" style="background-color: #1cc88a;"><i class="fas fa-shield-alt me-1"></i>C1 (Aman)</span>
                                <span class="badge me-1 " style="background-color: #f6c23e;"><i class="fas fa-exclamation-circle me-1"></i>C2 (Sedang)</span>
                                <span class="badge" style="background-color: #e74a3b;"><i class="fas fa-exclamation-triangle me-1"></i>C3 (Rawan)</span>
                            @else
                                <span class="badge bg-primary"><i class="fas fa-map-marker-alt me-1"></i>Titik Lokasi Data</span>
                            @endif
                        </div>
                    </div>

                    {{-- Body Peta --}}
                    <div class="card-body p-0 position-relative">
                        
                        {{-- 1. Overlay Loading (Akan disembunyikan via JS) --}}
                        <div id="map-loading"
                            class="d-flex align-items-center justify-content-center flex-column position-absolute w-100 h-100 bg-light"
                            style="z-index: 999; top:0; left:0; opacity: 0.8;">
                            <div class="spinner-border text-primary" role="status"></div>
                            <div class="mt-2 fw-bold text-primary">Memuat Peta...</div>
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

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        window.crimeData = @json($dataPeta ?? []); 
    </script>

    <script src="{{ asset('admin2/assets/js/map.js') }}"></script>
@endsection