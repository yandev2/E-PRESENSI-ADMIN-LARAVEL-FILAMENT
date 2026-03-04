<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Shift</title>
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
                <th colspan="5" style="text-align:center; font-size:15px; height:50px;">
                    DATA SHIFT {{ $json['perusahaan'] }} <br>
                </th>
            </tr>
            <tr>
                <th style="width: 1%;">No</th>
                <th>Nama Shift</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Jumlah Karyawan</th>
            </tr>
        </thead>
        <tbody>
            <!-- Contoh data -->

            @foreach ($json['data_shift'] as $data )
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data['nama_shift'] }}</td>
                <td>{{ $data['jam_masuk'] }}</td>
                <td>{{ $data['jam_keluar'] }}</td>
                <td>{{ $data['jumlah_karyawan'] }} Karyawan</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>