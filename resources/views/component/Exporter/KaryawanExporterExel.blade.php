<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Karyawan</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="17"
                    style="text-align: center;vertical-align: middle;font-size: 15px;height: 50px;background-color:blue;color: white; font-weight: 800;">
                    DATA KARYAWAN {{ $json['perusahaan'] }}
                </th>
            </tr>
            <tr>
                <th
                    style="border:solid gray; background-color:red;width:30px;font-weight:600; text-align: center;color:white">
                    No</th>
                <th
                    style="border:solid gray; background-color:red;width:200px; font-weight:600; text-align: center;color:white">
                    Nama</th>
                <th
                    style="border:solid gray; background-color:red;width:130px;font-weight:600; text-align: center;color:white">
                    Email</th>
                <th
                    style="border:solid gray; background-color:red;width:130px;font-weight:600; text-align: center;color:white">
                    Nip</th>
                <th
                    style="border:solid gray; background-color:red;width:150px;font-weight:600; text-align: center;color:white">
                    Nik</th>
                <th
                    style="border:solid gray; background-color:red;width:100px;font-weight:600; text-align: center;color:white">
                    Agama</th>
                <th
                    style="border:solid gray; background-color:red;width:150px;font-weight:600; text-align: center;color:white">
                    Tempat lahir</th>
                <th
                    style="border:solid gray; background-color:red;width:150px;font-weight:600; text-align: center;color:white">
                    Tanggal lahir</th>
                <th
                    style="border:solid gray; background-color:red;width:50px;font-weight:600; text-align: center;color:white">
                    JK</th>
                <th
                    style="border:solid gray; background-color:red;width:150px;font-weight:600; text-align: center;color:white">
                    Nomor telp</th>
                <th
                    style="border:solid gray; background-color:red;width:200px;font-weight:600; text-align: center;color:white">
                    Alamat domisili</th>
                <th
                    style="border:solid gray; background-color:red;width:200px;font-weight:600; text-align: center;color:white">
                    Pendidikan terakhir</th>
                <th
                    style="border:solid gray; background-color:red;width:150px;font-weight:600; text-align: center;color:white">
                    Jabatan</th>
                <th
                    style="border:solid gray; background-color:red;width:150px;font-weight:600; text-align: center;color:white">
                    Shift</th>
                <th
                    style="border:solid gray; background-color:red;width:100px;font-weight:600; text-align: center;color:white">
                    Status dinas</th>
                <th
                    style="border:solid gray; background-color:red;width:80px;font-weight:600; text-align: center;color:white">
                    Tipe</th>
                <th
                    style="border:solid gray; background-color:red;width:80px;font-weight:600; text-align: center;color:white">
                    Status</th>
            </tr>
        </thead>
        <tbody>
            <!-- Contoh data -->

            @foreach ($json['data_karyawan'] as $data )
            <tr>
                <td style="background-color:aliceblue;text-align: center; border:solid gray;">{{ $loop->iteration }}
                </td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['nama'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['email'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['nip'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['nik'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['agama'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['tempat_lahir'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['tanggal_lahir'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['jenis_kelamin'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['nomor_telp'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['alamat_domisili'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['pendidikan_terakhir'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['jabatan'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['shift'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['status_dinas'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['tipe_karyawan'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['status_karyawan'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>