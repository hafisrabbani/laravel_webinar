<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{

    // Untuk Insert Data ke database
    public function tambah(Request $request)
    {
        // Validasi Request
        $validData = Validator::make($request->only(['nama', 'nrp', 'email', 'jurusan']), [
            'nama' => 'required|string',
            'nrp' =>   'required|integer|unique:mahasiswas,nrp',
            'email' => 'required|email|unique:mahasiswas,email',
            'jurusan' => 'required|string'
        ], [
            'nama.required' => 'Nama Wajib Diisi',
        ]);

        // Cek apakah ada yang error?
        if ($validData->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validData->errors(),
            ], 400);
        }

        // masukan ke database
        $insert = Mahasiswa::create($validData->validated());
        // Kembalikan respon
        return response()->json([
            'status' => 'success',
            'message' => 'data berhasil ditambahkan',
            'data' => $insert
        ], 200);
    }

    public function index()
    {
        // ambil semua data di database
        $data = Mahasiswa::get();

        return response()->json([
            'status' => 'success',
            'message' => ($data->isEmpty()) ? 'No records found' : 'success get data',
            'data' => ($data->isEmpty()) ? [] : $data
        ], 200);
    }

    public function show($id)
    {
        $data = Mahasiswa::find($id);

        return response()->json([
            'status' => 'success',
            'message' => 'succes get data',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi Request
        $validData = Validator::make($request->only(['nama', 'nrp', 'email', 'jurusan']), [
            'nama' => 'required|string',
            'nrp' =>   'required|integer|unique:mahasiswas,nrp,' . $id,
            'email' => 'required|email|unique:mahasiswas,email,' . $id,
            'jurusan' => 'required|string'
        ]);

        // Cek apakah ada yang error?
        if ($validData->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validData->errors(),
            ], 400);
        }

        $update = Mahasiswa::find($id);
        if (!$update) {
            return response()->json([
                'status' => 'error',
                'message' => 'tidak ditemukan',
            ], 400);
        }

        $data = $update->update($validData->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ]);
    }

    public function delete($id)
    {
        $delete = Mahasiswa::find($id);

        if (!$delete) {
            return response()->json([
                'status' => 'error',
                'message' => 'tidak ditemukan',
            ], 400);
        }

        $delete = $delete->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
            'data' =>  $delete
        ]);
    }
}
