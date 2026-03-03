<?php

namespace App\Traits;
use App\Models\Perusahaan;

trait BelongsToPerusahaan
{
 
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }
}
