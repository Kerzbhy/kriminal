<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // Import Cache

class HomeController extends Controller
{
    public function index()
    {
        // 1. AMBIL DATA MAP (CLUSTERING) DARI CACHE
        $cachedMap = Cache::get('latest_map_clustering', []); // Default array kosong
        
        $mapData = [];
        if ($cachedMap) {
            foreach ($cachedMap as $group) {
                // Sederhanakan data untuk public view
                $level = $group['level'] ?? 99;
                if ($group['data']) {
                    foreach ($group['data'] as $point) {
                        $mapData[] = [
                            'kecamatan' => $point['kecamatan'] ?? '-',
                            'lat' => $point['lat'],
                            'lng' => $point['lng'],
                            'level' => $level, // 0=Aman, 1=Sedang, 2=Rawan
                            'status' => $group['label']
                        ];
                    }
                }
            }
        }

        // 2. AMBIL DATA RANKING (TOPSIS) DARI CACHE
        $hasilTopsis = Cache::get('latest_topsis_result');
        $rankingData = [];

        if ($hasilTopsis) {
            $scores = $hasilTopsis['scores'];
            $alternatives = $hasilTopsis['alternatives'];

            foreach ($scores as $index => $score) {
                $rankingData[] = [
                    'kecamatan' => $alternatives[$index],
                    'skor' => $score
                ];
            }

            // Urutkan Rank 1 di atas
            usort($rankingData, fn($a, $b) => $b['skor'] <=> $a['skor']);
            
            // Ambil Top 5 Saja untuk Landing Page (biar rapi)
            $rankingData = array_slice($rankingData, 0, 5); 
        }

        // Kirim ke View Landing Page
        return view('welcome', [
            'mapData' => $mapData,
            'rankingData' => $rankingData
        ]);
    }
}