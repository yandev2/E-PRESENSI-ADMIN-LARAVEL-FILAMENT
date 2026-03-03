<?php
namespace App\Models;

use App\Traits\BelongsToPerusahaan;
use App\Traits\HasPerusahaan;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use BelongsToPerusahaan, HasPerusahaan;
    protected $fillable = [
        'perusahaan_id',
        'karyawan_id',
        'shift_id',
        'jabatan_id',
        'gaji_id',
        'tanggal',
        'status_masuk',
        'status_keluar',
        'jam_masuk',
        'jam_keluar',
        'lokasi_masuk',
        'lokasi_keluar',
        'wajah_masuk',
        'wajah_keluar',
        'is_lembur',
        'status',
        'file',
    ];

    public function izin()
    {
        return $this->hasOne(Izin::class, foreignKey: 'presensi_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, foreignKey: 'jabatan_id');
    }

    public function gaji()
    {
        return $this->belongsTo(Gaji::class, foreignKey: 'gaji_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, foreignKey: 'shift_id');
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
