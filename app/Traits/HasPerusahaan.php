<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;

trait HasPerusahaan
{
    protected static function bootHasPerusahaan()
    {
        // 🔹 Auto isi id_sekolah saat create data
        static::creating(function ($model) {
            if (auth()->check() && auth()->user()->perusahaan_id) {
                $model->perusahaan_id = auth()->user()->perusahaan_id;
            }
        });

        // 🔹 Global scope: filter data berdasarkan sekolah user
        static::addGlobalScope('perusahaans', function (Builder $builder) {
            if (auth()->check()) {
                $user = auth()->user()->perusahaan_id;

                // Kalau user punya id_sekolah (bukan super admin)
                if ($user) {
                    $builder->where($builder->getModel()->getTable() . '.perusahaan_id', $user);
                }
            }
        });
    }
}
