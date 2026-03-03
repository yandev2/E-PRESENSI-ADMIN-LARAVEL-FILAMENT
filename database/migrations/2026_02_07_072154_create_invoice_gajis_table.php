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
        Schema::create('invoice_gajis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->cascadeOnDelete();
            $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
            $table->foreignId('gaji_id')->nullable()->constrained('gajis')->nullOnDelete();
            $table->text('bulan')->nullable();
            $table->text('tahun')->nullable();
            $table->text('jumlah_alpha')->nullable();
            $table->text('jumlah_izin')->nullable();
            $table->text('jumlah_sakit')->nullable();
            $table->text('jumlah_hadir')->nullable();
            $table->text('jumlah_potongan')->nullable();
            $table->text('gaji_diterima')->nullable();
            $table->text('status')->nullable();
            $table->string('file')->nullable();
            $table->date('tanggal_generate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_gajis');
    }
};
