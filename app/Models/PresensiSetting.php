<?php
namespace App\Models;

use App\Traits\BelongsToPerusahaan;
use App\Traits\HasPerusahaan;
use Illuminate\Database\Eloquent\Model;

class PresensiSetting extends Model
{
    use BelongsToPerusahaan, HasPerusahaan;
     

    protected $fillable = [
        'perusahaan_id',
        'batas_waktu_pengajuan_izin',
        'batas_waktu_sebelum_absen_masuk',
        'batas_waktu_sesudah_absen_masuk',
        'batas_waktu_sebelum_absen_keluar',
        'batas_waktu_sesudah_absen_keluar',
        'batas_waktu_keterlambatan',
        'status_presensi',
    ];

    
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, foreignKey: 'perusahaan_id');
    }
}
