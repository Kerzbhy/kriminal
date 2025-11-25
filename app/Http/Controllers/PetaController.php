<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataKriminal; // Pastikan import Model
use Illuminate\Support\Facades\Session; // Import Session

class PetaController extends Controller
{
    public function index()
    {
        $dataPeta = [];
        $isClustered = false;

        // 1. Cek apakah user sudah melakukan proses clustering (Data tersimpan di Session)
        if (Session::has('hasil_clustering')) {
            $hasil = Session::get('hasil_clustering');
            
            // Ambil bagian 'chart' karena di ClusterController kita sudah memformatnya
            // lengkap dengan lat, lng, warna, dan info popup.
            $dataPeta = $hasil['chart']; 
            $isClustered = true;
        } 
        else {
            // 2. Jika BELUM proses cluster, ambil data mentah dari database
            // Kita format manual agar struktur datanya sama dengan hasil clustering
            // Supaya view (Blade) tidak error saat looping.
            
            $raw = DataKriminal::all();
            
            // Format jadi 1 Grup (Grup Data Mentah)
            $dataPeta = [
                [
                    'label' => 'Semua Data Kriminal',
                    'level' => 99, // Kode dummy
                    'backgroundColor' => '#4e73df', // Warna Biru default
                    'data' => $raw->map(function($item) {
                        return [
                            'lat' => $item->latitude,
                            'lng' => $item->longitude,
                            'kecamatan' => $item->kecamatan,
                            'jenis' => $item->jenis_kejahatan,
                            'rugi' => $item->kerugian
                        ];
                    })->toArray()
                ]
            ];
            $isClustered = false;
        }

        // Kirim data ke View
        $data = array(
            'title' => 'Peta Persebaran',
            'menuPeta' => 'active',
            'dataPeta' => $dataPeta,      // Data titik koordinat
            'isClustered' => $isClustered // Status apakah hasil cluster atau bukan
        );

        return view('admin.peta.peta', $data);
    }
}