<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataKriminal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class ClusterController extends Controller
{
    public function index()
    {
        if (Session::has('hasil_clustering')) {
            $hasil = Session::get('hasil_clustering');
            $data = [
                'title' => 'Hasil Clustering',
                'menuCluster' => 'active',
                'hasilClusterTabel' => $hasil['tabel'],
                'hasilClusterChart' => $hasil['chart'],
                'dbiScore' => $hasil['dbi'] ?? 0,
            ];
        } else {
            $data = [
                'title' => 'Clustering',
                'menuCluster' => 'active',
                'hasilClusterTabel' => null,
                'hasilClusterChart' => null
            ];
        }

        return view('admin.cluster.cluster', $data);
    }

    public function prosesCluster(Request $request)
    {
        // 1. Validasi Input (BIARKAN SAMA)
        $validator = Validator::make($request->all(), [
            'eps' => 'required|numeric|min:0.001',
            'min_samples' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->route('cluster')
                ->withErrors($validator)
                ->withInput();
        }

        // 2. AMBIL DATA LENGKAP
        $data_kriminal = DataKriminal::all(['id', 'kecamatan', 'latitude', 'longitude', 'jenis_kejahatan', 'kerugian']);

        if ($data_kriminal->isEmpty()) {
            return redirect()->route('cluster')->with('error', 'Tidak ada data kriminal di database.');
        }

        // 3. Format Data untuk Python
        $points = $data_kriminal->map(function ($item) {
            return [
                'id' => $item->id,
                'kecamatan' => $item->kecamatan,
                'lat' => (float) $item->latitude,
                'lon' => (float) $item->longitude,
                'jenis' => $item->jenis_kejahatan,
                'rugi' => (float) $item->kerugian
            ];
        })->toArray();

        // 4. Request API Python
        try {
            $response = Http::timeout(60)->post('http://127.0.0.1:5001/api/dbscan', [
                'data' => $points,
                'eps' => (float) $request->input('eps'),
                'min_samples' => (int) $request->input('min_samples'),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('cluster')->with('error', 'Gagal terhubung ke service API Flask.');
        }

        if (!$response->successful()) {
            return redirect()->route('cluster')->with('error', 'API Error: ' . $response->status());
        }

        // 5. PARSING RESPON DENGAN BENAR (Perbaikan disini)
        $jsonResponse = $response->json();

        // Python mengembalikan: { "clusters": { ... }, "dbi": 0.123 }
        // Kita pecah dulu isinya
        $hasilClusterRaw = $jsonResponse['clusters'] ?? []; // Array cluster
        $dbiScore = isset($jsonResponse['dbi']) ? round($jsonResponse['dbi'], 4) : 0; // Nilai DBI

        // 6. Olah Hasil Clustering (Gunakan Array Cluster saja)
        list($hasilClusterTabel, $hasilClusterChart) = $this->formatHasilFlask($hasilClusterRaw);

        // 7. Simpan Session (Simpan juga DBI untuk ditampilkan di view)
        Session::put('hasil_clustering', [
            'tabel' => $hasilClusterTabel,
            'chart' => $hasilClusterChart,
            'dbi' => $dbiScore
        ]);

        // 8. Simpan Cache (Untuk Mobile API)
        Cache::put('latest_map_clustering', $hasilClusterChart, now()->addHours(24));

        return redirect()->route('cluster');
    }

    public function resetCluster()
    {
        Session::forget('hasil_clustering');
        return redirect()->route('cluster')->with('success', 'Hasil clustering telah direset.');
    }

    private function formatHasilFlask($hasilFlask)
    {
        // === 1. Analisis Skor Keparahan (Menentukan Level C1/C2/C3) ===
        $clusterStats = [];

        foreach ($hasilFlask as $cluster_id => $items) {
            $cid = (int) $cluster_id;
            if ($cid == -1)
                continue;

            $count = count($items);
            $totalRugi = 0;
            foreach ($items as $item)
                $totalRugi += ($item['rugi'] ?? 0);

            $clusterStats[] = [
                'id' => $cid,
                'score' => ($count * 1000000000) + $totalRugi
            ];
        }

        // Urutkan (Score Rendah -> Tinggi)
        usort($clusterStats, fn($a, $b) => $a['score'] <=> $b['score']);

        // === 2. Mapping ID -> Level (0,1,2) ===
        $mapping = [];
        $totalClusters = count($clusterStats);

        if ($totalClusters > 0) {
            $chunkSize = ceil($totalClusters / 3);
            foreach ($clusterStats as $index => $stat) {
                if ($totalClusters <= 3)
                    $level = $index;
                else {
                    $level = floor($index / $chunkSize);
                    if ($level > 2)
                        $level = 2;
                }
                $mapping[$stat['id']] = $level;
            }
        }

        // === 3. Proses Data Tabel & Detail (PERBAIKAN UTAMA) ===

        $agregat = []; // [BENAR] Inisialisasi HARUS DI LUAR foreach
        $levelLabels = ['C1 (Aman)', 'C2 (Sedang)', 'C3 (Rawan)'];

        foreach ($hasilFlask as $cluster_id => $items) {
            $cid = (int) $cluster_id;

            // [SALAH] HAPUS BARIS INI: $agregat = []; 
            // [SALAH] HAPUS BARIS INI: $levelLabels = [...];

            // Tentukan Level
            if ($cid == -1) {
                $lvlCode = -1;
                $labelStr = 'NOISE';
            } else {
                $lvlCode = $mapping[$cid] ?? 0;
                $labelStr = $levelLabels[$lvlCode] ?? 'C1';
            }

            foreach ($items as $point) {
                $kecamatan = $point['kecamatan'] ?? 'Unknown';
                $key = $kecamatan . '_lvl_' . $lvlCode;

                if (!isset($agregat[$key])) {
                    $agregat[$key] = [
                        'kecamatan' => $kecamatan,
                        'jumlah_kejadian' => 0,
                        'cluster_id' => $lvlCode,
                        'cluster_label' => $labelStr,
                        'list_detail' => [] // Siapkan array detail
                    ];
                }

                $agregat[$key]['jumlah_kejadian']++;

                // Masukkan rincian kejadian
                $agregat[$key]['list_detail'][] = [
                    'jenis' => $point['jenis'] ?? '-',
                    'rugi' => $point['rugi'] ?? 0,
                    'lat' => $point['lat'],
                    'lng' => $point['lon']
                ];
            }
        }

        // Finalisasi Data Tabel
        $tabel = array_values($agregat);
        usort($tabel, fn($a, $b) => strcmp($a['kecamatan'], $b['kecamatan']));

        // === 4. Proses Data Visualisasi (Chart) ===
        $groupedPoints = [];
        $chartResult = [];
        $colors = [-1 => '#858796', 0 => '#1cc88a', 1 => '#f6c23e', 2 => '#e74a3b'];

        foreach ($hasilFlask as $cluster_id => $items) {
            $cid = (int) $cluster_id;
            $lvl = ($cid == -1) ? -1 : ($mapping[$cid] ?? 0);

            foreach ($items as $p) {
                // Struktur lengkap untuk Chart.js & Map
                $groupedPoints[$lvl][] = [
                    'x' => $p['lon'],      // Chart
                    'y' => $p['lat'],      // Chart
                    'lat' => $p['lat'],    // Map
                    'lng' => $p['lon'],    // Map
                    'kecamatan' => $p['kecamatan'] ?? '-',
                    'jenis' => $p['jenis'] ?? '-',
                    'rugi' => $p['rugi'] ?? 0
                ];
            }
        }

        foreach ($groupedPoints as $lvl => $points) {
            if ($lvl == -1)
                $lText = 'NOISE';
            elseif ($lvl == 0)
                $lText = 'C1 (Aman)';
            elseif ($lvl == 1)
                $lText = 'C2 (Sedang)';
            else
                $lText = 'C3 (Rawan)';

            $chartResult[] = [
                'level' => $lvl,
                'label' => $lText,
                'backgroundColor' => $colors[$lvl] ?? '#000',
                'data' => $points
            ];
        }

        // Urutkan Chart
        usort($chartResult, fn($a, $b) => $a['level'] <=> $b['level']);

        return [$tabel, $chartResult];
    }
}