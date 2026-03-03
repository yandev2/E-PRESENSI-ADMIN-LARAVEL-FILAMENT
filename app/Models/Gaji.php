<?php

namespace App\Models;
use App\Traits\BelongsToPerusahaan;
use App\Traits\HasPerusahaan;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
     use BelongsToPerusahaan, HasPerusahaan;
    protected $fillable = [
        'perusahaan_id',
        'jabatan_id',
        'gaji_bulanan',
        'gaji_harian',
        'gaji_lembur',
        'jumlah_hari_kerja',
        'potongan_sakit',
        'potongan_izin',
        'potongan_alpha',
        'potongan_tidak_absen_keluar',
        'berlaku_dari',
        'berlaku_sampai',
        'status',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, foreignKey: 'perusahaan_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, foreignKey: 'jabatan_id');
    }
}
