<?php

namespace App\Http\Controllers\Api;

use App\Models\BarangModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BarangController extends Controller
{
    public function index()
    {
        return BarangModel::all();
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');

            // Melakukan Hash Name pada file
            $filename = $file->hashName();

            $file->storeAs('images', $filename, 'public');

            $data['image'] = 'images/' . $filename;
        }

        $barang = BarangModel::create($data);

        return response()->json($barang, 201);
    }



    public function show(BarangModel $barang)
    {
        return $barang;
    }

    public function update(Request $request, BarangModel $barang)
    {
        $barang->update($request->all());
        return $barang;
    }

    public function destroy(BarangModel $barang)
    {
        $barang->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}