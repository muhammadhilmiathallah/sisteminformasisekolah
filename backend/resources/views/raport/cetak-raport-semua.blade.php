 <!DOCTYPE html>
<html>
<head>
     <meta charset="UTF-8">
    <title>Raport – {{ $siswa->nama_lengkap_siswa }}</title>
     <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 30px;
            font-size: 12px;
        }

        .kop-surat {
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .kop-wrap {
            display: table;
            width: 100%;
        }

        .kop-logo {
            display: table-cell;
            vertical-align: middle;
            width: 70px;
        }

        .kop-logo img {
            width: 70px;
        }

        .kop-text {
            display: table-cell;
            text-align: center;
            color: #006400;
            vertical-align: middle;
        }

        .kop-text h1 {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }

        .kop-text h1 .plus {
            font-size: 14px;
            vertical-align: super;
        }

        .kop-text h2 {
            font-size: 15px;
            font-weight: 700;
            margin: 0;
        }

        .kop-text p {
            font-size: 12px;
            font-weight: 600;
            margin: 0;
        }

        .kop-surat hr {
            border: 0;
            height: 2px;
            background: #009344;
            margin: 6px 0 0 0;
        }

        .kop-surat hr+hr {
            height: 1px;
        }

        table { border-collapse: collapse; margin-top: 10px; width: 100%; }
        th, td { border: 1px solid black; padding: 5px; text-align: center; }
         th {
            background: #eee;
        }

        .total-row {
            font-weight: 700;
            background: #f9f9f9;
        }

        .text-left {
            text-align: left;
        }

        .ttd {
            margin-top: 40px;
            width: 100%;
            text-align: center;
        }

        .ttd td {
            border: none;
            padding-top: 30px;
            font-size: 12px;
        }
        .page-break { page-break-after: always; }
        hr { border: 1px solid black; margin-top: 4px; }
    </style>
</head>
<body>

    @foreach($dataRaport as $data)

       <div class="kop-surat">
        <div class="kop-wrap">
            <div class="kop-logo">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS9IoPPynhb_4LGNoM_HgMoqezmkrv7wrmqZA&s" alt="Logo Yatami">
            </div>
            <div class="kop-text">
                <h1>SD ISLAM<span class="plus"> Plus </span>AL MUHAJIRIN</h1>
                <h2>YAYASAN TAMAN AL MUHAJIRIN 1407</h2>
                <p>Jl. Kasuari No. 1 Komplek Pondok Pucung Indah I,<br>
                    Pondok Pucung, Pondok Aren, Tangerang Selatan, Banten 15429</p>
            </div>
        </div>
        <hr><hr>
    </div>


        <h3 style="text-align:center;">HASIL NILAI SEMESTER</h3>

    <div class="info-siswa">
        <p><strong>Nama Peserta Didik</strong>: {{ $data['siswa']->nama_lengkap_siswa }}</p>
        <p><strong>NISN/NIS</strong>: {{ $data['siswa']->nisn ?? '-' }} / {{ $data['siswa']->nis ?? '-' }}</p>
        <p><strong>Kelas</strong>: {{ $data['siswa']->kelas->nama_kelas ?? '-' }}</p>
        <p><strong>Tahun Pelajaran</strong>: {{ $tahun->tahun_pelajaran }}</p>
        <p><strong>Nama Sekolah</strong>: SD Islam Plus Al Muhajirin</p>
        <p><strong>Alamat</strong>: Jl. Kasuari No. 1 Komplek Pondok Pucung Indah I, Pondok Pucung, Pondok Aren, Tangerang Selatan, Banten</p>
    </div>

               @php
    if (!function_exists('getStatusAkademis')) {
        function getStatusAkademis($grade) {
            switch ($grade) {
                case 'A':
                case 'B':
                case 'C':
                    return 'Tuntas';
                case 'D':
                    return 'Belum Tuntas';
                case 'E':
                    return 'Tidak Lulus';
                default:
                    return '-';
            }
        }
    }
@endphp

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>Nilai Tugas</th>
                <th>Nilai UTS</th>
                <th>Nilai Ujian</th>
                <th>Rata-rata</th>
                <th>Grade</th>
                <th>Status Akademis</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalRata = 0;
                $jumlahMapel = count($data['nilai']);
            @endphp

            @if($data['nilai']->isEmpty())
                <tr>
                    <td colspan="8"><em>Belum ada nilai yang diinput.</em></td>
                </tr>
            @else
                @foreach($data['nilai'] as $index => $nilai)
                    @php
                        $totalRata += $nilai->rata_rata;
                        $statusAkademis = getStatusAkademis($nilai->grade);
                    @endphp

                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ optional($nilai->mataPelajaran)->nama_mapel ?? '-' }}</td>
                        <td>{{ $nilai->nilai_tugas ?? '-' }}</td>
                        <td>{{ $nilai->nilai_uts ?? '-' }}</td>
                        <td>{{ $nilai->nilai_ujian ?? '-' }}</td>
                        <td>{{ number_format($nilai->rata_rata ?? 0, 2) }}</td>
                        <td>{{ $nilai->grade ?? '-' }}</td>
                        <td>{{ $statusAkademis }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>

        @if(!$data['nilai']->isEmpty())
              <tfoot>
    <tr>
        <th colspan="5" style="text-align: left; border-right: 1px solid black;">
            Rata-rata Keseluruhan
        </th>
        <th colspan="3" style="text-align: center; border-left: none; border-right: 1px solid black;">
            {{ $jumlahMapel > 0 ? number_format($totalRata / $jumlahMapel, 2) : '0.00' }}
        </th>
    </tr>
</tfoot>
        @endif
    </table>
    
    <table class="ttd">
        <tr>
            <td style="width: 33%;">
                Wali Murid<br><br><br><br>
                (________________________)
            </td>
            <td style="width: 34%;">
                Tangerang Selatan, {{ date('d-m-Y') }}<br>
                Kepala Sekolah<br><br><br><br>
                (Khojanah, S.Pd.)
            </td>
            <td style="width: 33%;">
                Wali Kelas<br><br><br><br>
                (________________________)
            </td>
        </tr>
    </table>

    <div class="page-break"></div>
@endforeach

</body>
</html>