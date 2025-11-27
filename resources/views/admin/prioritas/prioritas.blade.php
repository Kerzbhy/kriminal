@extends('layouts.app')

@section('page-title', 'Prioritas')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        
        {{-- Header --}}
        <div class="col-12 mb-3 ms-1">
            <h3 class="mb-0 h4 font-weight-bolder">Prioritas</h3>
            <p class="mb-0 text-muted">Menampilkan detail perhitungan peringkat menggunakan metode TOPSIS.</p>
        </div>

        {{-- ===========================================
             CARD 1: TABEL DATA AGREGAT
        =========================================== --}}
        <div class="col-12 mb-5">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fas fa-list-alt me-2"></i>Tabel Matriks Keputusan (Alternatif)
                    </h6>
                </div>
                
                <div class="card-body p-0">
                    @if(empty($dataAgregat))
                        <div class="alert alert-warning m-4 text-center text-white" role="alert">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i><br>
                            <strong>Data Kosong!</strong><br>
                            Silakan lakukan proses <strong>Clustering</strong> terlebih dahulu.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-items-center mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Kecamatan</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Jumlah Kasus <span class="text-primary">(C1)</span>
                                        </th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Jenis Kejahatan Dominan <span class="text-info">(C2)</span>
                                        </th>
                                        <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 pe-4">
                                            Rata-Rata Kerugian <span class="text-danger">(C3)</span>
                                        </th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Kepadatan <span class="text-success">(C4)</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dataAgregat as $row)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm fw-bold">{{ $row['kecamatan'] }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-secondary text-xs">{{ $row['C1'] }}</span>
                                        </td>
                                        {{-- Tampilkan Info Teks dan Skor C2 --}}
                                        <td class="align-middle text-center text-sm">
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="fw-bold text-dark">{{ $row['C2_info'] }}</span>
                                                @php
                                                    $skor = $row['C2'];
                                                    $bg = ($skor >= 4) ? 'danger' : (($skor==3) ? 'warning text-dark' : 'info');
                                                @endphp
                                                <span class="badge bg-{{$bg}}" style="font-size:0.6rem">Skor: {{ $skor }}</span>
                                            </div>
                                        </td>
                                        <td class="align-middle text-end pe-4">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                Rp {{ number_format($row['C3'], 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ number_format($row['C4'], 0, ',', '.') }} /km²
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===========================================
             CARD 2: FORM INPUT PARAMETER (BOBOT)
             Tampil hanya jika data ada
        =========================================== --}}
        @if(!empty($dataAgregat))
        <div class="col-12 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fas fa-sliders-h me-2"></i>Input Parameter Pembobotan TOPSIS
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('prioritas.proses') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            {{-- Input Bobot Kriteria (Diisi ulang pakai $old_inputs) --}}
                            
                            <!-- C1: Jumlah Kasus -->
                            <div class="col-md-3 form-group">
                                <label class="fw-bold">Bobot Jumlah Kasus (C1)</label>
                                <input type="number" step="0.01" class="form-control mb-1" name="weights[]"
                                    value="{{ $old_inputs['weights'][0] ?? '' }}" placeholder="cth: 0.4" required>
                                <select class="form-select form-select-sm" name="impacts[]">
                                    <option value="1" {{ ($old_inputs['impacts'][0] ?? 1) == 1 ? 'selected' : '' }}>Benefit</option>
                                    <option value="-1" {{ ($old_inputs['impacts'][0] ?? 1) == -1 ? 'selected' : '' }}>Cost</option>
                                </select>
                            </div>

                            <!-- C2: Jenis Kejahatan -->
                            <div class="col-md-3 form-group">
                                <label class="fw-bold">Bobot Jenis Kejahatan (C2)</label>
                                <input type="number" step="0.01" class="form-control mb-1" name="weights[]"
                                    value="{{ $old_inputs['weights'][1] ?? '' }}" placeholder="cth: 0.2" required>
                                <select class="form-select form-select-sm" name="impacts[]">
                                    <option value="1" {{ ($old_inputs['impacts'][1] ?? 1) == 1 ? 'selected' : '' }}>Benefit</option>
                                    <option value="-1" {{ ($old_inputs['impacts'][1] ?? 1) == -1 ? 'selected' : '' }}>Cost</option>
                                </select>
                            </div>

                            <!-- C3: Kerugian (Default Cost -1) -->
                            <div class="col-md-3 form-group">
                                <label class="fw-bold">Bobot Kerugian (C3)</label>
                                <input type="number" step="0.01" class="form-control mb-1" name="weights[]"
                                    value="{{ $old_inputs['weights'][2] ?? '' }}" placeholder="cth: 0.2" required>
                                <select class="form-select form-select-sm" name="impacts[]">
                                    <option value="-1" {{ ($old_inputs['impacts'][2] ?? -1) == -1 ? 'selected' : '' }}>Cost</option>
                                    <option value="1" {{ ($old_inputs['impacts'][2] ?? -1) == 1 ? 'selected' : '' }}>Benefit</option>
                                </select>
                            </div>

                            <!-- C4: Kepadatan -->
                            <div class="col-md-3 form-group">
                                <label class="fw-bold">Bobot Kepadatan (C4)</label>
                                <input type="number" step="0.01" class="form-control mb-1" name="weights[]"
                                    value="{{ $old_inputs['weights'][3] ?? '' }}" placeholder="cth: 0.2" required>
                                <select class="form-select form-select-sm" name="impacts[]">
                                    <option value="1" {{ ($old_inputs['impacts'][3] ?? 1) == 1 ? 'selected' : '' }}>Benefit</option>
                                    <option value="-1" {{ ($old_inputs['impacts'][3] ?? 1) == -1 ? 'selected' : '' }}>Cost</option>
                                </select>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="text-end mt-4">
                            @if(isset($hasil_topsis))
                                {{-- Tombol Reset & Hitung Ulang jika data tersimpan --}}
                                <a href="{{ route('prioritas.reset') }}" class="btn btn-outline-danger me-2">
                                    <i class="fas fa-undo me-2"></i>Reset Perhitungan
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-sync-alt me-2"></i>Hitung Ulang
                                </button>
                            @else
                                <button type="submit" class="btn btn-info text-white">
                                    <i class="fas fa-calculator me-2"></i>Hitung & Proses
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- ===========================================
             HASIL PERHITUNGAN TOPSIS (Dari Session)
        =========================================== --}}
        @if (isset($hasil_topsis))
            <div class="col-12 mt-3 mb-3 text-center">
                <h5 class="fw-bold">DETAIL HASIL PERHITUNGAN TOPSIS</h5>
            </div>

            {{-- 1. Tabel Ranking Akhir --}}
            <div class="col-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-white"><h6 class="mb-0 fw-bold text-dark">Hasil Peringkat Akhir (Ranking)</h6></div>
                    <div class="card-body p-0">
                         <div class="table-responsive">
                            <table class="table table-striped align-middle text-center mb-0">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>Rank</th>
                                        <th>Kecamatan (Alternatif)</th>
                                        <th>Skor Preferensi (V)</th>
                                        <th>Status Kerawanan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Proses Pengurutan Ranking di View
                                        $final = [];
                                        foreach($hasil_topsis['alternatives'] as $idx => $name) {
                                            $final[] = [
                                                'name' => $name, 
                                                'score' => $hasil_topsis['scores'][$idx]
                                            ];
                                        }
                                        usort($final, fn($a, $b) => $b['score'] <=> $a['score']);
                                    @endphp

                                    @foreach($final as $k => $res)
                                    <tr>
                                        <td>
                                            @if($k==0) <i class="fas fa-trophy text-warning"></i> 1 
                                            @else {{ $k+1 }} @endif
                                        </td>
                                        <td class="fw-bold">{{ $res['name'] }}</td>
                                        <td><strong>{{ number_format($res['score'], 4) }}</strong></td>
                                        <td>
                                            @if($k <= 2) <span class="badge bg-danger">Sangat Prioritas (Rawan)</span>
                                            @elseif($k <= 5) <span class="badge bg-warning text-dark">Prioritas Sedang</span>
                                            @else <span class="badge bg-success">Aman</span> @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Detail Langkah Matriks (Collapsible) --}}
            <div class="col-12 text-center mb-4">
                 <button class="btn btn-sm btn-outline-secondary shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#detailMatriks">
                    <i class="fas fa-eye me-2"></i>Tampilkan/Sembunyikan Matriks Perhitungan Lengkap
                 </button>
            </div>

            <div class="collapse col-12" id="detailMatriks">
                <div class="row">
                    
                    {{-- A. Matriks Keputusan X (Raw) --}}
                    <div class="col-md-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-light"><h6 class="mb-0 fw-bold small">1. Matriks Keputusan Awal (X)</h6></div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 300px">
                                    <table class="table table-sm table-bordered text-center text-xs mb-0">
                                        <thead><tr><th>Alt</th><th>C1</th><th>C2</th><th>C3</th><th>C4</th></tr></thead>
                                        <tbody>
                                            @if(isset($dataAgregat))
                                                @foreach($dataAgregat as $row)
                                                <tr>
                                                    <td>{{ $row['kecamatan'] }}</td>
                                                    <td>{{ $row['C1'] }}</td>
                                                    <td>{{ $row['C2'] }}</td>
                                                    <td>{{ number_format($row['C3'],0,',','.') }}</td>
                                                    <td>{{ $row['C4'] }}</td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- B. Matriks Normalisasi R --}}
                    <div class="col-md-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-light"><h6 class="mb-0 fw-bold small">2. Matriks Normalisasi (R)</h6></div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 300px">
                                    <table class="table table-sm table-bordered text-center text-xs mb-0">
                                        <thead><tr><th>Alt</th><th>C1</th><th>C2</th><th>C3</th><th>C4</th></tr></thead>
                                        <tbody>
                                            @foreach($hasil_topsis['normalized_matrix'] as $i => $row)
                                            <tr>
                                                <td>{{ $hasil_topsis['alternatives'][$i] }}</td>
                                                @foreach($row as $val)<td>{{ number_format($val, 4) }}</td>@endforeach
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- C. Matriks Terbobot Y --}}
                    <div class="col-md-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-light"><h6 class="mb-0 fw-bold small">3. Matriks Normalisasi Terbobot (Y)</h6></div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 300px">
                                    <table class="table table-sm table-bordered text-center text-xs mb-0">
                                        <thead><tr><th>Alt</th><th>C1</th><th>C2</th><th>C3</th><th>C4</th></tr></thead>
                                        <tbody>
                                            @foreach($hasil_topsis['weighted_matrix'] as $i => $row)
                                            <tr>
                                                <td>{{ $hasil_topsis['alternatives'][$i] }}</td>
                                                @foreach($row as $val)<td>{{ number_format($val, 4) }}</td>@endforeach
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- D. Solusi Ideal --}}
                    <div class="col-md-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-light"><h6 class="mb-0 fw-bold small">4. Solusi Ideal Positif & Negatif</h6></div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered text-center text-xs mb-0">
                                        <thead><tr><th>Solusi</th><th>C1</th><th>C2</th><th>C3</th><th>C4</th></tr></thead>
                                        <tbody>
                                            <tr class="table-success fw-bold">
                                                <td>A+ (Positif)</td>
                                                @foreach($hasil_topsis['ideal_positive'] as $val)<td>{{ number_format($val, 4) }}</td>@endforeach
                                            </tr>
                                            <tr class="table-danger fw-bold">
                                                <td>A- (Negatif)</td>
                                                @foreach($hasil_topsis['ideal_negative'] as $val)<td>{{ number_format($val, 4) }}</td>@endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        @endif

    </div>
</div>
@endsection