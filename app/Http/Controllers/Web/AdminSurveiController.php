<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\SurveiKepuasan;

class AdminSurveiController extends Controller {
    public function index() {
        $survei = SurveiKepuasan::with('konsultasi.pengguna')->orderBy("created_at", "desc")->get();
        $totalSurvei = $survei->count();
        $indeksSKM = $totalSurvei > 0 ? $survei->avg("rating") : 0;
        
        $bintang = [
            5 => $survei->where("rating", 5)->count(),
            4 => $survei->where("rating", 4)->count(),
            3 => $survei->where("rating", 3)->count(),
            2 => $survei->where("rating", 2)->count(),
            1 => $survei->where("rating", 1)->count(),
        ];
        
        // Kalkulasi Skor per Aspek berdasarkan data nyata kriteria_penilaian
        // Jika survei memiliki kriteria X, kita ambil rata-rata rating dari survei tersebut
        $aspekData = [];
        foreach ($survei as $s) {
            $kriteriaArray = is_array($s->kriteria_penilaian) ? $s->kriteria_penilaian : json_decode($s->kriteria_penilaian, true);
            if ($kriteriaArray) {
                foreach ($kriteriaArray as $k) {
                    if (!isset($aspekData[$k])) {
                        $aspekData[$k] = ['total_rating' => 0, 'count' => 0];
                    }
                    $aspekData[$k]['total_rating'] += $s->rating;
                    $aspekData[$k]['count']++;
                }
            }
        }
        
        $skorAspek = [];
        foreach ($aspekData as $aspek => $data) {
            $skorAspek[$aspek] = round($data['total_rating'] / $data['count'], 1);
        }
        
        // Urutkan dari tertinggi ke terendah, ambil 5 teratas
        arsort($skorAspek);
        $skorAspek = array_slice($skorAspek, 0, 5);
        
        // Fallback jika tidak ada data kriteria sama sekali namun harus tampil layout (opsional, disesuaikan permintaan "real")
        
        return view("admin.survei.index", compact("survei", "totalSurvei", "indeksSKM", "bintang", "skorAspek"));
    }
}
