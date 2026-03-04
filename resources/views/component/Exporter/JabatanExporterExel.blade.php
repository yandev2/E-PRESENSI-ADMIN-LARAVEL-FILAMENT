<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Jabatan</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="11"
                    style="text-align: center;vertical-align: middle;font-size: 15px;height: 50px;background-color:blue;color: white; font-weight: 800;">
                    DATA JABATAN {{ $json['perusahaan'] }} <br>
                </th>
            </tr>
            <tr>
                <th style="border:solid gray; background-color:red;width:30px;font-weight:600; text-align: center;color:white; vertical-align: middle;"
                    rowspan="2">No</th>
                <th style="border:solid gray; background-color:red;width:80px;font-weight:600; text-align: center;color:white; vertical-align: middle;"
                    rowspan="2">kode Jabatan</th>
                <th style="border:solid gray; background-color:red;width:180px;font-weight:600; text-align: center;color:white; vertical-align: middle;"
                    rowspan="2">nama Jabatan</th>
                <th style="border:solid gray; background-color:red;width:300px;font-weight:600; text-align: center;color:white; vertical-align: middle;"
                    rowspan="2">Deskripsi</th>
                <th style="border:solid gray; background-color:red;width:80px;font-weight:600; text-align:
                    center;color:white; vertical-align: middle;" rowspan="2">Hari Kerja</th>
                <th style="border:solid gray; background-color:red;font-weight:600; text-align: center;color:white; vertical-align: middle;"
                    colspan="2">Gaji</th>
                <th style="border:solid gray; background-color:red;font-weight:600; text-align: center;color:white; vertical-align: middle;"
                    colspan="4">POTONGAN GAJI</th>
            </tr>
            <tr>
                <th
                    style="border:solid gray; background-color:red;width:150px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                    Bulanan</th>
                <th
                    style="border:solid gray; background-color:red;width:150px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                    Lembur</th>
                <th
                    style="border:solid gray; background-color:red;width:50px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                    S</th>
                <th
                    style="border:solid gray; background-color:red;width:50px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                    I</th>
                <th
                    style="border:solid gray; background-color:red;width:50px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                    A</th>
                <th
                    style="border:solid gray; background-color:red;width:50px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                    T</th>
            </tr>
        </thead>
        <tbody>
            <!-- Contoh data -->

            @foreach ($json['data_jabatan'] as $data )
            <tr>
                <td style="background-color:aliceblue;border:solid gray;">{{ $loop->iteration }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['kode_jabatan'] }} </td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['nama_jabatan'] }} </td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['deskripsi'] }} </td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['jumlah_hari_kerja'] }} Hari</td>
                <td style="background-color:aliceblue;border:solid gray;">Rp.{{ $data['gaji_bulanan'] }} </td>
                <td style="background-color:aliceblue;border:solid gray;">Rp.{{ $data['gaji_lembur'] }} </td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['potongan_sakit'] }}%</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['potongan_izin'] }}%</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['potongan_alpha'] }}%</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['potongan_tidak_absen_keluar'] }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>