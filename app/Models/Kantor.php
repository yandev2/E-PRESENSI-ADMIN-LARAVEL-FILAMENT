<?php

namespace App\Models;

use App\Traits\BelongsToPerusahaan;
use App\Traits\HasPerusahaan;
use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
      use BelongsToPerusahaan, HasPerusahaan;

    protected $fillable = [
        'perusahaan_id',
        'nama_kantor',
        'lokasi',
        'radius',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, foreignKey: 'perusahaan_id');
    }

       public function karyawan()
    {
        return $this->hasMany(Karyawan::class, foreignKey: 'kantor_id');
    }
}
