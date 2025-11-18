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

        $data_kriminal = DataKriminal::all(['kecamatan', 'latitude', 'longitude']);

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
            $response = Http::timeout(30)->post('http://127.0.0.1:5001/api/dbscan', $dataUntukApi);
        } catch (\Exception $e) {
            return redirect()->route('cluster')->with('error', 'Gagal terhubung ke service clustering. Pastikan API Flask aktif.');
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


    private function formatHasilFlask($hasilFlask, $data_kriminal)
    {
        // === Bagian 1: Siapkan data mentah (SAMA SEPERTI KODE ANDA) ===
        $hasilClusterMentah = []; // Ini adalah variabel sementara
        foreach ($hasilFlask as $cluster_id => $items) {
            if (!is_array($items))
                continue;

            foreach ($items as $point) {
                $kecamatan = $data_kriminal->first(function ($value) use ($point) {
                    return (string) $value->latitude == (string) $point[0] && (string) $value->longitude == (string) $point[1];
                });

                if ($kecamatan) {
                    $hasilClusterMentah[] = [
                        'kecamatan' => $kecamatan->kecamatan,
                        'cluster' => (int) $cluster_id
                    ];
                }
            }
        }

        // === Bagian 2: Lakukan Agregasi / Peringkasan Data di PHP ===
        // Di sini kita akan mengubah data mentah di atas menjadi data ringkasan.
        $hasilAgregat = [];
        foreach ($hasilClusterMentah as $item) {
            // Buat kunci unik untuk setiap kombinasi kecamatan dan cluster
            $key = $item['kecamatan'] . '_cluster_' . $item['cluster'];

            // Jika kunci ini belum ada di hasil ringkasan, buat entri baru
            if (!isset($hasilAgregat[$key])) {
            $hasilAgregat[$key] = [
                'kecamatan' => $item['kecamatan'],
                'jumlah_kejadian' => 0,
                'cluster_id' => $item['cluster'], 
                'cluster_label' => ($item['cluster'] == -1) ? 'Noise' : 'C' . ($item['cluster'] + 1)
            ];
        }


            // Tambahkan (increment) jumlah kejadian untuk kunci tersebut
            $hasilAgregat[$key]['jumlah_kejadian']++;
        }

        // Ubah dari array asosiatif menjadi array numerik standar yang mudah di-loop di Blade
        $hasilClusterTabel = array_values($hasilAgregat);

        // Urutkan hasil akhir berdasarkan nama kecamatan
        usort($hasilClusterTabel, function ($a, $b) {
            return strcmp($a['kecamatan'], $b['kecamatan']);
        });


        // === Bagian 3: Siapkan data untuk Chart (TIDAK PERLU DIUBAH) ===
        // Kode di bawah ini hanya untuk chart dan tidak perlu Anda ubah.
        $hasilClusterChart = [];
        $chartColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69'];

        foreach ($hasilFlask as $cluster_id => $items) {
            if (!is_array($items))
                continue;

            $chart_data = [];
            foreach ($items as $point) {
                $chart_data[] = ['x' => $point[1], 'y' => $point[0]];
            }

            $color = ($cluster_id == -1) ? 'rgba(128, 128, 128, 0.7)' : $chartColors[intval($cluster_id) % count($chartColors)];

            $hasilClusterChart[] = [
                'label' => ($cluster_id == -1) ? 'Noise' : 'Cluster ' . (intval($cluster_id) + 1),
                'data' => $chart_data,
                'backgroundColor' => $color
            ];
        }

        // Kembalikan data tabel yang SUDAH Diringkas dan data chart
        return [$hasilClusterTabel, $hasilClusterChart];
    }
}