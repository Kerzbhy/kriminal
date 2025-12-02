<!DOCTYPE html>
<html>
<head>
    <title>Laporan Prioritas</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; }
        .header p { margin: 2px 0; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table, th, td { border: 1px solid #333; }
        th, td { padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; font-weight: bold; }
        
        .section-title { font-size: 13px; font-weight: bold; margin-top: 15px; margin-bottom: 5px; color: #000; }
        .status-danger { background-color: #ffe6e6; color: #cc0000; }
        .status-warning { background-color: #fff8e1; color: #e6a800; }
        .status-success { background-color: #e6fffa; color: #008000; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN SISTEM PENDUKUNG KEPUTUSAN (TOPSIS)</h2>
        <h3>Analisis Tingkat Kerawanan Kriminalitas Kota Kendari</h3>
        <p>Dicetak Pada: {{ $date }}</p>
    </div>

    <hr style="border: 1px solid #000;">

    <!-- 1. HASIL AKHIR (UTAMA) -->
    <div class="section-title">A. HASIL PERINGKAT AKHIR (RANKING)</div>
    <p>Berdasarkan perhitungan metode TOPSIS, berikut adalah urutan prioritas penanganan wilayah:</p>
    
    <table>
        <thead>
            <tr>
                <th width="10%">Rank</th>
                <th>Kecamatan</th>
                <th>Skor Preferensi (V)</th>
                <th>Kategori Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rankingList as $k => $res)
                @php
                    // Logika Status Sederhana
                    $class = "";
                    $status = "Aman";
                    if($k <= 2) { $class = "status-danger"; $status = "SANGAT RAWAN"; }
                    elseif($k <= 5) { $class = "status-warning"; $status = "RAWAN SEDANG"; }
                    else { $class = "status-success"; }
                @endphp
                <tr>
                    <td style="font-weight: bold">{{ $k+1 }}</td>
                    <td style="text-align: left; padding-left:10px;">{{ $res['name'] }}</td>
                    <td>{{ number_format($res['score'], 4) }}</td>
                    <td class="{{ $class }}"><strong>{{ $status }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="page-break-after: always;"></div> {{-- Ganti Halaman Baru --}}

    <!-- 2. DETAIL PROSES (LAMPIRAN) -->
    <div class="header">
        <h3>LAMPIRAN: DETAIL PROSES PERHITUNGAN</h3>
    </div>

    {{-- Langkah 1: Matrix Awal --}}
    <div class="section-title">1. Matriks Keputusan Awal (X)</div>
    <table>
        <thead>
            <tr><th>Alternatif</th><th>C1 (Kasus)</th><th>C2 (Skor)</th><th>C3 (Rugi)</th><th>C4 (Kepadatan)</th></tr>
        </thead>
        <tbody>
            @foreach($dataAgregat as $row)
            <tr>
                <td align="left">{{ $row['kecamatan'] }}</td>
                <td>{{ $row['C1'] }}</td>
                <td>{{ $row['C2'] }}</td>
                <td>{{ number_format($row['C3'],0,',','.') }}</td>
                <td>{{ $row['C4'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Langkah 2: Normalisasi --}}
    <div class="section-title">2. Matriks Normalisasi (R)</div>
    <table>
        <thead><tr><th>Alt</th><th>C1</th><th>C2</th><th>C3</th><th>C4</th></tr></thead>
        <tbody>
            @foreach($hasilTopsis['normalized_matrix'] as $i => $row)
            <tr>
                <td align="left">{{ $hasilTopsis['alternatives'][$i] }}</td>
                @foreach($row as $val)<td>{{ number_format($val, 4) }}</td>@endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Langkah 3: Normalisasi Terbobot --}}
    <div class="section-title">3. Matriks Terbobot (Y)</div>
    <table>
        <thead><tr><th>Alt</th><th>C1</th><th>C2</th><th>C3</th><th>C4</th></tr></thead>
        <tbody>
            @foreach($hasilTopsis['weighted_matrix'] as $i => $row)
            <tr>
                <td align="left">{{ $hasilTopsis['alternatives'][$i] }}</td>
                @foreach($row as $val)<td>{{ number_format($val, 4) }}</td>@endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Langkah 4: Solusi Ideal --}}
    <div class="section-title">4. Solusi Ideal Positif (A+) & Negatif (A-)</div>
    <table>
        <thead><tr><th>Solusi</th><th>C1</th><th>C2</th><th>C3</th><th>C4</th></tr></thead>
        <tbody>
            <tr>
                <td><strong>A+ (Ideal)</strong></td> 
                @foreach($hasilTopsis['ideal_positive'] as $val) <td>{{ number_format($val, 4) }}</td> @endforeach
            </tr>
            <tr>
                <td><strong>A- (Negatif)</strong></td>
                @foreach($hasilTopsis['ideal_negative'] as $val) <td>{{ number_format($val, 4) }}</td> @endforeach
            </tr>
        </tbody>
    </table>

    <br>
    <p style="text-align: right; margin-top: 30px;">
        Kendari, {{ date('d F Y') }} <br><br><br>
        (......................................)<br>
        Admin Pengelola
    </p>

</body>
</html>