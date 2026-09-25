<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Konsultasi;
use App\Models\PesanKonsultasi;
use Illuminate\Support\Facades\Session;
use App\Events\PesanKonsultasiBaru;

class AdminKonsultasiController extends Controller
{
    public function index()
    {
        $konsultasi = Konsultasi::with('pengguna')->orderBy("created_at", "desc")->get();
        return view("admin.konsultasi.index", compact("konsultasi"));
    }

    public function show($id)
    {
        $konsultasi = Konsultasi::with(['pengguna', 'pesan.pengguna'])->findOrFail($id);
        return view("admin.konsultasi.show", compact("konsultasi"));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            "tanggapan_ahli" => "required|string",
        ]);

        $konsultasi = Konsultasi::findOrFail($id);
                $pesanBaru = PesanKonsultasi::create([
            'konsultasi_id' => $konsultasi->id,
            'pengguna_id' => Session::get('admin_id'),
            'pesan' => $request->tanggapan_ahli,
        ]);

        broadcast(new PesanKonsultasiBaru($pesanBaru));

        $konsultasi->status = "dijawab";
        // Backward compatibility for old API
        $konsultasi->tanggapan_ahli = $request->tanggapan_ahli;
        $konsultasi->save();

        return redirect()->back()->with("success", "Tanggapan berhasil dikirim!");
    }
}
