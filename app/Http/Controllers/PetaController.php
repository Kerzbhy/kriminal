<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index(){
        $data = array(
            'title' => 'Peta',
            'menuPeta' => 'active'
        );

        return view('admin/peta/index', $data);
    }
}
