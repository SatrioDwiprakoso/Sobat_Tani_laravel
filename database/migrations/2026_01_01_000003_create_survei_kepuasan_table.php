<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survei_kepuasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
            $table->foreignId('konsultasi_id')->constrained('konsultasi')->onDelete('cascade');
            $table->tinyInteger('rating')->comment('1-5');
            $table->json('kriteria_penilaian');
            $table->text('catatan_evaluasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survei_kepuasan');
    }
};
