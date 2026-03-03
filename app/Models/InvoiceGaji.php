<?php

namespace App\Models;
use App\Traits\BelongsToPerusahaan;
use App\Traits\HasPerusahaan;
use Illuminate\Database\Eloquent\Model;

class InvoiceGaji extends Model
{
   use BelongsToPerusahaan, HasPerusahaan;
  protected $fillable = [
    'perusahaan_id',
    'karyawan_id',
    'gaji_id',
    'bulan',
    'tahun',
    'jumlah_alpha',
    'jumlah_izin',
    'jumlah_sakit',
    'jumlah_hadir',
    'jumlah_potongan',
    'gaji_diterima',
    'status',
    'file',
    'tanggal_generate',
  ];

  public function gaji()
  {
    return $this->belongsTo(Gaji::class, foreignKey: 'gaji_id');
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
