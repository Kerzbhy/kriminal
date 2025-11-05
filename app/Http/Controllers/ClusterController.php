<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ClusterController extends Controller
{
      public function index(){

        
        $data = array(
            'title' => 'Clustering',
            'menuCluster' => 'active',
        );
        
        return view('admin/cluster/index',$data);
    }
}
