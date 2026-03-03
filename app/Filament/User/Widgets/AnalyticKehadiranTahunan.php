<?php

namespace App\Filament\User\Widgets;

use App\Models\Presensi;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AnalyticKehadiranTahunan extends Widget
{
    protected string $view = 'filament.user.widgets.analytic-kehadiran-tahunan';

}
