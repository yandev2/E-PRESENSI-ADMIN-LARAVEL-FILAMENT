<?php

namespace App\Models;
use App\Traits\BelongsToPerusahaan;
use App\Traits\HasPerusahaan;
use Illuminate\Database\Eloquent\Model;

class NokKaryawan extends Model
{
     use BelongsToPerusahaan, HasPerusahaan;
    protected $fillable = [
        'perusahaan_id',
        'karyawan_id',
        'nama',
        'hubungan',
        'kontak',
        'alamat',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, foreignKey: 'karyawan_id');
    }
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, foreignKey: 'perusahaan_id');
    }
}
