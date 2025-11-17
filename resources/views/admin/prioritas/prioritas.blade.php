@extends('layouts.app')

@section('page-title', 'Prioritas')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="ms-3">
            <h3 class="mb-0 h4 font-weight-bolder">Prioritas</h3>
            <p class="mb-4">
                Menampilkan detail perhitungan peringkat menggunakan metode TOPSIS.
            </p>
        </div>

        {{-- Form untuk Input Bobot --}}
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold">Input Parameter TOPSIS</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('prioritas.proses') }}" method="POST"> {{-- Pastikan route ini ada --}}
                        @csrf
                        <div class="row">
                            {{-- Input Bobot Kriteria (sesuaikan dengan kriteria Anda) --}}
                            <div class="col-md-3 form-group">
                                <label>Bobot Jml. Laporan (C1)</label>
                                <input type="number" step="0.01" class="form-control" name="weights[]" placeholder="cth: 0.4" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Bobot Kepadatan (C2)</label>
                                <input type="number" step="0.01" class="form-control" name="weights[]" placeholder="cth: 0.2" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Bobot Obyek Vital (C3)</label>
                                <input type="number" step="0.01" class="form-control" name="weights[]" placeholder="cth: 0.2" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Bobot Waktu Respon (C4)</label>
                                <input type="number" step="0.01" class="form-control" name="weights[]" placeholder="cth: 0.2" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            {{-- Input Jenis Kriteria (Impact) --}}
                             <div class="col-md-3 form-group">
                                <label>Jenis Kriteria C1</label>
                                <select class="form-control" name="impacts[]" required>
                                    <option value="1">Benefit</option>
                                    <option value="-1">Cost</option>
                                </select>
                            </div>
                           <div class="col-md-3 form-group">
                                <label>Jenis Kriteria C2</label>
                                <select class="form-control" name="impacts[]" required>
                                    <option value="1">Benefit</option>
                                    <option value="-1">Cost</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Jenis Kriteria C3</label>
                                <select class="form-control" name="impacts[]" required>
                                    <option value="1">Benefit</option>
                                    <option value="-1">Cost</option>
                                </select>
                            </div>
                           <div class="col-md-3 form-group">
                                <label>Jenis Kriteria C4</label>
                                <select class="form-control" name="impacts[]" required>
                                     <option value="-1">Cost</option>
                                    <option value="1">Benefit</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-calculator me-2"></i>
                                Hitung & Proses
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Menampilkan hasil jika ada --}}
        @if (isset($hasil_topsis))
            {{-- 1. Matriks Awal --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0 font-weight-bold">1. Matriks Keputusan (X)</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th><th>C1</th><th>C2</th><th>C3</th><th>C4</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hasil_topsis['matrix'] as $index => $row)
                                        <tr>
                                            <td><strong>{{ $hasil_topsis['alternatives'][$index] ?? 'A'.($index+1) }}</strong></td>
                                            @foreach ($row as $value) <td>{{ $value }}</td> @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Matriks Ternormalisasi --}}
            <div class="col-md-6 mb-4">
                 <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0 font-weight-bold">2. Matriks Ternormalisasi (R)</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                             <table class="table table-bordered text-center">
                                <thead>
                                     <tr>
                                        <th>Alternatif</th><th>C1</th><th>C2</th><th>C3</th><th>C4</th>
                                    </tr>
                                </thead>
                               <tbody>
                                     @foreach ($hasil_topsis['normalized_matrix'] as $index => $row)
                                        <tr>
                                             <td><strong>{{ $hasil_topsis['alternatives'][$index] ?? 'A'.($index+1) }}</strong></td>
                                             @foreach ($row as $value) <td>{{ number_format($value, 4) }}</td> @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Matriks Ternormalisasi Terbobot --}}
             <div class="col-md-6 mb-4">
                 <div class="card shadow">
                    <div class="card-header"><h6 class="mb-0 font-weight-bold">3. Matriks Ternormalisasi Terbobot (Y)</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                               <thead>
                                     <tr>
                                        <th>Alternatif</th><th>C1</th><th>C2</th><th>C3</th><th>C4</th>
                                    </tr>
                               </thead>
                               <tbody>
                                   @foreach ($hasil_topsis['weighted_matrix'] as $index => $row)
                                       <tr>
                                             <td><strong>{{ $hasil_topsis['alternatives'][$index] ?? 'A'.($index+1) }}</strong></td>
                                             @foreach ($row as $value) <td>{{ number_format($value, 4) }}</td> @endforeach
                                        </tr>
                                   @endforeach
                                </tbody>
                           </table>
                       </div>
                   </div>
                </div>
            </div>

            {{-- 4. Solusi Ideal Positif & Negatif --}}
             <div class="col-md-6 mb-4">
                <div class="card shadow">
                   <div class="card-header"><h6 class="mb-0 font-weight-bold">4. Solusi Ideal (A+ dan A-)</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <td><strong>Ideal Positif (A+)</strong></td>
                                         @foreach ($hasil_topsis['ideal_positive'] as $value) <td>{{ number_format($value, 4) }}</td> @endforeach
                                    </tr>
                                    <tr>
                                       <td><strong>Ideal Negatif (A-)</strong></td>
                                        @foreach ($hasil_topsis['ideal_negative'] as $value) <td>{{ number_format($value, 4) }}</td> @endforeach
                                    </tr>
                                </tbody>
                           </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5 & 6. Hasil Akhir --}}
            <div class="col-12 mb-4">
                <div class="card shadow">
                   <div class="card-header"><h6 class="mb-0 font-weight-bold">5 & 6. Hasil Akhir (Jarak, Preferensi, dan Peringkat)</h6></div>
                    <div class="card-body">
                       <div class="table-responsive">
                            <table class="table table-bordered table-striped text-center">
                               <thead class="thead-dark">
                                    <tr>
                                        <th>Peringkat</th>
                                        <th>Alternatif</th>
                                        <th>Jarak Ideal Positif (D+)</th>
                                        <th>Jarak Ideal Negatif (D-)</th>
                                        <th>Nilai Preferensi (V)</th>
                                   </tr>
                               </thead>
                                <tbody>
                                    @php
                                        // Gabungkan data untuk diurutkan
                                        $final_results = [];
                                        foreach ($hasil_topsis['alternatives'] as $index => $name) {
                                            $final_results[] = [
                                                'name' => $name,
                                                'dist_positive' => $hasil_topsis['dist_positive'][$index],
                                                'dist_negative' => $hasil_topsis['dist_negative'][$index],
                                                'score' => $hasil_topsis['scores'][$index]
                                            ];
                                        }
                                        // Urutkan berdasarkan skor tertinggi
                                        usort($final_results, function ($a, $b) {
                                            return $b['score'] <=> $a['score'];
                                        });
                                    @endphp

                                    @foreach ($final_results as $rank => $result)
                                        <tr>
                                            <td><span class="badge bg-{{ $rank == 0 ? 'success' : ($rank == 1 ? 'info' : ($rank == 2 ? 'warning' : 'secondary')) }}">{{ $rank + 1 }}</span></td>
                                            <td><strong>{{ $result['name'] }}</strong></td>
                                            <td>{{ number_format($result['dist_positive'], 4) }}</td>
                                            <td>{{ number_format($result['dist_negative'], 4) }}</td>
                                            <td><strong>{{ number_format($result['score'], 4) }}</strong></td>
                                        </tr>
                                    @endforeach
                               </tbody>
                           </table>
                       </div>
                   </div>
               </div>
            </div>
        @else
            <div class="col-12 text-center">
                <p>Silakan masukkan parameter dan klik tombol "Hitung & Proses" untuk melihat hasil perhitungan.</p>
            </div>
        @endif

    </div>
</div>
@endsection

@section('scripts')
    {{-- Script tambahan bisa diletakkan di sini jika perlu --}}
@endsection