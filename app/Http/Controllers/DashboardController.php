<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataKriminal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session; // Import Session

class DashboardController extends Controller
{
    public function index()
    {
        // --- 1. DATA UMUM (Tetap Ambil dari Database) ---
        $totalKejadian = DataKriminal::count();
        $totalKerugian = DataKriminal::sum('kerugian');
        
        // Jenis Dominan
        $jenisDominanData = DataKriminal::select('jenis_kejahatan')
            ->groupBy('jenis_kejahatan')
            ->orderByRaw('COUNT(*) DESC')
            ->first();
        $namaJenisDominan = $jenisDominanData ? $jenisDominanData->jenis_kejahatan : '-';

        // Grafik Bar (Tetap tampilkan jumlah kasus real)
        $dataGrafik = DataKriminal::select('kecamatan', DB::raw('count(*) as total'))
            ->groupBy('kecamatan')->get();
        $grafikLabels = $dataGrafik->pluck('kecamatan');
        $grafikValues = $dataGrafik->pluck('total');


        // --- 2. LOGIKA TOP WILAYAH (TOPSIS vs RAW) ---
        $wilayahRawanName = '-';
        $top5List = [];
        $isTopsisData = false; // Flag penanda status data

        // Cek Session TOPSIS
        if (Session::has('hasil_topsis')) {
            // A. JIKA SUDAH ADA HASIL PERHITUNGAN PRIORITAS
            $hasilTopsis = Session::get('hasil_topsis');
            $alternatives = $hasilTopsis['alternatives'];
            $scores = $hasilTopsis['scores'];

            // Gabungkan Nama dan Skor
            $combined = [];
            foreach ($alternatives as $index => $name) {
                $combined[] = [
                    'kecamatan' => $name,
                    'nilai' => $scores[$index]
                ];
            }

            // Urutkan dari Skor Tertinggi ke Terendah (Ranking)
            usort($combined, function($a, $b) {
                return $b['nilai'] <=> $a['nilai'];
            });

            // Set Data untuk Dashboard
            $wilayahRawanName = $combined[0]['kecamatan']; // Juara 1
            $top5List = array_slice($combined, 0, 5);      // Ambil 5 Besar
            $isTopsisData = true;

        } else {
            // B. JIKA BELUM ADA (Gunakan Data Kasus Terbanyak sebagai Fallback)
            $dbRanking = DataKriminal::select('kecamatan', DB::raw('count(*) as nilai'))
                ->groupBy('kecamatan')
                ->orderByDesc('nilai')
                ->take(5)
                ->get();

            if ($dbRanking->isNotEmpty()) {
                $wilayahRawanName = $dbRanking->first()->kecamatan;
                $top5List = $dbRanking->toArray(); // strukturnya ['kecamatan', 'nilai']
            }
            $isTopsisData = false;
        }

        return view('dashboard', [
            'menuDashboard' => 'active',
            'title' => 'Dashboard',
            'totalKejadian' => $totalKejadian,
            'totalKerugian' => $totalKerugian,
            'namaJenisDominan' => $namaJenisDominan,
            'grafikLabels' => $grafikLabels,
            'grafikValues' => $grafikValues,
            
            // Variable Baru untuk TOPSIS
            'namaWilayahRawan' => $wilayahRawanName,
            'top5List' => $top5List,
            'isTopsisData' => $isTopsisData 
        ]);
    }
}