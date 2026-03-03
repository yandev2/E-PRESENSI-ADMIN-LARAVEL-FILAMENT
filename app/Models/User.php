<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Andreia\FilamentUiSwitcher\Models\Traits\HasUiPreferences;
use App\Traits\BelongsToPerusahaan;
use App\Traits\HasPerusahaan;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,   HasUiPreferences,  HasRoles;

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->hasAnyRole(['super_admin', 'admin']),
            'user'  => $this->hasAnyRole(['manager']),
            default => false,
        };
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if (! $this->avatar) {
            return null;
        }
        return asset('storage/' . $this->avatar);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'perusahaan_id',
        'name',
        'email',
        'password',
        'face_id',
        'avatar',
        'is_owner',
        'token'
    ];

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'user_id');
    }

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class, 'user_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, foreignKey: 'perusahaan_id');
    }

    protected static function booted()
    {
        static::updating(function ($model) {
            if ($model->isDirty('avatar')) {
                $oldFile = $model->getOriginal('avatar');

                if ($oldFile && \Storage::disk('public')->exists($oldFile)) {
                    if (!in_array($oldFile, ['karyawan/default_sys_l.jpg', 'karyawan/default_sys.jpg'])) {
                        \Storage::disk('public')->delete($oldFile);
                    }
                }
            }
        });

        static::deleted(function ($model) {
            if ($model->avatar && \Storage::disk('public')->exists($model->avatar)) {
                if (!in_array($model->avatar, ['karyawan/default_sys_l.jpg', 'karyawan/default_sys.jpg'])) {
                    \Storage::disk('public')->delete($model->avatar);
                }
            }
        });
    }


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ui_preferences' => 'array',

        ];
    }
}
