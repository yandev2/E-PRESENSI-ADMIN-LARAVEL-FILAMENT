<?php

namespace App\Filament\User\Widgets;

use App\Models\Jabatan;
use App\Models\Kantor;
use App\Models\Karyawan;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;

class DashboardCountOverlay extends Widget
{
    protected string $view = 'filament.user.widgets.dashboard-count-overlay';

    protected function getViewData(): array
    {
        $colorPalette = [
            ['text' => 'text-blue-600',   'bg' => 'bg-blue-100 dark:bg-blue-900/40'],
            ['text' => 'text-rose-600',   'bg' => 'bg-rose-100 dark:bg-rose-900/40'],
            ['text' => 'text-amber-600',  'bg' => 'bg-amber-100 dark:bg-amber-900/40'],
            ['text' => 'text-emerald-600', 'bg' => 'bg-emerald-100 dark:bg-emerald-900/40'],
            ['text' => 'text-indigo-600', 'bg' => 'bg-indigo-100 dark:bg-indigo-900/40'],
            ['text' => 'text-pink-600',   'bg' => 'bg-pink-100 dark:bg-pink-900/40'],
            ['text' => 'text-cyan-600',   'bg' => 'bg-cyan-100 dark:bg-cyan-900/40'],
            ['text' => 'text-orange-600', 'bg' => 'bg-orange-100 dark:bg-orange-900/40'],
            ['text' => 'text-violet-600', 'bg' => 'bg-violet-100 dark:bg-violet-900/40'],
            ['text' => 'text-lime-600',   'bg' => 'bg-lime-100 dark:bg-lime-900/40'],
        ];

        $counts = Karyawan::query()
            ->selectRaw("
            count(*) as total,
            count(case when jenis_kelamin = 'p' then 1 end) as p,
            count(case when jenis_kelamin = 'l' then 1 end) as l,
            count(case when status_karyawan = 'aktif' then 1 end) as aktif,
            count(case when status_karyawan = 'non_aktif' then 1 end) as non_aktif,
            count(case when status_karyawan = 'cuti' then 1 end) as cuti,
            count(case when status_karyawan = 'resign' then 1 end) as resign,
            count(case when status_dinas = 'dalam' then 1 end) as dinas_dalam,
            count(case when status_dinas = 'luar' then 1 end) as dinas_luar,
            count(case when tipe_karyawan = 'tetap' then 1 end) as tetap,
            count(case when tipe_karyawan = 'magang' then 1 end) as magang,
            count(case when tipe_karyawan = 'kontrak' then 1 end) as kontrak,
            count(case when tipe_karyawan = 'paruh_waktu' then 1 end) as paruh_waktu
        ")
            ->first();


        $karyawanList = [
            ['Total Karyawan', $counts->total, 'heroicon-o-user-group'],
            ['Perempuan', $counts->p, 'heroicon-o-users'],
            ['Laki-Laki', $counts->l, 'heroicon-o-user'],
            ['Aktif', $counts->aktif, 'heroicon-o-check-badge'],
            ['Cuti', $counts->cuti, 'heroicon-o-calendar'],
            ['Tetap', $counts->tetap, 'heroicon-o-shield-check'],
        ];

        $karyawanItems = collect($karyawanList)->map(function ($item, $index) use ($colorPalette) {
            $color = $colorPalette[$index % count($colorPalette)];
            return [
                'title' => "KARYAWAN",
                'label' => $item[0],
                'val'   => $item[1],
                'icon'  => $item[2],
                'color' => $color['text'],
                'bg'    => $color['bg']
            ];
        })->toArray();

        // 5. Query jabatan dinamis (lanjutkan looping warna dari index terakhir karyawan)
        $offset = count($karyawanItems);


        $jabatanItems = Jabatan::withCount('karyawan')->get()->map(function ($jbt, $index) use ($colorPalette, $offset) {
            $color = $colorPalette[($index + $offset) % count($colorPalette)];
            return [
                'title' => "JABATAN",
                'label' => $jbt->nama_jabatan,
                'val'   => $jbt->karyawan_count,
                'icon'  => 'heroicon-o-briefcase',
                'color' => $color['text'],
                'bg'    => $color['bg']
            ];
        })->toArray();


        $offset2 = count($jabatanItems);
        $kantor = Kantor::withCount('karyawan')->get()->map(function ($knt, $index) use ($colorPalette, $offset2) {
            $color = $colorPalette[($index + $offset2) % count($colorPalette)];
            return [
                'title' => "KANTOR",
                'label' => $knt->nama_kantor,
                'val'   => $knt->karyawan_count,
                'icon'  => 'heroicon-o-users',
                'color' => $color['text'],
                'bg'    => $color['bg']
            ];
        })->toArray();


        $allWidgets = array_merge($karyawanItems, $jabatanItems, $kantor);

        return [
            'displayItems' => array_merge($allWidgets, $allWidgets),
        ];
    }
}
