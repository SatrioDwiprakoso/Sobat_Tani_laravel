<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\PesanKonsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Events\PesanKonsultasiBaru;

class KonsultasiController extends Controller
{
    public function index(Request $request)
    {
        $pengguna = $request->user();
        if ($pengguna->peran === 'penyuluh' || $pengguna->peran === 'admin') {
            $konsultasi = Konsultasi::with(['pengguna', 'pesan.pengguna', 'surveiKepuasan'])->orderBy('created_at', 'desc')->get();
        } else {
            $konsultasi = Konsultasi::with(['pesan.pengguna', 'surveiKepuasan'])->where('pengguna_id', $pengguna->id)->orderBy('created_at', 'desc')->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data konsultasi berhasil diambil.',
            'data' => $konsultasi
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'komoditas' => 'required|in:kakao,kelapa,kopi',
            'judul_keluhan' => 'required|string|max:255',
            'deskripsi_gejala' => 'required|string',
            'tingkat_urgensi' => 'required|in:rendah,sedang,tinggi',
            'foto_tanaman' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $path = null;
        if ($request->hasFile('foto_tanaman')) {
            $path = $request->file('foto_tanaman')->store('unggahan', 'public');
        }

        $konsultasi = Konsultasi::create([
            'pengguna_id' => $request->user()->id,
            'komoditas' => $request->komoditas,
            'judul_keluhan' => $request->judul_keluhan,
            'deskripsi_gejala' => $request->deskripsi_gejala,
            'tingkat_urgensi' => $request->tingkat_urgensi,
            'foto_tanaman' => $path,
            'status' => 'menunggu',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Konsultasi berhasil dibuat.',
            'data' => $konsultasi
        ], 201);
    }

    public function show($id, Request $request)
    {
        $konsultasi = Konsultasi::with(['pengguna', 'pesan.pengguna', 'surveiKepuasan'])->find($id);

        if (!$konsultasi) {
            return response()->json([
                'success' => false,
                'message' => 'Konsultasi tidak ditemukan.',
            ], 404);
        }

        if ($request->user()->peran === 'petani' && $konsultasi->pengguna_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak memiliki akses ke konsultasi ini.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail konsultasi berhasil diambil.',
            'data' => $konsultasi
        ], 200);
    }

    public function kirimPesan(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pesan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$request->filled('pesan') && !$request->hasFile('foto')) {
            return response()->json([
                'success' => false,
                'message' => 'Pesan atau foto harus diisi.',
            ], 422);
        }

        $konsultasi = Konsultasi::find($id);
        if (!$konsultasi) {
            return response()->json([
                'success' => false,
                'message' => 'Konsultasi tidak ditemukan.',
            ], 404);
        }

        if ($konsultasi->status === 'selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Konsultasi ini sudah selesai.',
            ], 400);
        }

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('unggahan', 'public');
        }

        $pesanBaru = PesanKonsultasi::create([
            'konsultasi_id' => $konsultasi->id,
            'pengguna_id' => $request->user()->id,
            'pesan' => $request->pesan,
            'foto' => $path,
        ]);

        broadcast(new PesanKonsultasiBaru($pesanBaru));

        // Jika penyuluh/admin yang balas, update status jadi dijawab
        if ($request->user()->peran !== 'petani') {
            $konsultasi->update(['status' => 'dijawab']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim.',
            'data' => $pesanBaru->load('pengguna')
        ], 201);
    }

    public function tandaiSelesai(Request $request, $id)
    {
        $konsultasi = Konsultasi::find($id);

        if (!$konsultasi) {
            return response()->json([
                'success' => false,
                'message' => 'Konsultasi tidak ditemukan.',
            ], 404);
        }

        $konsultasi->update(['status' => 'selesai']);

        return response()->json([
            'success' => true,
            'message' => 'Konsultasi berhasil ditandai selesai.',
            'data' => $konsultasi
        ], 200);
    }

    public function berikanTanggapan(Request $request, $id)
    {
        if ($request->user()->peran !== 'penyuluh' && $request->user()->peran !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya penyuluh/admin yang dapat memberikan tanggapan.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'tanggapan_ahli' => 'required|string',
            'status' => 'required|in:dijawab,selesai',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $konsultasi = Konsultasi::find($id);

        if (!$konsultasi) {
            return response()->json([
                'success' => false,
                'message' => 'Konsultasi tidak ditemukan.',
            ], 404);
        }

        PesanKonsultasi::create([
            'konsultasi_id' => $konsultasi->id,
            'pengguna_id' => $request->user()->id,
            'pesan' => $request->tanggapan_ahli,
        ]);

        $konsultasi->update([
            'tanggapan_ahli' => $request->tanggapan_ahli,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tanggapan berhasil diberikan.',
            'data' => $konsultasi
        ], 200);
    }
}