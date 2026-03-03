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
        Schema::create('perusahaans', function (Blueprint $table) {
            $table->id();
            $table->text('nama_perusahaan');
            $table->string('npwp')->nullable();
            $table->string('kontak')->nullable();
            $table->string('email')->nullable();
            $table->string('site')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->longText('alamat')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('logo')->nullable();
            $table->text('status')->nullable()->default('aktif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaans');
    }
};
