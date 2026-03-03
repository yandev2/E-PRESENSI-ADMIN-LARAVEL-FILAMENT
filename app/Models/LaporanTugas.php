<?php

namespace App\Models;
use App\Traits\BelongsToPerusahaan;
use App\Traits\HasPerusahaan;
use Illuminate\Database\Eloquent\Model;

class LaporanTugas extends Model
{
     use BelongsToPerusahaan, HasPerusahaan;
    protected $fillable = [
        'perusahaan_id',
        'karyawan_id',
        'aprove_id',
        'tugas_id',
        'status',
        'laporan',
        'tanggal_approve',
        'tanggal_diserahkan',
        'file',
    ];
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, foreignKey: 'tugas_id');
    }
    public function aprove()
    {
        return $this->belongsTo(Karyawan::class, foreignKey: 'aprove_id');
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
