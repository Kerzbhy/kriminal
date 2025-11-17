<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataKriminal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session; // <-- TAMBAHKAN 'use' UNTUK SESSION

class ClusterController extends Controller
{
    /**
     * Menampilkan halaman clustering.
     * Method ini akan memeriksa session terlebih dahulu.
     */
    public function index()
    {
        // PERIKSA APAKAH ADA DATA HASIL DI SESSION
        if (Session::has('hasil_clustering')) {
            // Jika ada, ambil data dari session
            $hasil = Session::get('hasil_clustering');
            $data = [
                'title' => 'Hasil Clustering',
                'menuCluster' => 'active',
                'hasilClusterTabel' => $hasil['tabel'],
                'hasilClusterChart' => $hasil['chart'],
            ];
        } else {
            // Jika tidak ada session (kondisi awal), kirim data kosong
            $data = [
                'title' => 'Clustering',
                'menuCluster' => 'active',
                'hasilClusterTabel' => null,
                'hasilClusterChart' => null
            ];
        }

        return view('admin.cluster.cluster', $data);
    }

    /**
     * Menerima request dari form, memproses data, memanggil API Flask, 
     * dan MENYIMPAN HASILNYA KE SESSION.
     */
    public function prosesCluster(Request $request)
    {
        // Bagian Validasi, Pengambilan Data, dan Pemanggilan API tetap sama
        $validator = Validator::make($request->all(), [
            'eps' => 'required|numeric|min:0.001',
            'min_samples' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->route('cluster')
                ->withErrors($validator)
                ->withInput();
        }

        $data_kriminal = DataKriminal::all(['lokasi', 'latitude', 'longitude']);

        if ($data_kriminal->isEmpty()) {
            return redirect()->route('cluster')->with('error', 'Tidak ada data kriminal di database untuk diproses.');
        }

        $points = $data_kriminal->map(function ($item) {
            return [(float) $item->latitude, (float) $item->longitude];
        })->toArray();

        $dataUntukApi = [
            'points' => $points,
            'eps' => (float) $request->input('eps'),
            'min_samples' => (int) $request->input('min_samples'),
        ];

        try {
            $response = Http::timeout(30)->post('http://127.0.0.1:5000/api/dbscan', $dataUntukApi);
        } catch (\Exception $e) {
            return redirect()->route('cluster')->with('error', 'Gagal terhubung ke service clustering. Pastikan API Flask aktif. Error: ' . $e->getMessage());
        }

        if (!$response->successful()) {
            return redirect()->route('cluster')->with('error', 'Terjadi kesalahan pada service clustering (API). Status: ' . $response->status());
        }

        $hasilFlask = $response->json();

        if (empty($hasilFlask) || !is_array($hasilFlask)) {
            return redirect()->route('cluster')->with('error', 'Service clustering tidak mengembalikan hasil yang valid.');
        }

        // Olah hasil dari Flask
        list($hasilClusterTabel, $hasilClusterChart) = $this->formatHasilFlask($hasilFlask, $data_kriminal);

        // -- PERUBAHAN UTAMA ADA DI SINI --
        // SIMPAN HASIL KE DALAM SESSION, BUKAN MENGIRIM LANGSUNG KE VIEW
        Session::put('hasil_clustering', [
            'tabel' => $hasilClusterTabel,
            'chart' => $hasilClusterChart
        ]);

        // KEMUDIAN, REDIRECT KEMBALI KE HALAMAN INDEX
        // Method 'index' nanti yang akan membaca session ini.
        return redirect()->route('cluster');
    }

    /**
     * METHOD BARU UNTUK MERESET HASIL
     * Menghapus hasil clustering dari session.
     */
    public function resetCluster()
    {
        // Hapus kunci 'hasil_clustering' dari session
        Session::forget('hasil_clustering');

        // Redirect kembali ke halaman cluster dengan pesan sukses
        return redirect()->route('cluster')->with('success', 'Hasil clustering telah direset.');
    }


    /**
     * Helper function untuk format data (TETAP SAMA, TIDAK PERLU DIUBAH).
     */
    private function formatHasilFlask($hasilFlask, $data_kriminal)
    {
        $hasilClusterTabel = [];
        $hasilClusterChart = [];
        $chartColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69'];

        foreach ($hasilFlask as $cluster_id => $items) {
            if (!is_array($items))
                continue;

            // --- MULAI LOGIKA UNTUK GRAFIK YANG HILANG ---
            $chart_data = [];
            foreach ($items as $point) {
                // $point adalah array [latitude, longitude]
                // Untuk Chart.js scatter, formatnya adalah { x: longitude, y: latitude }
                $chart_data[] = ['x' => $point[1], 'y' => $point[0]];
            }
            // --- SELESAI LOGIKA YANG HILANG ---

            // Pilih warna berdasarkan cluster ID
            $color = ($cluster_id == -1) ? 'rgba(128, 128, 128, 0.7)' : $chartColors[intval($cluster_id) % count($chartColors)];

            // Sekarang masukkan $chart_data yang sudah terisi
            $hasilClusterChart[] = [
                'label' => ($cluster_id == -1) ? 'Noise' : 'Cluster ' . (intval($cluster_id) + 1),
                'data' => $chart_data, // <-- $chart_data sudah berisi data
                'backgroundColor' => $color
            ];

            // Logika untuk tabel (ini sudah benar)
            foreach ($items as $point) {
                $lokasi = $data_kriminal->first(function ($value) use ($point) {
                    return (string) $value->latitude == (string) $point[0] && (string) $value->longitude == (string) $point[1];
                });

                if ($lokasi) {
                    $hasilClusterTabel[] = [
                        'lokasi' => $lokasi->lokasi,
                        'cluster' => (int) $cluster_id
                    ];
                }
            }
        }

        // Logika sorting tabel (ini sudah benar)
        usort($hasilClusterTabel, function ($a, $b) {
            // ...
        });

        return [$hasilClusterTabel, $hasilClusterChart];
    }
}