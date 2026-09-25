<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
            $table->enum('kategori', ['infrastruktur', 'pupuk', 'hama', 'layanan_publik']);
            $table->string('lokasi_kebun');
            $table->text('deskripsi');
            $table->string('foto_bukti')->nullable();
            $table->enum('status', ['terkirim', 'diproses', 'ditindaklanjuti', 'selesai'])->default('terkirim');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
