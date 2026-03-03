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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans')->nullOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->foreignId('kantor_id')->nullable()->constrained('kantors')->nullOnDelete();
            $table->text('nik')->nullable();
            $table->text('nip')->nullable();
            $table->text('pendidikan_terakhir')->nullable();
            $table->text('nomor_telp')->nullable();
            $table->text('agama')->nullable();
            $table->text('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('jenis_kelamin')->nullable();
            $table->text('status_pernikahan')->nullable();
            $table->longText('alamat_ktp')->nullable();
            $table->longText('alamat_domisili')->nullable();
            $table->text('tipe_karyawan')->default('tetap');
            $table->text('status_karyawan')->default('aktif');
            $table->text('status_dinas')->default('dalam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
