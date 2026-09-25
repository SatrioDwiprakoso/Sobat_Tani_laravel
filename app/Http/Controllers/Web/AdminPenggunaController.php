<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Pengguna;

class AdminPenggunaController extends Controller {
    public function index() {
        $pengguna = Pengguna::orderBy("created_at", "desc")->get(); 
        return view("admin.pengguna.index", compact("pengguna"));
    }
    public function store(\Illuminate\Http\Request $request) {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'kata_sandi' => 'required|min:6',
            'peran' => 'required|in:admin,penyuluh',
        ]);

        Pengguna::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'kata_sandi' => \Illuminate\Support\Facades\Hash::make($request->kata_sandi),
            'peran' => $request->peran,
        ]);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }
}


