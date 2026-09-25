<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $pengguna = $request->user();
        if ($pengguna->peran === 'penyuluh') {
            $pengaduan = Pengaduan::with('pengguna')->get();
        } else {
            $pengaduan = Pengaduan::where('pengguna_id', $pengguna->id)->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data pengaduan berhasil diambil.',
            'data' => $pengaduan
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|in:infrastruktur,pupuk,hama,layanan_publik',
            'lokasi_kebun' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $path = null;
        if ($request->hasFile('foto_bukti')) {
            $path = $request->file('foto_bukti')->store('unggahan', 'public');
        }

        $pengaduan = Pengaduan::create([
            'pengguna_id' => $request->user()->id,
            'kategori' => $request->kategori,
            'lokasi_kebun' => $request->lokasi_kebun,
            'deskripsi' => $request->deskripsi,
            'foto_bukti' => $path,
            'status' => 'terkirim',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil dikirim.',
            'data' => $pengaduan
        ], 201);
    }

    public function show($id, Request $request)
    {
        $pengaduan = Pengaduan::with('pengguna')->find($id);

        if (!$pengaduan) {
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan tidak ditemukan.',
            ], 404);
        }

        if ($request->user()->peran === 'petani' && $pengaduan->pengguna_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak memiliki akses ke pengaduan ini.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pengaduan berhasil diambil.',
            'data' => $pengaduan
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        if ($request->user()->peran !== 'penyuluh') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya penyuluh yang dapat memperbarui status pengaduan.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:terkirim,diproses,ditindaklanjuti,selesai',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $pengaduan = Pengaduan::find($id);

        if (!$pengaduan) {
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan tidak ditemukan.',
            ], 404);
        }

        $pengaduan->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pengaduan berhasil diperbarui.',
            'data' => $pengaduan
        ], 200);
    }
}
