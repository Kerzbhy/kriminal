<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Data Kriminal',
            'menuData' => 'active',
            'data_kriminal' => Data::all(),
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
            'lokasi' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'total_kejadian' => 'required|integer',
            'jenis_kejadian' => 'required|string',
            'avg_kerugian' => 'required|numeric',
            'jumlah_penduduk' => 'required|integer',
        ]);

        Data::create($request->all());

        return redirect()->route('data.data')->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data = Data::findOrFail($id);
        return view('admin.data.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lokasi' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'total_kejadian' => 'required|integer',
            'jenis_kejadian' => 'required|string',
            'avg_kerugian' => 'required|numeric',
            'jumlah_penduduk' => 'required|integer',
        ]);

        $data = Data::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('data.data')->with('success', 'Data berhasil diperbarui!');
    }

        public function destroy($id)
    {
        $data = Data::findOrFail($id);
        $data->delete();

        return redirect()->route('data.data')->with('success', 'Data berhasil dihapus.');
    }

}