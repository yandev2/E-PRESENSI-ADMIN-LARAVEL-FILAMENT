<?php

namespace App\Models;

use App\Traits\BelongsToPerusahaan;
use App\Traits\HasPerusahaan;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
  use BelongsToPerusahaan, HasPerusahaan;
  protected $fillable = [
    'perusahaan_id',
    'kode_jabatan',
    'nama_jabatan',
    'deskripsi',
    'created_at'
  ];
  public function karyawan()
  {
    return $this->hasMany(Karyawan::class, 'jabatan_id');
  }

  public function gaji()
  {
    return $this->hasMany(Gaji::class, 'jabatan_id');
  }
  public function gajiAktif()
  {
    return $this->hasOne(Gaji::class, 'jabatan_id')->where('status', 'aktif');
  }
  public function perusahaan()
  {
    return $this->belongsTo(Perusahaan::class, foreignKey: 'perusahaan_id');
  }

  public function presensi()
  {
    return $this->hasMany(Presensi::class, foreignKey: 'shift_id');
  }

  protected static function booted()
  {
    static::updating(function ($model) {
      foreach ($model->gaji as $data) {
        $data->update();
      }
    });

    static::creating(function ($model) {
      foreach ($model->gaji as $data) {
        $data->create();
      }
    });

    static::deleting(function ($model) {
      foreach ($model->gaji as $data) {
        $data->delete();
      }
      if ($model->karyawan()->exists()) {
        Notification::make()
          ->title('Ups')
          ->body('jabatan tidak bisa dihapus karena masih terikat dengan karyawan')
          ->danger()
          ->send();
        throw new Halt();
      }

      if ($model->presensi()->exists()) {
        Notification::make()
          ->title('Ups')
          ->body('jabatan tidak bisa dihapus karena masih terikat dengan presensi karyawan, silahkan update data untuk melakukan perubahan pada jabatan')
          ->danger()
          ->send();
        throw new Halt();
      }
    });
  }
}
