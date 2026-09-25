<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaduan;

class AdminPengaduanController extends Controller
{
    public function index()
    {
        $pengaduan = Pengaduan::orderBy("created_at", "desc")->get();
        return view("admin.pengaduan.index", compact("pengaduan"));
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        return view("admin.pengaduan.show", compact("pengaduan"));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            "status" => "required|in:menunggu,diproses,selesai,ditolak",
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->status = $request->status;
        $pengaduan->save();

        return redirect()->route("admin.pengaduan.index")->with("success", "Status pengaduan berhasil diperbarui!");
    }
}
