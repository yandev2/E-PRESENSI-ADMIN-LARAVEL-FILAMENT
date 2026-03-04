<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presensi_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->cascadeOnDelete();
            $table->time('batas_waktu_pengajuan_izin')->nullable()->default(null);
            $table->integer('batas_waktu_sebelum_absen_masuk')->default(1);
            $table->integer('batas_waktu_sesudah_absen_masuk')->default(1);
            $table->integer('batas_waktu_sebelum_absen_keluar')->default(1);
            $table->integer('batas_waktu_sesudah_absen_keluar')->default(1);
            $table->integer('batas_waktu_keterlambatan')->default(30);
            $table->boolean('status_presensi')->default('aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_settings');
    }
};
