<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class PrioritasController extends Controller
{
    // ==========================================
    // 1. HALAMAN UTAMA (INDEX)
    // ==========================================
    public function index()
    {
        // A. Ambil Data Agregat (Tabel Kriteria)
        $dataAgregat = $this->getAggregatedData();

        // B. Cek Apakah ada hasil hitungan TOPSIS tersimpan di Session?
        $hasilTopsis = Session::get('hasil_topsis', null); // Default null
        $oldInputs = Session::get('topsis_inputs', []);    // Untuk mengisi ulang form bobot

        $data = [
            'title' => 'Prioritas Wilayah (TOPSIS)',
            'menuPrioritas' => 'active',
            'dataAgregat' => $dataAgregat,
            'hasil_topsis' => $hasilTopsis, // Kirim hasil yang disimpan di session
            'old_inputs' => $oldInputs      // Kirim input bobot sebelumnya agar tidak mengetik ulang
        ];

        return view('admin.prioritas.prioritas', $data);
    }

    // ==========================================
    // 2. PROSES HITUNG TOPSIS
    // ==========================================
    public function prosesTopsis(Request $request)
    {
        // A. Validasi Data Awal
        $dataAgregat = $this->getAggregatedData();
        if (empty($dataAgregat)) {
            return redirect()->route('prioritas')->with('error', 'Data Clustering kosong.');
        }

        // B. Persiapkan Matrix
        $matrix = [];
        $alternativesNames = [];
        foreach ($dataAgregat as $row) {
            $alternativesNames[] = $row['kecamatan'];
            // Pastikan mengambil C2 yang ANGKA
            $matrix[] = [(float) $row['C1'], (float) $row['C2'], (float) $row['C3'], (float) $row['C4']];
        }

        // C. Ambil Parameter dari Form
        $weights = array_map('floatval', $request->input('weights'));
        $impacts = array_map('intval', $request->input('impacts'));

        // D. Hitung via API Python / Internal
        try {
            // Pastikan Flask/API Anda sudah berjalan
            $response = Http::timeout(10)->post('http://127.0.0.1:5001/api/topsis', [
                'matrix' => $matrix,
                'weights' => $weights,
                'impacts' => $impacts
            ]);

            if ($response->failed()) {
                return back()->with('error', 'API Error: ' . $response->status());
            }

            $result = $response->json();

        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi API Gagal: ' . $e->getMessage());
        }

        // E. Lengkapi Data Hasil
        $result['alternatives'] = $alternativesNames;

        // F. === PERUBAHAN UTAMA: SIMPAN KE SESSION ===
        Session::put('hasil_topsis', $result);

        // (Opsional) Simpan inputan user agar form terisi otomatis
        Session::put('topsis_inputs', [
            'weights' => $weights,
            'impacts' => $impacts
        ]);

        // G. Redirect kembali ke index (PRG Pattern)
        // Data akan diambil oleh method index() dari session
        return redirect()->route('prioritas')->with('success', 'Perhitungan Selesai!');
    }

    // ==========================================
    // 3. FITUR RESET HASIL
    // ==========================================
    public function resetTopsis()
    {
        Session::forget('hasil_topsis');
        Session::forget('topsis_inputs');
        return redirect()->route('prioritas');
    }

    // ==========================================
    // 4. HELPER FUNCTIONS
    // ==========================================
    private function getAggregatedData()
    {
        if (Session::has('hasil_clustering')) {
            $sessionData = Session::get('hasil_clustering');
            $rawPoints = [];

            if (isset($sessionData['chart']) && is_array($sessionData['chart'])) {
                foreach ($sessionData['chart'] as $group) {
                    // Filter Noise (-1)
                    if (isset($group['level']) && (int) $group['level'] === -1)
                        continue;

                    if (isset($group['data'])) {
                        foreach ($group['data'] as $point)
                            $rawPoints[] = $point;
                    }
                }
            }
            return $this->gabungDataPerKecamatan($rawPoints);
        }
        return [];
    }

    private function gabungDataPerKecamatan($points)
    {
        $dataKepadatan = ['Abeli' => 1200, 'Baruga' => 2800, 'Kadia' => 4500, 'Kambu' => 3200, 'Kendari Barat' => 5200, 'Mandonga' => 4800, 'Poasia' => 3100, 'Puuwatu' => 2500, 'Ranomeeto' => 1500, 'Wua-Wua' => 4100];
        $skorKejahatan = ['Pembunuhan' => 4, 'Pelecehan Anak' => 4, 'Penganiayaan' => 3, 'Penipuan' => 2, 'Pencurian' => 2];

        $stats = [];
        foreach ($points as $p) {
            $kec = $p['kecamatan'] ?? '-';
            if ($kec === '-' || $kec === '')
                continue;

            if (!isset($stats[$kec])) {
                $stats[$kec] = ['kecamatan' => $kec, 'total_kasus' => 0, 'list_jenis' => [], 'total_rugi' => 0];
            }
            $stats[$kec]['total_kasus']++;
            $stats[$kec]['total_rugi'] += ($p['rugi'] ?? 0);
            if (!empty($p['jenis']))
                $stats[$kec]['list_jenis'][] = $p['jenis'];
        }

        $finalData = [];
        foreach ($stats as $kec => $data) {
            if (!empty($data['list_jenis'])) {
                $counts = array_count_values($data['list_jenis']);
                arsort($counts);
                $dominanText = array_key_first($counts);
            } else {
                $dominanText = '-';
            }

            $formatText = ucwords(strtolower($dominanText));
            $skorC2 = $skorKejahatan[$formatText] ?? 1;

            $avgRugi = ($data['total_kasus'] > 0) ? $data['total_rugi'] / $data['total_kasus'] : 0;
            $kepadatan = $dataKepadatan[$kec] ?? 1000;

            $finalData[] = [
                'kecamatan' => $kec,
                'C1' => $data['total_kasus'],
                'C2' => $skorC2,
                'C2_info' => $dominanText,
                'C3' => $avgRugi,
                'C4' => $kepadatan
            ];
        }
        usort($finalData, fn($a, $b) => strcmp($a['kecamatan'], $b['kecamatan']));
        return $finalData;
    }
}