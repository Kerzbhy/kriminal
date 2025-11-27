<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataKriminal; 
use Illuminate\Support\Facades\Session;

class PetaController extends Controller
{
    public function index()
    {
        $dataPeta = [];
        $isClustered = false;

        if (Session::has('hasil_clustering')) {
            $hasil = Session::get('hasil_clustering');
            $dataPeta = $hasil['chart']; 
            $isClustered = true;
        } 
        else {
            $raw = DataKriminal::all();
            
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