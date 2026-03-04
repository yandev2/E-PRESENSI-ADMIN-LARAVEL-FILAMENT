<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            /* font agak kecil agar muat di A4 */
        }

        table {
            width: 100%;
            border-collapse: collapse;

            /* supaya kolom tetap rapi */
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 8px;
            word-wrap: break-word;
            /* pecah kata panjang agar tidak meluber */
            text-align: left;
        }

        th {
            background-color: #3a3dff;
            color: white;
            text-align: center
        }

        thead {
            display: table-header-group;
            /* penting supaya header muncul di tiap halaman PDF */
        }

        tbody {
            display: table-row-group;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="17" style="text-align:center; font-size:15px; height:50px;">
                    DATA KARYAWAN {{ $json['perusahaan'] }} <br>
                </th>
            </tr>
            <tr>
                <th style="width: 1%;">No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Nip</th>
                <th>Nik</th>
                <th>Agama</th>
                <th>Tempat lahir</th>
                <th>Tanggal lahir</th>
                <th>JK</th>
                <th>Nomor telp</th>
                <th>Alamat domisili</th>
                <th>Pendidikan terakhir</th>
                <th>Jabatan</th>
                <th>Shift</th>
                <th>Status dinas</th>
                <th>Tipe karyawan</th>
                <th>Status karyawan</th>

            </tr>
        </thead>
        <tbody>
            <!-- Contoh data -->

            @foreach ($json['data_karyawan'] as $data )
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data['nama'] }}</td>
                <td>{{ $data['email'] }}</td>
                <td>{{ $data['nip'] }}</td>
                <td>{{ $data['nik'] }}</td>
                <td>{{ $data['agama'] }}</td>
                <td>{{ $data['tempat_lahir'] }}</td>
                <td>{{ $data['tanggal_lahir'] }}</td>
                <td>{{ $data['jenis_kelamin'] }}</td>
                <td>{{ $data['nomor_telp'] }}</td>
                <td>{{ $data['alamat_domisili'] }}</td>
                <td>{{ $data['pendidikan_terakhir'] }}</td>
                <td>{{ $data['jabatan'] }}</td>
                <td>{{ $data['shift'] }}</td>
                <td>{{ $data['status_dinas'] }}</td>
                <td>{{ $data['tipe_karyawan'] }}</td>
                <td>{{ $data['status_karyawan'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>