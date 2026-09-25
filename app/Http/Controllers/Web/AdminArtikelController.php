<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel;
use Illuminate\Support\Facades\Session;

class AdminArtikelController extends Controller
{
    public function index()
    {
        $artikels = Artikel::orderBy('created_at', 'desc')->get();
        return view('admin.artikel.index', compact('artikels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'gambar_url' => 'nullable|url'
        ]);

        $ringkasan = $request->ringkasan;
        if (!$ringkasan) {
            $ringkasan = substr(strip_tags($request->isi), 0, 100) . '...';
        }

        Artikel::create([
            'judul' => $request->judul,
            'ringkasan' => $ringkasan,
            'isi' => $request->isi,
            'gambar_url' => $request->gambar_url,
            'penulis' => Session::get('admin_name', 'Admin Sobat Tani'),
        ]);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diterbitkan!');
    }

    public function update(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'gambar_url' => 'nullable|url'
        ]);

        $ringkasan = $request->ringkasan;
        if (!$ringkasan) {
            $ringkasan = substr(strip_tags($request->isi), 0, 100) . '...';
        }

        $artikel->update([
            'judul' => $request->judul,
            'ringkasan' => $ringkasan,
            'isi' => $request->isi,
            'gambar_url' => $request->gambar_url,
        ]);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);
        $artikel->delete();
        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus!');
    }
}
