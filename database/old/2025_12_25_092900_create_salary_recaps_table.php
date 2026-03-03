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
        Schema::create('salary_recaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('position_id')->nullable()->constrained('positions')->nullOnDelete();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('total_hadir');
            $table->integer('total_alpha');
            $table->integer('total_izin');
            $table->integer('gaji_bulanan');
            $table->integer('total_potongan');
            $table->integer('gaji_diterima');
            $table->enum('status', ['diterima', 'ditunda'])->default('diterima');
            $table->string('file')->nullable();
            $table->date('generate_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_recaps');
    }
};
