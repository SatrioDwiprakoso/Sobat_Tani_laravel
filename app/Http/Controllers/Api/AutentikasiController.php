<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AutentikasiController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pengguna',
            'kata_sandi' => 'required|string|min:8',
            'peran' => 'nullable|in:petani,penyuluh',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $pengguna = Pengguna::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'kata_sandi' => Hash::make($request->kata_sandi),
            'peran' => $request->peran ?? 'petani',
        ]);

        $token = $pengguna->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil.',
            'data' => [
                'pengguna' => $pengguna,
                'akses_token' => $token,
                'tipe_token' => 'Bearer'
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'kata_sandi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $pengguna = Pengguna::where('email', $request->email)->first();

        if (!$pengguna || !Hash::check($request->kata_sandi, $pengguna->kata_sandi)) {
            return response()->json([
                'success' => false,
                'message' => 'Kredensial tidak valid.',
            ], 401);
        }

        $token = $pengguna->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'pengguna' => $pengguna,
                'akses_token' => $token,
                'tipe_token' => 'Bearer'
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ], 200);
    }

    public function profil(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data profil berhasil diambil.',
            'data' => $request->user()
        ], 200);
    }

    public function updateProfil(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $pengguna = $request->user();

        if ($request->has('nama_lengkap')) {
            $pengguna->nama_lengkap = $request->nama_lengkap;
        }

        if ($request->has('nomor_telepon')) {
            $pengguna->nomor_telepon = $request->nomor_telepon;
        }

        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('unggahan/profil', 'public');
            $pengguna->foto_profil = $path;
        }

        $pengguna->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => $pengguna
        ], 200);
    }
}