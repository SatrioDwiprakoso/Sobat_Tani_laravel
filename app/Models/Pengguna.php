<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'pengguna';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'kata_sandi',
        'nomor_telepon',
        'peran',
        'foto_profil',
    ];

    protected $hidden = [
        'kata_sandi',
    ];

    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    public function konsultasi()
    {
        return $this->hasMany(Konsultasi::class, 'pengguna_id');
    }

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'pengguna_id');
    }

    public function surveiKepuasan()
    {
        return $this->hasMany(SurveiKepuasan::class, 'pengguna_id');
    }
}
