<?php

namespace App\Models;
use App\Traits\BelongsToPerusahaan;
use App\Traits\HasPerusahaan;
use Illuminate\Database\Eloquent\Model;

class LiveLocationkaryawan extends Model
{
     use BelongsToPerusahaan, HasPerusahaan;
    protected $fillable = [
        'perusahaan_id',
        'karyawan_id',
        'presensi_id',
        'latitude',
        'longitude',
        'address',
        'is_active',
    ];

    public function presensi()
    {
        return $this->belongsTo(Presensi::class, foreignKey: 'presensi_id');
    }
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, foreignKey: 'karyawan_id');
    }
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, foreignKey: 'perusahaan_id');
    }
}
