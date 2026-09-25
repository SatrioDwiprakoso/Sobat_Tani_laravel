<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\Pengaduan;
use App\Models\SurveiKepuasan;
use App\Models\User; // Tambahan: import model User
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalKonsultasi = Konsultasi::count();
        $menungguBalasan = Konsultasi::where('status', 'menunggu')->count();
        $pengaduanAktif = Pengaduan::whereIn('status', ['menunggu', 'diproses'])->count();
        
        // Tambahan: Menghitung total petani
        $totalPetani = User::count();
        
        $totalSurvei = SurveiKepuasan::count();
        $indeksSKM = $totalSurvei > 0 ? SurveiKepuasan::avg('rating') : 0;
        
        $aktivitasTerbaru = Konsultasi::with('pengguna')->orderBy('created_at', 'desc')->take(5)->get();

        $statusSelesai = Konsultasi::where('status', 'selesai')->count();
        $statusDijawab = Konsultasi::where('status', 'dijawab')->count();
        $statusMenunggu = Konsultasi::where('status', 'menunggu')->count();
        
        // Menghitung tren 7 hari terakhir secara riil
        $tujuhHariLalu = Carbon::now()->subDays(6)->startOfDay();
        $trenKakao = [];
        $trenKelapa = [];
        $trenKopi = [];
        $labelHari = [];
        
        for ($i = 0; $i < 7; $i++) {
            $tanggal = Carbon::now()->subDays(6 - $i)->format('Y-m-d');
            $labelHari[] = Carbon::now()->subDays(6 - $i)->locale('id')->isoFormat('ddd');
            
            $trenKakao[] = Konsultasi::whereDate('created_at', $tanggal)->where('komoditas', 'Kakao')->count();
            $trenKelapa[] = Konsultasi::whereDate('created_at', $tanggal)->where('komoditas', 'Kelapa')->count();
            $trenKopi[] = Konsultasi::whereDate('created_at', $tanggal)->where('komoditas', 'Kopi')->count();
        }

        return view('admin.dashboard', compact(
            'totalKonsultasi', 'menungguBalasan', 'pengaduanAktif', 
            'indeksSKM', 'totalSurvei', 'aktivitasTerbaru',
            'statusSelesai', 'statusDijawab', 'statusMenunggu',
            'labelHari', 'trenKakao', 'trenKelapa', 'trenKopi',
            'totalPetani' // Tambahan: dikirim ke view
        ));
    }
}