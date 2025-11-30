<?php

namespace App\Http\Controllers;

use App\Models\DataKriminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = DataKriminal::query();
        $query->when($search, function ($q, $searchKeyword) {
            return $q->where(DB::raw('LOWER(kecamatan)'), 'like', "%{$searchKeyword}%")
         ->orWhere(DB::raw('LOWER(jenis_kejahatan)'), 'like', "%{$searchKeyword}%");
        });

        $data_kriminal_paginated = $query->latest()->paginate(25);
        $data = [
            'title' => 'Data Kriminal',
            'menuData' => 'active',
            'data_kriminal' => $data_kriminal_paginated,
            'jumlah_data' => DataKriminal::count(),
            'search' => $search
        ];
        return view('admin.data.data', $data);
    }


    public function create()
    {
        $data = [
            'title' => 'Tambah Data Kriminal',
            'menuData' => 'active',
        ];

        return view('admin.data.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kecamatan' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'jenis_kejahatan' => 'required|string',
            'kerugian' => 'required|numeric',
        ]);

        DataKriminal::create($request->all());

        return redirect()->route('data.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data = DataKriminal::findOrFail($id);
        return view('admin.data.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'kecamatan' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'jenis_kejahatan' => 'required|string',
            'kerugian' => 'required|numeric',

        ]);

        $data = DataKriminal::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('data.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $data = DataKriminal::findOrFail($id);
        $data->delete();

        return redirect()->route('data.index')->with('success', 'Data berhasil dihapus.');
    }

}