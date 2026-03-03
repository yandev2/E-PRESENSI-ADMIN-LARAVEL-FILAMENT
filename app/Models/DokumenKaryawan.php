<?php

namespace App\Models;

use App\Traits\BelongsToPerusahaan;
use App\Traits\HasPerusahaan;
use Illuminate\Database\Eloquent\Model;

class DokumenKaryawan extends Model
{
    use BelongsToPerusahaan, HasPerusahaan;
    protected $fillable = [
        'perusahaan_id',
        'karyawan_id',
        'jenis_dokumen',
        'nama_dokumen',
        'deskripsi',
        'tanggal_terbit',
        'tanggal_expired',
        'file',
    ];

    public function documentable()
    {
        return $this->morphTo();
    }

    
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, foreignKey: 'karyawan_id');
    }
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, foreignKey: 'perusahaan_id');
    }

     protected static function booted()
    {
        static::deleted(function ($model) {
            if ($model->file && \Storage::disk('public')->exists($model->file)) {
                \Storage::disk('public')->delete($model->file);
            }
        });
    }
}
