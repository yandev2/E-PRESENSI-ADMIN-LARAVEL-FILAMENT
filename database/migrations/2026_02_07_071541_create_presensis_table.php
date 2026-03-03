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
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->cascadeOnDelete();
            $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans')->nullOnDelete();
            $table->foreignId('kantor_id')->nullable()->constrained('kantors')->nullOnDelete();
            $table->foreignId('gaji_id')->nullable()->constrained('gajis')->nullOnDelete();
            $table->date('tanggal');
            $table->text('status')->nullable();
            $table->text('status_masuk'); // H I S A
            $table->text('status_keluar')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->string('lokasi_masuk')->nullable();
            $table->string('lokasi_keluar')->nullable();
            $table->string('wajah_masuk')->nullable();
            $table->string('wajah_keluar')->nullable();
            $table->boolean('is_lembur')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
