<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataKriminal;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Kejadian (Card 1)
        $totalKejadian = DataKriminal::count();

        // 2. Total Kerugian (Card 4 - Uang)
        $totalKerugian = DataKriminal::sum('kerugian');

        // 3. Wilayah dengan Kasus Terbanyak (Card 2 - User/Orang ganti Wilayah)
        $wilayahRawan = DataKriminal::select('kecamatan')
            ->groupBy('kecamatan')
            ->orderByRaw('COUNT(*) DESC')
            ->first();
        $namaWilayahRawan = $wilayahRawan ? $wilayahRawan->kecamatan : '-';

        // 4. Jenis Kejahatan Dominan (Card 3)
        $jenisDominan = DataKriminal::select('jenis_kejahatan')
            ->groupBy('jenis_kejahatan')
            ->orderByRaw('COUNT(*) DESC')
            ->first();
        $namaJenisDominan = $jenisDominan ? $jenisDominan->jenis_kejahatan : '-';

        // 5. Data untuk Grafik (Statistik per Kecamatan)
        $dataGrafik = DataKriminal::select('kecamatan', DB::raw('count(*) as total'))
            ->groupBy('kecamatan')
            ->get();

        // Pisahkan Label dan Value untuk Chart.js
        $grafikLabels = $dataGrafik->pluck('kecamatan');
        $grafikValues = $dataGrafik->pluck('total');

        return view('dashboard', [
            'menuDashboard' => 'active', // Untuk sidebar active
            'title' => 'Dashboard',
            'totalKejadian' => $totalKejadian,
            'totalKerugian' => $totalKerugian,
            'namaWilayahRawan' => $namaWilayahRawan,
            'namaJenisDominan' => $namaJenisDominan,
            'grafikLabels' => $grafikLabels,
            'grafikValues' => $grafikValues
        ]);
    }
}