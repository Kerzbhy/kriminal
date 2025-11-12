@extends('layouts.app')

@section('page-title', 'Clustering')

@section('content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="ms-3">
                <h3 class="mb-0 h4 font-weight-bolder">Clustering</h3>
                <p class="mb-4"> 
                    Mengelompokkan Data Kriminal
                </p>
            </div>
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold">Hasil Clustering</h6>
                        <button type="button" class="btn btn-sm btn-info d-flex align-items-center" data-toggle="modal"
                            data-target="#modalTambah">
                            <i class="fas fa-spinner me-2"></i>
                            Proses
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="dataTable">
                                <thead class="thead-dark text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Wilayah</th>
                                        <th>Cluster</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Contoh data dummy --}}
                                    <tr>
                                        <td>1</td>
                                        <td>Mandonga</td>
                                        <td><span class="badge bg-success">C1</span></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Baruga</td>
                                        <td><span class="badge bg-warning">C2</span></td>
                                    </tr>
                                                                        <tr>
                                        <td>3</td>
                                        <td>Abeli</td>
                                        <td><span class="badge bg-danger">C3</span></td>
                                    </tr>
                                    {{-- Data clustering akan ditampilkan secara dinamis di sini --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-4 shadow">
                    <div class="card-header">
                        <h6 class="mb-0 font-weight-bold">Visualisasi Clustering</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartClustering" width="100%" height="40"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Proses Clustering --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="#" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Proses Clustering</h5>
                    </div>

                    <div class="modal-body">
                        {{-- Bisa tambahkan parameter DBSCAN seperti eps dan minPts jika diperlukan --}}
                        <div class="form-group">
                            <label for="eps">Nilai Epsilon (eps)</label>
                            <input type="number" step="0.01" class="form-control" name="eps" placeholder="Contoh: 0.5"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="minPts">Minimum Points (minPts)</label>
                            <input type="number" class="form-control" name="minPts" placeholder="Contoh: 3" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Proses</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chartClustering').getContext('2d');

        const data = {
            datasets: [
                {
                    label: 'Cluster 1',
                    data: [
                        { x: 122.5153, y: -3.9982 },
                        { x: 122.5171, y: -3.9990 }
                    ],
                    backgroundColor: 'rgba(75, 192, 192, 0.7)'
                },
                {
                    label: 'Cluster 2',
                    data: [
                        { x: 122.5245, y: -4.0123 },
                        { x: 122.5260, y: -4.0130 }
                    ],
                    backgroundColor: 'rgba(255, 205, 86, 0.7)'
                },
                {
                    label: 'Noise',
                    data: [
                        { x: 122.5300, y: -4.0050 }
                    ],
                    backgroundColor: 'rgba(128, 128, 128, 0.7)'
                }
            ]
        };

        const config = {
            type: 'scatter',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `(${context.raw.x.toFixed(4)}, ${context.raw.y.toFixed(4)})`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: { display: true, text: 'Longitude' }
                    },
                    y: {
                        title: { display: true, text: 'Latitude' }
                    }
                }
            }
        };

        new Chart(ctx, config);
    </script>
@endsection