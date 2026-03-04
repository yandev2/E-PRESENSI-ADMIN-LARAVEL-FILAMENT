<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArrayResource;
use App\Http\Resources\ListResource;
use App\Models\Jabatan;
use App\Models\Presensi;
use App\Models\PresensiSetting;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PresensiController extends Controller
{
    public function getPresensi(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $search  = $request->search ?? null;

            $user = User::with('karyawan')->where('token', $token)->first();
            if (empty($user)) {
                return new ArrayResource(false, 'token anda tidak valid, silahkan melakukan login ulang', null);
            }

            $data = Presensi::where('karyawan_id', $user->karyawan->id)
                ->select('id', 'tanggal', 'status_masuk', 'status_keluar', 'jam_masuk', 'jam_keluar', 'status')
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($query) use ($search) {
                        $query->where('tanggal', 'ILIKE', "%{$search}%")
                            ->orWhere('status', 'ILIKE', "%{$search}%")
                            ->orWhere('status_masuk', 'ILIKE', "%{$search}%");
                    });
                })

                ->orderByDesc('tanggal')
                ->paginate(10)
                ->through(function ($item) {
                    $item->jam_masuk = $item->jam_masuk
                        ? Carbon::parse($item->jam_masuk)->format('H:i')
                        : null;

                    $item->jam_keluar = $item->jam_keluar
                        ? Carbon::parse($item->jam_keluar)->format('H:i')
                        : null;

                    return $item;
                });

            return new ListResource(true, 'list data presensi anda', $data);
        } catch (\Throwable $th) {
            return new ArrayResource(false, $th->getMessage(), null);
        }
    }
    public function getPresensiToday(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $today = Carbon::now();

            $user = User::with('karyawan')->where('token', $token)->first();
            if (empty($user)) {
                return new ArrayResource(false, 'token anda tidak valid, silahkan melakukan login ulang', null);
            }

            $presensi =  Presensi::with(['perusahaan', 'karyawan', 'shift', 'jabatan', 'gaji', 'izin'])->where('karyawan_id', $user->karyawan->id)
                ->whereDate('tanggal', $today)
                ->first();

            $response  =  $this->getDetailDataPresensi($presensi->id);

            return new ArrayResource(true, 'presensi hari ini', $response);
        } catch (\Throwable $th) {
            return new ArrayResource(false, $th->getMessage(), null);
        }
    }
    public function getDetailPresensi(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $presensi_id = $request->presensi_id;

            $user = User::with('karyawan')->where('token', $token)->first();
            if (empty($user)) {
                return new ArrayResource(false, 'token anda tidak valid, silahkan melakukan login ulang', null);
            }
            Log::info($presensi_id);
            $presensi = Presensi::with(['perusahaan', 'karyawan', 'shift', 'jabatan', 'gaji', 'izin'])->findOrFail($presensi_id);

            $response  =  $this->getDetailDataPresensi($presensi->id);

            return new ArrayResource(true, 'detail presensi tanggal ' . $presensi->tanggal, $response);
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return new ArrayResource(false, $th->getMessage(), null);
        }
    }
    public function izin(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $date = Carbon::now(config('app.timezone'));

            $validator = Validator::make($request->all(), [
                'izin' => 'required',
            ]);
            if ($validator->fails()) {
                return new ArrayResource(false, $validator->messages()->all(), null);
            }

            $user = User::with(['karyawan.jabatan.gajiAktif'])->where('token', $token)->first();
            if (empty($user)) {
                return new ArrayResource(false, 'token anda tidak valid, silahkan melakukan login ulang', null);
            }

            $perusahaan_id = $user->perusahaan_id;
            $karyawan_id = $user->karyawan->id;
            $shift_id = $user->karyawan->shift_id;
            $jabatan_id =  $user->karyawan->jabatan_id;
            $gaji_id = $user->karyawan?->jabatan?->gajiAktif->id;
            $kantor_id = $user->karyawan->kantor_id;

            $setting = PresensiSetting::where('perusahaan_id', $perusahaan_id)->first();
            if ($setting->status_presensi == 'nonaktif') {
                return new ArrayResource(false, 'Presensi dan pengajuan izin saat ini sedang di matikan oleh operator', null);
            }

            $batas_string = $setting?->batas_waktu_pengajuan_izin ?? '23:59:00';
            $batas_waktu = Carbon::createFromFormat('H:i:s', $batas_string, config('app.timezone'));

            if ($date->gt($batas_waktu)) {
                return new ArrayResource(false, 'Pengajuan izin sudah melewati batas waktu!', null);
            }

            $presensi = Presensi::where('karyawan_id', $user->karyawan->id)->whereDate('tanggal', $date)->first();
            if (!empty($presensi)) {
                return new ArrayResource(false, 'anda sudah melakukan presensi hari ini', null);
            }

            $presensi = Presensi::create([
                'perusahaan_id' => $perusahaan_id,
                'karyawan_id' => $karyawan_id,
                'kantor_id' => $kantor_id,
                'shift_id' => $shift_id,
                'jabatan_id' => $jabatan_id,
                'gaji_id' => $gaji_id,
                'status_masuk' => $request->status_masuk,
                'tanggal' => $date->format('d-M-Y'),
            ]);
            $izinData = $request->input('izin');
            $file = $request->file('izin.file');
            $path = $file->storeAs(
                "$perusahaan_id/izin/" . $date->format('d M Y'),
                $file->hashName(),
                'public'
            );

            $presensi->izin()->create([
                'perusahaan_id' => $perusahaan_id,
                'karyawan_id' => $karyawan_id,
                'presensi_id' => $presensi->id,
                'izin'          => $izinData['izin'] ?? '',
                'deskripsi'     => $izinData['deskripsi'] ?? '',
                'file' =>  $path
            ]);

            $response  =  $this->getDetailDataPresensi($presensi->id);
            return new ArrayResource(true, 'izin berhasil dibuat', $response);
        } catch (\Throwable $th) {
            Log::info('IZIN ERROR ' . $th->getMessage());
            return new ArrayResource(false, $th->getMessage(), null);
        }
    }
    public function presensi(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $date = Carbon::now(config('app.timezone'));

            $user = User::with(['karyawan.jabatan.gajiAktif'])->where('token', $token)->first();
            if (empty($user)) {
                return new ArrayResource(false, 'token anda tidak valid, silahkan melakukan login ulang', null);
            }

            $perusahaan_id = $user->perusahaan_id;
            $karyawan_id = $user->karyawan->id;
            $shift_id = $user->karyawan->shift_id;
            $jabatan_id =  $user->karyawan->jabatan_id;
            $gaji_id = $user->karyawan?->jabatan?->gajiAktif->id;
            $kantor_id = $user->karyawan->kantor_id;

            $setting = PresensiSetting::where('perusahaan_id', $perusahaan_id)->first();
            if ($setting->status_presensi == 'nonaktif') {
                return new ArrayResource(false, 'Presensi saat ini sedang di matikan oleh operator', null);
            }
            $shift = Shift::find($shift_id);
            if (empty($shift)) {
                return new ArrayResource(false, 'Shift anda tidak terdaftar', null);
            }
            $jabatan = Jabatan::find($jabatan_id);
            if (empty($jabatan)) {
                return new ArrayResource(false, 'Jabatan anda tidak terdaftar', null);
            }

            $presensi = Presensi::with('izin')->where('karyawan_id', $user->karyawan->id)->whereDate('tanggal', $date)->first();

            // ========>> presensi masuk

            if (empty($presensi)) {
                $validator = Validator::make($request->all(), [
                    'wajah_masuk' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'lokasi_masuk' => 'required',
                ]);
                if ($validator->fails()) {
                    return new ArrayResource(false, $validator->messages()->all(), null);
                }

                $shiftTime = $date->copy()->setTimeFromTimeString($shift->jam_masuk);

                $batas_sesudah_jam_masuk = $setting?->batas_waktu_sesudah_absen_masuk ?? 1;
                $batas_sebelum_jam_masuk = $setting?->batas_waktu_sebelum_absen_masuk ?? 1;
                $batas_keterlambatan = $setting?->batas_waktu_keterlambatan ?? 30;

                if ($date->greaterThan($shiftTime->copy()->addHours($batas_sesudah_jam_masuk))) {
                    return new ArrayResource(false, 'Presensi tidak dapat dilakukan lebih dari ' . $batas_sesudah_jam_masuk . ' jam setelah jam masuk shift.', null);
                }
                if ($date->lessThan($shiftTime->copy()->subHours($batas_sebelum_jam_masuk))) {
                    return new ArrayResource(
                        false,
                        'Presensi tidak dapat dilakukan lebih awal dari ' . $batas_sebelum_jam_masuk . ' jam sebelum jam masuk shift.',
                        null
                    );
                }

                $selisih = $shiftTime->diffInMinutes($date, false);
                $status = ($selisih > $batas_keterlambatan) ? 'terlambat' : 'tepat waktu';

                $wajah_masuk = $request->file('wajah_masuk');
                $path = $wajah_masuk->storeAs(
                    "$perusahaan_id/presensi/masuk/" . $date->format('d M Y'),
                    $wajah_masuk->hashName(),
                    'public'
                );

                $presensi = Presensi::create([
                    'perusahaan_id' => $perusahaan_id,
                    'kantor_id' => $kantor_id,
                    'karyawan_id' => $karyawan_id,
                    'shift_id' => $shift_id,
                    'jabatan_id' => $jabatan_id,
                    'gaji_id' => $gaji_id,
                    'tanggal' => $date->format('d-M-Y'),
                    'status_masuk' => 'H',
                    'jam_masuk' => $date->format('H:i:s'),
                    'lokasi_masuk' => $request->lokasi_masuk,
                    'wajah_masuk' => $path,
                    'is_lembur' => $request->is_lembur,
                    'status' => $status,
                ]);

                $response = $this->getDetailDataPresensi($presensi->id);
                return new ArrayResource(true, 'presensi masuk berhasil', $response);
            }


            // ========>> presensi keluar

            if (!empty($presensi)) {
                $validator = Validator::make($request->all(), [
                    'wajah_keluar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'lokasi_keluar' => 'required',
                ]);
                if ($validator->fails()) {
                    return new ArrayResource(false, $validator->messages()->all(), null);
                }

                if ($presensi->status_keluar != null && $presensi->izin) {
                    return new ArrayResource(false, 'anda sudah melakukan presensi masuk dan keluar pada hari ini', null);
                }

                $batas_sebelum_jam_keluar = (int) ($setting?->batas_waktu_sebelum_absen_keluar ?? 1);

                $shiftKeluarTime = Carbon::createFromFormat('H:i:s', $shift->jam_keluar);

                $waktuMinimalAbsen = $shiftKeluarTime->copy()->subHours($batas_sebelum_jam_keluar);

                if ($date->lt($waktuMinimalAbsen)) {
                    return new ArrayResource(false, 'Belum waktunya absen pulang!', null);
                }

                $wajah_keluar = $request->file('wajah_keluar');
                $path = $wajah_keluar->storeAs(
                    "$perusahaan_id/presensi/keluar/" . $date->format('d M Y'),
                    $wajah_keluar->hashName(),
                    'public'
                );
                /** @var Presensi $presensi */
                $presensi?->update([
                    'status_keluar' => 'H',
                    'jam_keluar' => $date->format('H:i:s'),
                    'lokasi_keluar' => $request->lokasi_keluar,
                    'wajah_keluar' => $path,
                    'is_lembur' => $request->is_lembur,
                ]);
                $response = $this->getDetailDataPresensi($presensi->id);
                return new ArrayResource(true, 'presensi keluar berhasil', $response);
            }
        } catch (\Throwable $th) {
            Log::info('PRESENSI ERROR ' . $th->getMessage());
            return new ArrayResource(false, $th->getMessage(), null);
        }
    }

    //=======================>>>>> helper get data
    private function getDetailDataPresensi($id): array
    {
        $data = Presensi::with(['perusahaan', 'karyawan', 'shift', 'jabatan', 'gaji', 'izin', 'kantor'])->findOrFail($id);
        $result =  [
            'id' => $data->id ?? null,
            'perusahaan' => $data->perusahaan?->nama_perusahaan ?? null,
            'kantor' => $data->kantor?->nama_kantor ?? null,
            'karyawan' => $data->karyawan?->user?->name ?? null,
            'shift' => $data->shift?->nama_shift ?? null,
            'jabatan' => $data->jabatan?->nama_jabatan ?? null,
            'gaji' => $data->gaji?->gaji_harian ?? null,
            'tanggal' => $data->tanggal ?? null,
            'status_masuk' => $data->status_masuk ?? null,
            'status_keluar' => $data->status_keluar ?? null,
            'jam_masuk' => $data->jam_masuk != null ? Carbon::parse($data->jam_masuk)->format('H:i') :  null,
            'jam_keluar' =>  $data->jam_keluar != null ? Carbon::parse($data->jam_keluar)->format('H:i') :  null,
            'lokasi_masuk' => $data->lokasi_masuk ?? null,
            'lokasi_keluar' => $data->lokasi_keluar ?? null,
            'wajah_masuk' => $data->wajah_masuk == null ? null : url('storage/' . $data->wajah_masuk),
            'wajah_keluar' => $data->wajah_keluar == null ? null : url('storage/' . $data->wajah_keluar),
            'is_lembur' => $data->is_lembur ?? null,
            'status' => $data->status ?? null,
            'izin' => [
                'id' => $data->izin?->id,
                'presensi_id' => $data->izin?->presensi_id,
                'karyawan_id' => $data->izin?->karyawan_id,
                'perusahaan_id' => $data->izin?->perusahaan_id,
                'izin' => $data->izin?->izin,
                'deskripsi' => $data->izin?->deskripsi,
                'file' => url('storage/' . $data->izin?->file)  ?? null,
            ]
        ];
        return $result;
    }
}
