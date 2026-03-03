<?php

namespace App\Models;

use App\Traits\BelongsToPerusahaan;
use App\Traits\HasPerusahaan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Karyawan extends Model
{
  use BelongsToPerusahaan, HasPerusahaan;
  protected $fillable = [
    'perusahaan_id',
    'user_id',
    'jabatan_id',
    'shift_id',
    'kantor_id',
    'nik',
    'nip',
    'pendidikan_terakhir',
    'agama',
    'tempat_lahir',
    'tanggal_lahir',
    'jenis_kelamin',
    'status_pernikahan',
    'alamat_ktp',
    'alamat_domisili',
    'tipe_karyawan',
    'status_karyawan',
    'nomor_telp',
    'status_dinas',
  ];

  public function presensi()
  {
    return $this->hasMany(Presensi::class, foreignKey: 'karyawan_id')->orderByDesc('tanggal');
  }
  public function presensiHariIni()
  {
    return $this->hasOne(Presensi::class)
      ->whereDate('tanggal', today());
  }

  public function presensiBulanIni()
  {
    return $this->hasMany(Presensi::class, 'karyawan_id')
      ->whereMonth('tanggal', now()->month)
      ->whereYear('tanggal', now()->year);
  }

  public function nok()
  {
    return $this->hasMany(NokKaryawan::class, foreignKey: 'karyawan_id');
  }
  public function dokumen()
  {
    return $this->hasMany(DokumenKaryawan::class, foreignKey: 'karyawan_id');
  }
  public function lokasi()
  {
    return $this->hasMany(LiveLocationkaryawan::class, foreignKey: 'karyawan_id');
  }
  public function tugas()
  {
    return $this->hasMany(Tugas::class, foreignKey: 'karyawan_id');
  }

  public function laporanTugas()
  {
    return $this->hasMany(LaporanTugas::class, foreignKey: 'karyawan_id');
  }

  public function shift()
  {
    return $this->belongsTo(Shift::class, foreignKey: 'shift_id');
  }
  public function jabatan()
  {
    return $this->belongsTo(Jabatan::class, foreignKey: 'jabatan_id');
  }
   public function kantor()
  {
    return $this->belongsTo(Kantor::class, foreignKey: 'kantor_id');
  }
  public function user()
  {
    return $this->belongsTo(User::class, foreignKey: 'user_id');
  }
  public function perusahaan()
  {
    return $this->belongsTo(Perusahaan::class, foreignKey: 'perusahaan_id');
  }

  protected static function booted()
  {
    static::updating(function ($model) {
      foreach ($model->dokumen as $data) {
        $data->update();
      }
      foreach ($model->nok as $data) {
        $data->update();
      }
    });

    static::creating(function ($model) {
      foreach ($model->dokumen as $data) {
        $data->create();
      }
      foreach ($model->nok as $data) {
        $data->create();
      }
    });

    static::deleting(function ($model) {
      DB::transaction(function () use ($model) {
        if ($model->user) {
          $model->user->delete();
        }
      });
      foreach ($model->dokumen as $data) {
        $data->delete();
      }
      foreach ($model->nok as $data) {
        $data->delete();
      }
    });
  }


  public function hitungGaji(?int $bulan = null, ?int $tahun = null): array
  {
    $bulan = $bulan ?? now()->month;
    $tahun = $tahun ?? now()->year;

    $toInt  = fn($v) => (int) str_replace([',', '.'], '', $v);

    $gaji = $this->jabatan?->gajiAktif;

    if (! $gaji) {
      return [
        'pokok'    => 0,
        'potongan' => 0,
        'lembur'   => 0,
        'total'    => 0,
      ];
    }

    $gajiHarian      = $toInt($gaji->gaji_harian);
    $gajiLembur      = $toInt($gaji->gaji_lembur);

    $potongan = [
      'A' => (float) $gaji->potongan_alpha,
      'I' => (float) $gaji->potongan_izin,
      'S' => (float) $gaji->potongan_sakit,
      'T' => (float) $gaji->potongan_tidak_absen_keluar,
    ];

    $presensi = $this->presensi()
      ->whereMonth('tanggal', $bulan)
      ->whereYear('tanggal', $tahun)
      ->get();

    $alpha = $gaji?->jumlah_hari_kerja ?? 0 -  $presensi->count() ?? 0;

    $totalPokok    = 0;
    $totalPotongan = 0;
    $totalLembur   = 0;

    for ($i = 0; $i < $alpha; $i++) {
      $totalPotongan +=  $gajiHarian * ($potongan['A'] / 100);
    }

    foreach ($presensi as $p) {
      $gajiHari     = $gajiHarian;
      $potonganHari = 0;
      $lemburHari   = 0;

      if ($p->status_masuk !== 'H' && isset($potongan[$p->status_masuk])) {
        $potonganHari = $gajiHarian * ($potongan[$p->status_masuk] / 100);
      } elseif ($p->status_keluar === null) {
        $potonganHari = $gajiHarian * ($potongan['T'] / 100);
      }

      if ($p->is_lembur) {
        $lemburHari = $gajiLembur;
      }

      $totalPokok    += $gajiHari;
      $totalPotongan += $potonganHari;
      $totalLembur   += $lemburHari;
    }
 
     $total =  $totalPokok - $totalPotongan + $totalLembur;
    return [
      'pokok'    =>  number_format(round($totalPokok), 0, ',', '.'),
      'potongan' => number_format(round($totalPotongan), 0, ',', '.'),
      'lembur'   =>  number_format(round($totalLembur), 0, ',', '.'),
      'total'    => number_format(round($total), 0, ',', '.'),
    ];
  }
}
