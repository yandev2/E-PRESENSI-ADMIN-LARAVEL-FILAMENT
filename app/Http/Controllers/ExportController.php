<?php

namespace App\Http\Controllers;

use App\Filament\Exports\JabatanExporter;
use App\Filament\Exports\KaryawanExporter;
use App\Filament\Exports\PresensiExporter;
use App\Filament\Exports\ShiftExporter;
use App\Models\Jabatan;
use App\Models\Perusahaan;
use App\Models\Presensi;
use App\Models\Shift;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

use function Livewire\str;

class ExportController extends Controller
{
    public function export_karyawan(Request $request)
    {
        $type = urldecode($request->type);
        $id = $request->id;
        $data = User::with(['karyawan', 'karyawan.jabatan', 'karyawan.shift'])->whereIn('id', $id)->get();
        $karyawan = $data->toArray();

        $json = [];
        $dataKaryawan = [];
        $perusahaan = Perusahaan::findOrFail($karyawan[0]['perusahaan_id'])->value('nama_perusahaan');

        $data->each(function ($item) use (&$dataKaryawan) {
            $dataKaryawan[] = [
                'nama' => $item->name,
                'email' => $item->email,
                'nik' => $item->karyawan->nik,
                'nip' => $item->karyawan->nip,
                'agama' => $item->karyawan->agama,
                'tempat_lahir' => $item->karyawan->tempat_lahir,
                'tanggal_lahir' => Carbon::parse($item->karyawan->tanggal_lahir)->format('d-m-Y'),
                'jenis_kelamin' => $item->karyawan->jenis_kelamin,
                'alamat_ktp' => $item->karyawan->alamat_ktp,
                'alamat_domisili' => $item->karyawan?->alamat_domisili,
                'jabatan' => $item->karyawan?->jabatan?->nama_jabatan,
                'shift' => $item->karyawan?->shift?->nama_shift,
                'tipe_karyawan' => $item->karyawan->tipe_karyawan,
                'status_karyawan' => $item->karyawan->status_karyawan,
                'nomor_telp' => $item->karyawan->nomor_telp,
                'status_dinas' => $item->karyawan->status_dinas,
                'pendidikan_terakhir' => $item->karyawan->pendidikan_terakhir,
            ];
        });

        $json = [
            'data_karyawan' => $dataKaryawan,
            'perusahaan' => strtoupper($perusahaan),
        ];


        $fileName = 'Export_Data_Karyawan_tanggal_' . Carbon::now()->format('d-M-Y');

        if ($type == 'pdf') {
            $pdf = Pdf::loadView('component.exporter.KaryawanExporterPdf', compact('json'))
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ])
                ->setPaper('A4', 'landscape');
            return response()->stream(
                fn() => print($pdf->output()),
                200,
                [
                    "Content-Type" => "application/pdf",
                    "Content-Disposition" => "inline; filename={$fileName}.pdf"
                ]
            );
        } else {
            return Excel::download(new KaryawanExporter($json), "{$fileName}.xlsx");
        }
    }

    public function export_jabatan(Request $request)
    {
        $type = urldecode($request->type);
        $id = $request->id;
        $data = Jabatan::with(['gajiAktif'])->whereIn('id', $id)->orderByDesc('created_at')->get();
        $jabatan = $data->toArray();

        $dataJabatan = [];
        $perusahaan = Perusahaan::findOrFail($jabatan[0]['perusahaan_id'])->value('nama_perusahaan');

        $data->each(function ($item) use (&$dataJabatan) {
            $dataJabatan[] = [
                'kode_jabatan' => $item->kode_jabatan,
                'nama_jabatan' => $item->nama_jabatan,
                'deskripsi' => $item->deskripsi ?? '-',
                'gaji_bulanan' => $item?->gajiAktif?->gaji_bulanan ?? 0,
                'gaji_lembur' => $item?->gajiAktif?->gaji_lembur ?? 0,
                'jumlah_hari_kerja' => $item?->gajiAktif?->jumlah_hari_kerja  ?? 0,
                'potongan_sakit' => $item?->gajiAktif?->potongan_sakit  ?? 0,
                'potongan_izin' => $item?->gajiAktif?->potongan_izin  ?? 0,
                'potongan_alpha' => $item?->gajiAktif?->potongan_alpha  ?? 0,
                'potongan_tidak_absen_keluar' => $item?->gajiAktif?->potongan_tidak_absen_keluar  ?? 0,
            ];
        });

        $json = [
            'data_jabatan' => $dataJabatan,
            'perusahaan' => strtoupper($perusahaan),
        ];

        $fileName = 'Export_Data_Jabatan_tanggal_' . Carbon::now()->format('d-M-Y');

        if ($type == 'pdf') {
            $pdf = Pdf::loadView('component.exporter.JabatanExporterPdf', compact('json'))
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ])
                ->setPaper('A4', 'landscape');
            return response()->stream(
                fn() => print($pdf->output()),
                200,
                [
                    "Content-Type" => "application/pdf",
                    "Content-Disposition" => "inline; filename={$fileName}.pdf"
                ]
            );
        } else {
            return Excel::download(new JabatanExporter($json), "{$fileName}.xlsx");
        }
    }

    public function export_shift(Request $request)
    {
        $type = urldecode($request->type);
        $id = $request->id;
        $data = Shift::with(['karyawan'])->whereIn('id', $id)->orderByDesc('created_at')->get();
        $shift = $data->toArray();

        $dataShift = [];
        $perusahaan = Perusahaan::findOrFail($shift[0]['perusahaan_id'])->value('nama_perusahaan');

        $data->each(function ($item) use (&$dataShift) {
            $dataShift[] = [
                'nama_shift' => $item->nama_shift,
                'jam_masuk' => $item->jam_masuk ?? '-',
                'jam_keluar' => $item?->jam_keluar ?? '-',
                'jumlah_karyawan' => $item->karyawan->count()
            ];
        });

        $json = [
            'data_shift' => $dataShift,
            'perusahaan' => strtoupper($perusahaan),
        ];

        $fileName = 'Export_Data_Shift_tanggal_' . Carbon::now()->format('d-M-Y');

        if ($type == 'pdf') {
            $pdf = Pdf::loadView('component.exporter.ShiftExporterPdf', compact('json'))
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ])
                ->setPaper('A4', 'landscape');
            return response()->stream(
                fn() => print($pdf->output()),
                200,
                [
                    "Content-Type" => "application/pdf",
                    "Content-Disposition" => "inline; filename={$fileName}.pdf"
                ]
            );
        } else {
            return Excel::download(new ShiftExporter($json), "{$fileName}.xlsx");
        }
    }

    public function export_presensi(Request $request)
    {
        $type = urldecode($request->type);
        $name = urldecode($request->name_file);
        $id =  explode(',', urldecode($request->id));
        $data = Presensi::with([
            'karyawan:id,user_id,jenis_kelamin,nip',
            'karyawan.user:id,name',
            'shift:id,nama_shift,jam_masuk,jam_keluar',
            'jabatan:id,nama_jabatan',
        ])->whereIn('id', $id)->orderByDesc('created_at')->get();
        $grouped = $data->groupBy(fn($item) => $item->karyawan->id);
        $presensi = $data->toArray();


        $perusahaan = Perusahaan::findOrFail($presensi[0]['perusahaan_id'])->value('nama_perusahaan');

        //=>hitung total semua kehadiran
        $status = ['H', 'I', 'S'];
        $total_kehadiran = collect($status)->mapWithKeys(function ($status) use ($presensi) {
            return [$status => collect($presensi)->where('status_masuk', $status)->count()];
        });

        //=>ambil tahun bulan yang sedang di export
        $tanggalAwal = Carbon::parse($data->first()->tanggal)->format('d-m-Y');
        $tanggalAkhir = Carbon::parse($data->last()->tanggal)->format('d-m-Y');

        $dataPresensi = [];
        $grouped->each(function ($item) use (&$dataPresensi) {
            $dataPresensi[] = [
                'nama' => $item[0]->karyawan?->user?->name ?? '-',
                'nip'  => $item[0]->karyawan?->nip ?? '-',
                'jk'   => $item[0]->karyawan?->jenis_kelamin ?? '-',
                'status_masuk' =>  $item->pluck('status_masuk')->toArray(),
                'status_keluar' =>  $item->pluck('status_keluar')->toArray(),
                'tanggal' => $item->pluck('tanggal')->toArray(),
            ];
        });

        $json = [
            "tanggal_awal" => $tanggalAwal,
            "tanggal_akhir" => $tanggalAkhir,
            "presensi" => $dataPresensi,
            "perusahaan" =>  strtoupper($perusahaan),
            "total" => $total_kehadiran,
        ];


        $fileName = 'Export_' . $name;

        if ($type == 'pdf') {
            $pdf = Pdf::loadView('component.exporter.PresensiExporterPdf', compact('json'))
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ])
                ->setPaper('A4', 'landscape');
            return response()->stream(
                fn() => print($pdf->output()),
                200,
                [
                    "Content-Type" => "application/pdf",
                    "Content-Disposition" => "inline; filename={$fileName}.pdf"
                ]
            );
        } else {
            return Excel::download(new PresensiExporter($json), "{$fileName}.xlsx");
        }
    }
}
