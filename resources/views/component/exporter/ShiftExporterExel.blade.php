<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Shift</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="5"
                    style="text-align: center; vertical-align: middle; font-size: 15px; height: 50px; background-color:blue; color: white; font-weight: 800;">
                    DATA SHIFT {{ $json['perusahaan'] }}
                </th>
            </tr>
            <tr>
                <th
                    style="border:solid gray; background-color:red;width:30px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                    No</th>
                <th
                    style="border:solid gray; background-color:red;width:150px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                    Nama Shift</th>
                <th
                    style="border:solid gray; background-color:red;width:80px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                    Jam Masuk</th>
                <th
                    style="border:solid gray; background-color:red;width:80px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                    Jam Keluar</th>
                <th
                    style="border:solid gray; background-color:red;width:130px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                    Jumlah Karyawan</th>
            </tr>
        </thead>
        <tbody>
            <!-- Contoh data -->

            @foreach ($json['data_shift'] as $data )
            <tr>
                <td style="background-color:aliceblue;text-align: center; border:solid gray;">{{ $loop->iteration }}
                </td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['nama_shift'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['jam_masuk'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['jam_keluar'] }}</td>
                <td style="background-color:aliceblue;border:solid gray;">{{ $data['jumlah_karyawan'] }} Karyawan</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>