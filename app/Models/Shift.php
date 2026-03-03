<?php

namespace App\Models;

use App\Traits\BelongsToPerusahaan;
use App\Traits\HasPerusahaan;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use BelongsToPerusahaan, HasPerusahaan;
    protected $fillable = [
        'perusahaan_id',
        'nama_shift',
        'jam_masuk',
        'jam_keluar',
    ];
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, foreignKey: 'perusahaan_id');
    }

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, foreignKey: 'shift_id');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, foreignKey: 'shift_id');
    }

    protected static function booted()
    {
        static::updating(function ($model) {});

        static::creating(function ($model) {});

        static::deleting(function ($model) {

            if ($model->karyawan()->exists()) {
                Notification::make()
                    ->title('Ups')
                    ->body('shift tidak bisa dihapus karena masih terikat dengan karyawan')
                    ->danger()
                    ->send();
                throw new Halt();
            }

            if ($model->presensi()->exists()) {
                Notification::make()
                    ->title('Ups')
                    ->body('shift tidak bisa dihapus karena masih terikat dengan presensi karyawan. silahkan update data untuk melakukan perubahan pada shift')
                    ->danger()
                    ->send();
                throw new Halt();
            }
        });
    }
}
