<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrioritasController extends Controller
{
    public function index(){
        $data = array(
            'title' => 'Prioritas',
            'menuPrioritas' => 'active'
        );

        return view('admin/prioritas/prioritas', $data);
    }
}
