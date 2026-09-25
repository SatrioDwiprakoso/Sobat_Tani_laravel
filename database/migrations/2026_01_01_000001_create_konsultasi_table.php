<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsultasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
            $table->enum('komoditas', ['kakao', 'kelapa', 'kopi']);
            $table->string('judul_keluhan');
            $table->text('deskripsi_gejala');
            $table->enum('tingkat_urgensi', ['rendah', 'sedang', 'tinggi']);
            $table->string('foto_tanaman')->nullable();
            $table->text('tanggapan_ahli')->nullable();
            $table->enum('status', ['menunggu', 'dijawab', 'selesai'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsultasi');
    }
};
