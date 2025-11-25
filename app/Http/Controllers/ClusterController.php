<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataKriminal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

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
        $validator = Validator::make($request->all(), [
            'eps' => 'required|numeric|min:0.001',
            'min_samples' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->route('cluster')
                ->withErrors($validator)
                ->withInput();
        }

        // 1. AMBIL DATA LENGKAP (Sertakan ID, Jenis, Kerugian)
        $data_kriminal = DataKriminal::all(['id', 'kecamatan', 'latitude', 'longitude', 'jenis_kejahatan', 'kerugian']);

        if ($data_kriminal->isEmpty()) {
            return redirect()->route('cluster')->with('error', 'Tidak ada data kriminal di database.');
        }

        // 2. Format Data untuk dikirim ke Python (Bentuk Array of Objects)
        $points = $data_kriminal->map(function ($item) {
            return [
                'id' => $item->id, // Penting untuk mapping balik
                'kecamatan' => $item->kecamatan,
                'lat' => (float) $item->latitude,
                'lon' => (float) $item->longitude,
                'jenis' => $item->jenis_kejahatan,
                'rugi' => (float) $item->kerugian
            ];
        })->toArray();

        $dataUntukApi = [
            'data' => $points, // Ubah key jadi 'data' biar lebih umum
            'eps' => (float) $request->input('eps'),
            'min_samples' => (int) $request->input('min_samples'),
        ];

        try {
            $response = Http::timeout(60)->post('http://127.0.0.1:5001/api/dbscan', $dataUntukApi);
        } catch (\Exception $e) {
            return redirect()->route('cluster')->with('error', 'Gagal terhubung ke service API Flask.');
        }

        if (!$response->successful()) {
            return redirect()->route('cluster')->with('error', 'API Error: ' . $response->status());
        }

        $hasilFlask = $response->json();

        // 3. Olah hasil menggunakan metode baru (Lookup by ID)
        list($hasilClusterTabel, $hasilClusterChart) = $this->formatHasilFlask($hasilFlask);

        Session::put('hasil_clustering', [
            'tabel' => $hasilClusterTabel,
            'chart' => $hasilClusterChart
        ]);

        return redirect()->route('cluster');
    }

    public function resetCluster()
    {
        Session::forget('hasil_clustering');
        return redirect()->route('cluster')->with('success', 'Hasil clustering telah direset.');
    }

    private function formatHasilFlask($hasilFlask)
    {
        // === Format Data Tabel (Di-group per Kecamatan & Cluster) ===
        $agregat = [];

        foreach ($hasilFlask as $cluster_id => $items) {
            if (!is_array($items))
                continue;

            foreach ($items as $point) {
                // Di Python kita mengirim balik object lengkap, jadi kita ambil kecamatannya
                $kecamatan = $point['kecamatan'] ?? 'Unknown';

                // Key unik: Gabungan Nama Kecamatan + ID Cluster
                $key = $kecamatan . '_' . $cluster_id;

                if (!isset($agregat[$key])) {
                    $agregat[$key] = [
                        'kecamatan' => $kecamatan,
                        'jumlah_kejadian' => 0,
                        'cluster_id' => (int) $cluster_id,
                        'cluster_label' => ((int) $cluster_id === -1) ? 'NOISE' : 'C' . ((int) $cluster_id + 1),
                        // Bisa tambahkan total kerugian per cluster jika mau
                    ];
                }
                $agregat[$key]['jumlah_kejadian']++;
            }
        }

        // Ratakan array & urutkan nama
        $tabel = array_values($agregat);
        usort($tabel, function ($a, $b) {
            return strcmp($a['kecamatan'], $b['kecamatan']);
        });

        // === Format Data Chart Scatter Plot ===
        // Note: Chart Lat/Long visualnya mungkin jadi agak 'aneh' karena
        // titik yang berjauhan secara visual bisa satu warna cluster karena Kerugian-nya mirip.

        $chartResult = [];
        $colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6f42c1', '#fd7e14'];

        foreach ($hasilFlask as $cluster_id => $items) {
            if (!is_array($items))
                continue;
            $cluster_id = (int) $cluster_id;

            $dataPoints = [];
            foreach ($items as $p) {
                $dataPoints[] = [
                    'x' => $p['lon'],
                    'y' => $p['lat']
                    // Note: Chart.js standard scatter cuma x & y.
                    // Info kerugian/jenis bisa ditambahkan di tooltip pakai Javascript di view.
                ];
            }

            $color = ($cluster_id === -1) ? '#858796' : $colors[$cluster_id % count($colors)]; // Abu-abu untuk noise

            $chartResult[] = [
                'label' => ($cluster_id === -1) ? 'Noise' : 'Cluster ' . ($cluster_id + 1),
                'data' => $dataPoints,
                'backgroundColor' => $color
            ];
        }

        return [$tabel, $chartResult];
    }
}