<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Models\DataKriminal;


class DashboardController extends Controller
{
    public function index(){
        $totalKejadian = DataKriminal::sum('jumlah_kejadian');
        $data = array(
            "title"             => "Dashboard",
            "menuDashboard"    => "active",
            'totalKejadian' => $totalKejadian
        );
        return view('dashboard',$data);
    }
}
