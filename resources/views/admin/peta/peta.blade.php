@extends('layouts.app')

@section('page-title', 'Peta')

@section('content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="ms-3">
                <h3 class="mb-0 h4 font-weight-bolder">Peta</h3>
                <p class="mb-4">Menampilkan Visual Geografis Kota Kendari</p>
            </div>

            <div class="col-12">
                <div class="card shadow" style="overflow: hidden;">
                    <div class="card-header custom-map-header text-white">
                        <h6 class="mb-0 font-weight-bold">Visualisasi Peta Kota Kendari</h6>
                    </div>
                    <div class="card-body p-0 position-relative" style="height: 600px;">
                        <!-- Overlay Loading -->
                        <div id="map-loading"
                            class="map-loading d-flex align-items-center justify-content-center flex-column">
                            <div class="spinner-border text-dark" role="status"></div>
                            <div class="mt-2 fw-bold">Loading...</div>
                        </div>

                        {{-- Elemen Peta --}}
                        <div id="map" class="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection