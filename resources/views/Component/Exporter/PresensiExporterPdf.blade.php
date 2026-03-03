@php
use Carbon\Carbon;
$bulan = Carbon::parse($json['tanggal_awal'])->format('m');
$tahun = Carbon::parse($json['tanggal_awal'])->format('Y');
$jumlahHari = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth; // jumlah hari di bulan
$mingguList = [];
$bulans = Carbon::parse($json['tanggal_awal'])->format('M Y');
for ($day = 1; $day <= $jumlahHari; $day++) { $tanggal=Carbon::createFromDate($tahun, $bulan, $day); if ($tanggal->
    format('D') == 'Sun') {
    $mingguList[] = $day;
    }
    }
    @endphp

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Data Presensi</title>
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
                padding-bottom: 5px;
                padding-top: 5px;
                word-wrap: break-word;
                /* pecah kata panjang agar tidak meluber */
                text-align: left;
            }

            th {
                background-color: #acacac;
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
                    <th colspan={{ $jumlahHari+7 }} style="text-align:center; font-size:15px; height:50px;">
                        DATA PRESENSI {{ $json['perusahaan'] }} <br>
                    </th>
                </tr>
                <tr>
                    <th colspan="2">NOMOR</th>
                    <th rowspan="3">NAMA</th>
                    <th style="width: 23px; " rowspan="3">L/P</th>
                    <th colspan={{ $jumlahHari }}>BULAN {{ $bulans }}</th>
                    <th colspan="3" rowspan="2">JMLH</th>
                </tr>

                <tr>
                    <th style="width: 20px;" rowspan="2">NO</th>
                    <th style="width: 150px;" rowspan="2">NIP</th>
                    <th colspan={{ $jumlahHari }}>TANGGAL</th>
                </tr>

                <tr>
                    @for ($i = 1; $i <= $jumlahHari; $i++) @php $isMinggu=in_array($i, $mingguList); @endphp <th
                        style="width: 17px; {{ $isMinggu ? 'background-color: #ffcccc; color: red;' : '' }} ">
                        {{ $i }}
                        </th>
                        @endfor
                        <th style="width: 28px; background-color: green;">H</th>
                        <th style="width: 28px; background-color:red;">I</th>
                        <th style="width: 28px; background-color: orange;">S</th>
                </tr>
            </thead>
            <tbody>
                @foreach($json['presensi'] as $presensi)
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}</td>
                    <td style="padding: 5px">{{ $presensi['nip'] }}</td>
                    <td style="padding: 5px">{{ $presensi['nama'] }}</td>
                    <td style=" text-align: center;">{{ $presensi['jk']}}</td>
                    @for($i = 1; $i <= $jumlahHari; $i++) @php $currentDate=Carbon::createFromDate($tahun, $bulan, $i)->
                        format('Y-m-d');
                        $index = array_search($currentDate, $presensi['tanggal']);
                        $status = ($index !== false) ? $presensi['status_masuk'][$index] : '';
                        $isMinggu = in_array($i, $mingguList);
                        @endphp
                        <td
                            style=" text-align: center; {{ $isMinggu ? 'background-color: #ffcccc; color: red;' : '' }}">
                            {{ $status }}
                        </td>
                        @endfor
                        @php
                        // Hitung jumlah tiap status
                        $statusCount = array_count_values($presensi['status_masuk']);
                        $hadir = $statusCount['H'] ?? 0;
                        $izin = $statusCount['I'] ?? 0;
                        $sakit = $statusCount['S'] ?? 0;

                        @endphp
                        <td style=" text-align: center;">{{ $hadir }}</td>
                        <td style=" text-align: center;">{{ $izin }}</td>
                        <td style=" text-align: center;">{{ $sakit }}</td>
                </tr>
                @endforeach


            </tbody>
        </table>
    </body>

    </html>