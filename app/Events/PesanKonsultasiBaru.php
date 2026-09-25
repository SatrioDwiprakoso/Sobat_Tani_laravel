<?php

namespace App\Events;

use App\Models\PesanKonsultasi;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PesanKonsultasiBaru implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pesan;
    public $konsultasiId;

    public function __construct(PesanKonsultasi $pesan)
    {
        // Load relasi pengguna agar Flutter bisa tahu siapa pengirimnya
        $this->pesan = $pesan->load('pengguna'); 
        $this->konsultasiId = $pesan->konsultasi_id;
    }

    public function broadcastOn()
    {
        // Menggunakan public channel untuk kemudahan. 
        // Nama channel unik untuk setiap konsultasi.
        return new Channel('chat.konsultasi.' . $this->konsultasiId);
    }

    public function broadcastAs()
    {
        return 'PesanBaru'; // Nama event yang akan didengarkan oleh Flutter
    }
}