<?php

namespace App\Models;

use App\Traits\BelongsToPerusahaan;
use App\Traits\HasPerusahaan;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
      use BelongsToPerusahaan, HasPerusahaan;

    protected $fillable = [
        'perusahaan_id',
        'karyawan_id',
        'presensi_id',
        'izin',
        'deskripsi',
        'file',
    ];

      public function presensi()
    {
        return $this->belongsTo(Presensi::class, foreignKey: 'presensi_id');
    }
}
