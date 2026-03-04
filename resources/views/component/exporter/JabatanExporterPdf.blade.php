<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Jabatan</title>
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
                <th colspan="11" style="text-align:center; font-size:15px; height:50px;">
                    DATA JABATAN {{ $json['perusahaan'] }} <br>
                </th>
            </tr>
            <tr>
                <th rowspan="2" style="width: 1%;">No</th>
                <th style="width: 80px;" rowspan="2">kode Jabatan</th>
                <th style="width: 150px;" rowspan="2">nama Jabatan</th>
                <th rowspan="2">Deskripsi</th>
                <th style="width: 50px;" rowspan="2">Hari Kerja</th>
                <th colspan="2">Gaji</th>
                <th colspan="4">POTONGAN GAJI</th>
            </tr>
            <tr>
                <th style="width: 80px;">Bulanan</th>
                <th style="width: 80px;">Lembur</th>
                <th style="width: 23px;">S</th>
                <th style="width: 23px;">I</th>
                <th style="width: 23px;">A</th>
                <th style="width: 23px;">T</th>
            </tr>
        </thead>
        <tbody>
            <!-- Contoh data -->

            @foreach ($json['data_jabatan'] as $data )
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data['kode_jabatan'] }} </td>
                <td>{{ $data['nama_jabatan'] }} </td>
                <td>{{ $data['deskripsi'] }} </td>
                <td>{{ $data['jumlah_hari_kerja'] }} Hari</td>
                <td>Rp.{{ $data['gaji_bulanan'] }} </td>
                <td>Rp.{{ $data['gaji_lembur'] }} </td>
                <td>{{ $data['potongan_sakit'] }}%</td>
                <td>{{ $data['potongan_izin'] }}%</td>
                <td>{{ $data['potongan_alpha'] }}%</td>
                <td>{{ $data['potongan_tidak_absen_keluar'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>