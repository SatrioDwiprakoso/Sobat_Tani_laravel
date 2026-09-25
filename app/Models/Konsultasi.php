<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasFactory;

    protected $table = 'konsultasi';

    protected $fillable = [
        'pengguna_id',
        'komoditas',
        'judul_keluhan',
        'deskripsi_gejala',
        'tingkat_urgensi',
        'foto_tanaman',
        'tanggapan_ahli',
        'status',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function surveiKepuasan()
    {
        return $this->hasOne(SurveiKepuasan::class, 'konsultasi_id');
    }

    public function pesan()
    {
        return $this->hasMany(PesanKonsultasi::class, 'konsultasi_id');
    }
}
