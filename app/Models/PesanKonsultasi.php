<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesanKonsultasi extends Model
{
    use HasFactory;

    protected $table = 'pesan_konsultasi';

    protected $fillable = [
        'konsultasi_id',
        'pengguna_id',
        'pesan',
        'foto',
    ];

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'konsultasi_id');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
}
