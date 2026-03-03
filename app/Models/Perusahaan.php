<?php

namespace App\Models;

use App\Traits\BelongsToPerusahaan;
use App\Traits\HasPerusahaan;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
  protected $fillable = [
    'nama_perusahaan',
    'npwp',
    'kontak',
    'email',
    'site',
    'deskripsi',
    'alamat',
    'lokasi',
    'logo',
    'status',
  ];

  public function user()
  {
    return $this->hasMany(User::class, foreignKey: 'perusahaan_id');
  }


  public function owner()
  {
    return $this->hasOne(User::class)->where('is_owner', true);
  }

  public function kantor()
  {
    return $this->hasMany(Kantor::class, foreignKey: 'perusahaan_id');
  }

  protected static function booted()
  {
    static::updating(function ($model) {
      if ($model->isDirty('logo')) {
        $oldFile = $model->getOriginal('logo');

        if ($oldFile && \Storage::disk('public')->exists($oldFile)) {
          if (!in_array($oldFile, ['perusahaan/default_logo.png'])) {
            \Storage::disk('public')->delete($oldFile);
          }
        }
      }
    });

    static::deleted(function ($model) {
      if ($model->logo && \Storage::disk('public')->exists($model->logo)) {
        if (!in_array($model->logo, ['perusahaan/default_logo.png'])) {
          \Storage::disk('public')->delete($model->logo);
        }
      }
    });
  }
}
