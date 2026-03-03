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
        Schema::create('gajis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->cascadeOnDelete();
            $table->foreignId('jabatan_id')->constrained('jabatans')->cascadeOnDelete();
            $table->text('gaji_bulanan');
            $table->text('gaji_harian');
            $table->integer('jumlah_hari_kerja')->default(26);
            $table->text('gaji_lembur')->nullable();
            $table->integer('potongan_sakit')->nullable()->default(0);
            $table->integer('potongan_izin')->nullable()->default(0);
            $table->integer('potongan_alpha')->nullable()->default(0);
            $table->integer('potongan_tidak_absen_keluar')->nullable()->default(0);
            $table->date('berlaku_dari')->nullable();
            $table->date('berlaku_sampai')->nullable();
            $table->text('status')->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gajis');
    }
};
