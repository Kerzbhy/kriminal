<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataKriminal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache; // <--- WAJIB IMPORT INI
use App\Models\Kecamatan;

class MobileController extends Controller
{
    // ==========================================
    // 1. API DATA PETA (Titik Cluster)
    // ==========================================
    public function getMapData()
    {
        // A. Coba Ambil Hasil Cluster dari Cache (yg disimpan Web)
        $cachedMap = Cache::get('latest_map_clustering');

        $mobileData = [];

        // KONDISI 1: Admin sudah melakukan clustering di Web
        if ($cachedMap) {
            // Loop per Kelompok (Group Noise, C1, C2, dst)
            foreach ($cachedMap as $group) {

                // Level ini menentukan warna (-1=Noise, 0=C1/Hijau, 1=C2/Kuning, dst)
                $levelId = $group['level'] ?? 99;

                // Nama Status (Aman/Sedang/Rawan)
                $statusLabel = $group['label'] ?? 'Unknown';

                if (isset($group['data'])) {
                    foreach ($group['data'] as $point) {
                        $mobileData[] = [
                            // ID Cluster penting untuk pewarnaan di Flutter
                            'cluster_id' => (int) $levelId,

                            // Data Detail
                            'kecamatan' => $point['kecamatan'] ?? '-',
                            'lat' => $point['lat'],
                            'lng' => $point['lng'],
                            'jenis' => $point['jenis'] ?? '-',
                            'rugi' => $point['rugi'] ?? 0,
                            'status' => $statusLabel
                        ];
                    }
                }
            }
        }

        // KONDISI 2: Cache Kosong (Admin mereset atau belum memproses data)
        // Kita kirim data mentah agar peta di HP tidak kosong melompong
        else {
            $rawPoints = DataKriminal::all();
            foreach ($rawPoints as $item) {
                $mobileData[] = [
                    'cluster_id' => 99, // 99 = Kode warna default (misal Biru/Abu)
                    'kecamatan' => $item->kecamatan,
                    'lat' => (float) $item->latitude,
                    'lng' => (float) $item->longitude,
                    'jenis' => $item->jenis_kejahatan,
                    'rugi' => (float) $item->kerugian,
                    'status' => 'Data Mentah'
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $mobileData
        ]);
    }

    // ==========================================
    // 2. API DATA RANKING (TOPSIS VIA CACHE)
    // ==========================================
    public function getRanking()
    {
        // --- LOGIKA BARU: AMBIL DARI CACHE WEB ---
        // Ini memastikan hasil di HP sama persis dengan Web (karena bobotnya ikut Web)
        $hasilTopsis = Cache::get('latest_topsis_result');

        // Jika Web belum pernah menghitung TOPSIS
        if (!$hasilTopsis) {
            return response()->json([
                'status' => 'empty',
                'message' => 'Admin belum melakukan perhitungan data di Website.',
                'data' => []
            ]);
        }

        // 2. Format ulang data agar mudah dibaca Flutter
        $alternatives = $hasilTopsis['alternatives'];
        $scores = $hasilTopsis['scores'];

        $finalRanking = [];
        foreach ($scores as $index => $score) {
            $finalRanking[] = [
                'kecamatan' => $alternatives[$index] ?? 'Unknown',
                'skor' => round($score, 4), // Bulatkan 4 digit
                // Kita simpan skor mentah untuk sorting di bawah
                'raw_score' => $score
            ];
        }

        // 3. Sorting (Urutkan dari Skor Tertinggi/Rank 1)
        usort($finalRanking, function ($a, $b) {
            return $b['raw_score'] <=> $a['raw_score'];
        });

        // 4. Tambahkan Label Prioritas Setelah Diurutkan
        foreach ($finalRanking as $key => &$item) {
            $rank = $key + 1; // 1, 2, 3...

            // Logika Label: Top 3 Rawan
            if ($rank <= 3) {
                $item['label'] = 'Sangat Prioritas (Rawan)';
            } elseif ($rank <= 6) {
                $item['label'] = 'Prioritas Sedang';
            } else {
                $item['label'] = 'Aman';
            }

            // Hapus raw_score biar output bersih
            unset($item['raw_score']);
        }

        return response()->json([
            'status' => 'success',
            'data' => $finalRanking
        ]);
    }

    // ==========================================
    // 3. API DETAIL PERHITUNGAN PER KECAMATAN
    // ==========================================
    public function getRankingDetail($kecamatan)
    {
        $cache = \Illuminate\Support\Facades\Cache::get('latest_topsis_result');

        if (!$cache) {
            return response()->json(['status' => 'error', 'message' => 'Cache kosong']);
        }

        // 1. Cari Index Kecamatan di dalam Array Alternatives
        $index = array_search($kecamatan, $cache['alternatives']);

        if ($index === false) {
            return response()->json(['status' => 'error', 'message' => 'Kecamatan tidak ditemukan']);
        }

        // 2. Ambil Data Mentah dari Database (Untuk Info Awal C1, C2, C3)
        $rawDB = \App\Models\DataKriminal::where('kecamatan', $kecamatan)->get();

        $totalKasus = $rawDB->count();
        $totalRugi = $rawDB->sum('kerugian');
        $avgRugi = $totalKasus > 0 ? $totalRugi / $totalKasus : 0;

        $listJenis = $rawDB->pluck('jenis_kejahatan')->toArray();
        $counts = array_count_values($listJenis);
        arsort($counts);
        $dominan = array_key_first($counts) ?? '-';

        // === BAGIAN YANG DIPERBAIKI: Ambil Data C4 dari Config ===
        // Mengambil array kepadatan dari file config/kriminal_data.php
        $dataKepadatanArray = config('kriminal_data.kepadatan_penduduk');
        // Mencari nilai untuk kecamatan yang spesifik, beri nilai 0 jika tidak ketemu
        $kepadatanPenduduk = $dataKepadatanArray[$kecamatan] ?? 0;
        // =========================================================

        // 3. Susun Data Detail dari CACHE TOPSIS (Hasil Python)
        $detail = [
            'nama' => $kecamatan,
            // Data Awal
            'raw_data' => [
                'C1 (Kasus)' => $totalKasus,
                'C2 (Dominan)' => $dominan,
                'C3 (Rata Rugi)' => round($avgRugi, 0),
                // === KUNCI JSON BARU UNTUK C4 DITAMBAHKAN ===
                'C4 (Kepadatan)' => $kepadatanPenduduk
            ],

            'normalisasi' => $cache['normalized_matrix'][$index],
            'terbobot' => $cache['weighted_matrix'][$index],

            'jarak_positif' => $cache['dist_positive'][$index],
            'jarak_negatif' => $cache['dist_negative'][$index],

            'skor_akhir' => $cache['scores'][$index]
        ];

        return response()->json([
            'status' => 'success',
            'data' => $detail
        ]);
    }
}