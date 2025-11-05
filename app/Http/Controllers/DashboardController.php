<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Models\Data;

class DashboardController extends Controller
{
    public function index(){
        $totalKejadian = Data::sum('total_kejadian');
        $data = array(
            "title"             => "Dashboard",
            "menuDashboard"    => "active",
            'totalKejadian' => $totalKejadian
        );
        return view('dashboard',$data);
    }
}
