<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArrayResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => ['required'],
                ],
                [
                    'email.required' => 'Email wajib diisi.',
                    'email.email' => 'Format email tidak valid.',
                    'password.required' => 'Password wajib diisi.',
                ]
            );

            if ($validator->fails()) {
                return new ArrayResource(false, $validator->messages()->all(), null);
            }

            $email = $request->email;
            $password = $request->password;

            $user = User::with(['karyawan'])->where('email', $email)->first();
            if (empty($user)) {
                return new ArrayResource(false, 'email anda tidak valid', null);
            }
            if (!Hash::check($password, $user->password)) {
                return new ArrayResource(false, 'password anda tidak valid', null);
            }
            $user->token =  Str::random(80);
            $user->save();
            $response = $this->getUserData($user);

            return new ArrayResource(true, 'login berhasil', $response);
        } catch (\Throwable $th) {
            return new ArrayResource(false, $th->getMessage(), null);
        }
    }

    public function autoLogin(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $user = User::where('token', $token)->first();
            if (empty($user)) {
                return new ArrayResource(false, 'sesi masuk anda telah berakhir', null);
            }
            $response = $this->getUserData($user);
            return new ArrayResource(true, 'login berhasil', $response);
        } catch (\Throwable $th) {
            return new ArrayResource(false, $th->getMessage(), null);
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $user = User::where('token', $token)->first();
            if (empty($user)) {
                return new ArrayResource(false, 'token anda tidak valid', null);
            }

            $user->token = null;
            $user->save();

            return new ArrayResource(true, 'logout berhasil', true);
        } catch (\Throwable $th) {
            return new ArrayResource(false, $th->getMessage(), null);
        }
    }

    public function updatePassword(Request $request)
    {
        try {

            $token = $request->bearerToken();
            $old_password = $request->old_password;
            $new_password = $request->new_password;

            $user = User::where('token', $token)->first();
            if (empty($user)) {
                return new ArrayResource(false, 'token anda tidak valid. silahkan melakukan login ulang terlebih dahulu', null);
            }
            if (!Hash::check($old_password, $user->password)) {
                return new ArrayResource(false, 'password lama anda tidak valid', null);
            }

            $user->password = Hash::make($new_password);
            $user->token =  Str::random(80);
            $user->save();

            return new ArrayResource(true, 'password telah berhasil diperbarui', $user->token);
        } catch (\Throwable $th) {
            return new ArrayResource(false, $th->getMessage(), null);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $token = $request->bearerToken();

            $user = User::with('karyawan')->where('token', $token)->first();
            if (empty($user)) {
                return new ArrayResource(false, 'token anda tidak valid. silahkan melakukan login ulang terlebih dahulu', null);
            }
            $newAvatar = null;
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $path = $avatar->storeAs($user->perusahaan_id . '/perusahaan/karyawan/profile', $avatar->hashName());

                // Hapus foto lama jika ada
                if ($user->avatar) {
                    if (!in_array($user->avatar, ['karyawan/default_sys_l.jpg', 'karyawan/default_sys.jpg'])) {
                        Storage::disk('public')->delete($user->avatar);
                    };
                }
                $newAvatar = $path;
            }

            $user->karyawan->update($request->karyawan);
            if (!empty($newAvatar)) {
                $user->avatar = $newAvatar;
            }

            $user->update($request->user);
        } catch (\Throwable $th) {
            return new ArrayResource(false, $th->getMessage(), null);
        }
    }

    public function updateFaceId(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $face_id = $request->face_id;

            $user = User::where('token', $token)->first();
            if (empty($user)) {
                return new ArrayResource(false, 'token anda tidak valid. silahkan melakukan login ulang terlebih dahulu', null);
            }

            $user->face_id = $face_id;
            $user->save();

            return new ArrayResource(true, 'face id telah berhasil diperbarui', $user->face_id);
        } catch (\Throwable $th) {
            return new ArrayResource(false, $th->getMessage(), null);
        }
    }

    private function getUserData(User $user)
    {
        $roles = $user->roles->pluck('name')->last();

        $karyawan = $user->karyawan ? [
            'id' =>  $user->karyawan?->id,
            'nik' => $user->karyawan?->nik,
            'nip' => $user->karyawan?->nip,
            'pendidikan_terakhir' => $user->karyawan?->pendidikan_terakhir,
            'agama' => $user->karyawan?->agama,
            'tempat_lahir' => $user->karyawan?->tempat_lahir,
            'tanggal_lahir' => $user->karyawan?->tanggal_lahir,
            'jenis_kelamin' => $user->karyawan?->jenis_kelamin,
            'status_pernikahan' => $user->karyawan?->status_pernikahan,
            'alamat_ktp' => $user->karyawan?->alamat_ktp,
            'alamat_domisili' => $user->karyawan?->alamat_domisili,
            'tipe_karyawan' => $user->karyawan?->tipe_karyawan,
            'status_karyawan' => $user->karyawan?->status_karyawan,
            'nomor_telp' => $user->karyawan?->nomor_telp,
            'status_dinas' => $user->karyawan?->status_dinas,
        ] : null;

        $jabatan = $user->karyawan?->jabatan ? [
            'id' =>  $user->karyawan?->jabatan?->id,
            'kode_jabatan' => $user->karyawan?->jabatan?->kode_jabatan ?? null,
            'nama_jabatan' => $user->karyawan?->jabatan?->nama_jabatan ?? null,
            'deskripsi' => $user->karyawan?->jabatan?->deskripsi ?? null,
        ] : null;

        $kantor = $user->karyawan?->kantor ? [
            'id' =>  $user->karyawan?->kantor?->id,
            'nama_kantor' => $user->karyawan?->kantor?->nama_kantor ?? null,
            'lokasi' => $user->karyawan?->kantor?->lokasi ?? null,
            'radius' =>  $user->karyawan?->kantor?->radius ?? null
        ] : null;
        $shift = $user->karyawan?->shift ? [
            'id' =>  $user->karyawan?->shift?->id,
            'nama_shift' => $user->karyawan?->shift?->nama_shift ?? null,
            'jam_masuk' => $user->karyawan?->shift?->jam_masuk != null ? Carbon::parse($user->karyawan?->shift?->jam_masuk)->format('H:i') :  null,
            'jam_keluar' => $user->karyawan?->shift?->jam_keluar != null ? Carbon::parse($user->karyawan?->shift?->jam_keluar)->format('H:i') :  null,
        ] : null;

        $perusahaan = $user->perusahaan ? [
            'id' =>  $user->perusahaan?->id,
            'nama_perusahaan' => $user->perusahaan?->nama_perusahaan ?? null,
            'npwp' => $user->perusahaan?->npwp ?? null,
            'kontak' => $user->perusahaan?->kontak ?? null,
            'email' => $user->perusahaan?->email ?? null,
            'site' => $user->perusahaan?->site ?? null,
            'deskripsi' => $user->perusahaan?->deskripsi ?? null,
            'alamat' => $user->perusahaan?->alamat ?? null,
            'lokasi' => $user->perusahaan?->lokasi ?? null,
            'logo' => $user->perusahaan?->logo == null ?  url('storage/perusahaan/default_logo.png') : url('storage/' .  $user->perusahaan?->logo),
            'status' => $user->perusahaan?->status ?? null,
        ] : null;

        return [
            'id' =>  $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $user->token,
            'role' => $roles,
            'face_id' => $user->face_id,
            'avatar' => $user->avatar == null ?  url('storage/karyawan/default_sys_l.jpg') : url('storage/' .  $user["avatar"]),
            "karyawan" => $karyawan,
            "jabatan" => $jabatan,
            "kantor" => $kantor,
            "shift" => $shift,
            "perusahaan" => $perusahaan
        ];
    }
}
