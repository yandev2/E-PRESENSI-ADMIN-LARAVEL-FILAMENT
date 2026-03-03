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
    </head>

    <body>
        <table>
            <thead>
                <tr>
                    <th colspan={{ $jumlahHari+7 }}
                        style="text-align: center; font-size: 15px; padding: 20px; height: 50px; background-color:blue; color: white; font-weight: 800; vertical-align: middle;">
                        DATA PRESENSI {{ $json['perusahaan'] }} <br>
                    </th>
                </tr>
                <tr>
                    <th style="border:solid gray; background-color:red;font-weight:600; text-align: center;color:white; vertical-align: middle;"
                        colspan="2">NOMOR</th>
                    <th style="border:solid gray; background-color:red;width:200px;font-weight:600; text-align: center;color:white; vertical-align: middle;"
                        rowspan="3">NAMA</th>
                    <th style="border:solid gray; background-color:red;font-weight:600; width: 40px; text-align: center;color:white; vertical-align: middle;"
                        rowspan="3">L/P</th>
                    <th style="border:solid gray; background-color:red;font-weight:600; text-align: center;color:white; vertical-align: middle;"
                        colspan={{ $jumlahHari }}>BULAN {{ $bulans }}</th>
                    <th style="border:solid gray; background-color:red;width:200px;font-weight:600; text-align: center;color:white; vertical-align: middle;"
                        colspan="3" rowspan="2">JMLH</th>
                </tr>

                <tr>
                    <th style="border:solid gray; background-color:red;width:30px;font-weight:600; text-align: center;color:white; vertical-align: middle;"
                        rowspan="2">NO</th>
                    <th style="border:solid gray; background-color:red;width:200px;font-weight:600; text-align: center;color:white; vertical-align: middle;"
                        rowspan="2">NIP</th>
                    <th style="border:solid gray; background-color:red; font-weight:600; text-align: center;color:white; vertical-align: middle;"
                        colspan={{ $jumlahHari }}>TANGGAL</th>
                </tr>

                <tr>
                    @for ($i = 1; $i <= $jumlahHari; $i++) @php $isMinggu=in_array($i, $mingguList); @endphp <th
                        style="width: 30px; text-align: center; {{ $isMinggu ? 'background-color: #ffcccc; color: red;' : 'background-color: orange; color: white;' }} ">
                        {{ $i }}
                        </th>
                        @endfor
                        <th
                            style="border:solid gray; background-color:green;width:70px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                            H</th>
                        <th
                            style="border:solid gray; background-color:red;width:70px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                            I</th>
                        <th
                            style="border:solid gray; background-color:orange;width:70px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                            S</th>
                </tr>
            </thead>
            <tbody>
                @foreach($json['presensi'] as $presensi)
                <tr>
                    <td style="background-color:aliceblue;border:solid gray; text-align: center">{{ $loop->iteration }}
                    </td>
                    <td style="background-color:aliceblue;border:solid gray; text-align: left; padding: 10px;">{{
                        $presensi['nip'] }}</td>
                    <td style="background-color:aliceblue;border:solid gray; text-align: left; padding: 10px;">{{
                        $presensi['nama'] }}</td>
                    <td style="background-color:aliceblue;border:solid gray; text-align: center; padding: 10px;">{{
                        $presensi['jk']}}</td>
                    @for($i = 1; $i <= $jumlahHari; $i++) @php $currentDate=Carbon::createFromDate($tahun, $bulan, $i)->
                        format('Y-m-d');
                        $index = array_search($currentDate, $presensi['tanggal']);
                        $status = ($index !== false) ? $presensi['status_masuk'][$index] : '';
                        $isMinggu = in_array($i, $mingguList);
                        @endphp
                        <td
                            style="background-color:aliceblue;border:solid gray; text-align: center; {{ $isMinggu ? 'background-color: #ffcccc; color: red;' : '' }}">
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
                        <td style="background-color:aliceblue;border:solid gray; text-align: center;">{{ $hadir }}</td>
                        <td style="background-color:aliceblue;border:solid gray; text-align: center;">{{ $izin }}</td>
                        <td style="background-color:aliceblue;border:solid gray; text-align: center;">{{ $sakit }}</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-size: 10px; padding: 20px; background-color:aliceblue; color: black; vertical-align: middle;"
                        colspan={{ $jumlahHari+4 }}> SUMMARY PRESENSI KARYAWAN {{ $json['perusahaan'] }}</td>
                    <td
                        style="border:solid gray; background-color:green;width:70px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                        {{ $json['total']['H']}}</td>
                    <td
                        style="border:solid gray; background-color:red;width:70px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                        {{ $json['total']['I']}}</td>
                    <td
                        style="border:solid gray; background-color:orange;width:70px;font-weight:600; text-align: center;color:white; vertical-align: middle;">
                        {{ $json['total']['S']}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>

    </html>