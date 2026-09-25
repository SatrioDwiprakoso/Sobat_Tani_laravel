<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveiKepuasan extends Model
{
    use HasFactory;

    protected $table = 'survei_kepuasan';

    protected $fillable = [
        'pengguna_id',
        'konsultasi_id',
        'rating',
        'kriteria_penilaian',
        'catatan_evaluasi',
    ];

    protected $casts = [
        'kriteria_penilaian' => 'array',
        'rating' => 'integer',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'konsultasi_id');
    }
}
