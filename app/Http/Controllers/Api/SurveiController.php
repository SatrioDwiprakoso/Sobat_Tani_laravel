<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SurveiKepuasan;
use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SurveiController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'konsultasi_id' => 'required|exists:konsultasi,id',
            'rating' => 'required|integer|min:1|max:5',
            'kriteria_penilaian' => 'required|array',
            'catatan_evaluasi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $konsultasi = Konsultasi::find($request->konsultasi_id);
        
        if ($konsultasi->pengguna_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pembuat konsultasi yang dapat mengisi survei.',
            ], 403);
        }

        if ($konsultasi->status !== 'selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Konsultasi harus dalam status selesai untuk dapat disurvei.',
            ], 400);
        }

        // Cek apakah sudah disurvei
        $surveiAda = SurveiKepuasan::where('konsultasi_id', $request->konsultasi_id)->first();
        if ($surveiAda) {
            return response()->json([
                'success' => false,
                'message' => 'Survei untuk konsultasi ini sudah diisi.',
            ], 400);
        }

        $survei = SurveiKepuasan::create([
            'pengguna_id' => $request->user()->id,
            'konsultasi_id' => $request->konsultasi_id,
            'rating' => $request->rating,
            'kriteria_penilaian' => $request->kriteria_penilaian,
            'catatan_evaluasi' => $request->catatan_evaluasi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Survei kepuasan berhasil disimpan.',
            'data' => $survei
        ], 201);
    }

    public function ringkasanSurvei(Request $request)
    {
        if ($request->user()->peran !== 'penyuluh') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya penyuluh yang dapat melihat ringkasan survei.',
            ], 403);
        }

        $rataRataRating = SurveiKepuasan::avg('rating');
        $totalSurvei = SurveiKepuasan::count();

        return response()->json([
            'success' => true,
            'message' => 'Ringkasan survei berhasil diambil.',
            'data' => [
                'rata_rata_rating' => round($rataRataRating, 2),
                'total_survei' => $totalSurvei,
            ]
        ], 200);
    }
}
