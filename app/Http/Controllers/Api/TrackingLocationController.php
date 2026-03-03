<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArrayResource;
use App\Models\LiveLocationkaryawan;
use App\Models\User;
use Illuminate\Http\Request;

class TrackingLocationController extends Controller
{
    public function sendLocation(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $lat = $request->latitude;
            $long = $request->longitude;
            $address = $request->address;
            $presensi_id = $request->presensi_id;

            $user = User::with(['karyawan'])->where('token', $token)->first();
            if (empty($user)) {
                return new ArrayResource(false, 'token anda tidak valid, silahkan melakukan login ulang', null);
            }
            $karyawan_id = $user->karyawan->id;

            $liveLocation = LiveLocationkaryawan::where('karyawan_id', $karyawan_id)->first();
            if (empty($liveLocation)) {
                LiveLocationKaryawan::create(
                    [
                        'karyawan_id' => $karyawan_id,
                        'presensi_id' => $presensi_id,
                        'latitude' => $lat,
                        'longitude' => $long,
                        'address' => $address,
                        'is_active' => true,
                    ]
                );
            }

            if ($liveLocation) {
                $liveLocation->update([
                    'karyawan_id' => $karyawan_id,
                    'presensi_id' => $presensi_id,
                    'latitude' => $lat,
                    'longitude' => $long,
                    'address' => $address,
                    'is_active' => true,
                ]);
            }
            return new ArrayResource(true, 'tracking lokasi sedang berjalan', null);
        } catch (\Throwable $th) {
            return new ArrayResource(false, $th->getMessage(), null);
        }
    }

    public function stopLocation(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $lat = $request->latitude;
            $long = $request->longitude;
            $address = $request->address;
            $presensi_id = $request->presensi_id;

            $user = User::with(['karyawan'])->where('token', $token)->first();
            if (empty($user)) {
                return new ArrayResource(false, 'token anda tidak valid, silahkan melakukan login ulang', null);
            }
            $karyawan_id = $user->karyawan->id;

            $liveLocation = LiveLocationkaryawan::where('karyawan_id', $karyawan_id)->first();
            if ($liveLocation) {
                $liveLocation->update([
                    'karyawan_id' => $karyawan_id,
                    'presensi_id' => $presensi_id,
                    'latitude' => $lat,
                    'longitude' => $long,
                    'address' => $address,
                    'is_active' => false,
                ]);
            }
            return new ArrayResource(true, 'tracking lokasi telah berhenti', null);
        } catch (\Throwable $th) {
            return new ArrayResource(false, $th->getMessage(), null);
        }
    }
}
