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

        .info-siswa {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

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
         hr { border: 1px solid black; margin-top: 4px; }
    </style>
</head>
<body>

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
        <p><strong>Nama Peserta Didik</strong>: {{ $siswa->nama_lengkap_siswa }}</p>
        <p><strong>NISN/NIS</strong>: {{ $siswa->nisn ?? '-' }} / {{ $siswa->nis ?? '-' }}</p>
        <p><strong>Kelas</strong>: {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
        <p><strong>Tahun Pelajaran</strong>: {{ $tahun->tahun_pelajaran }}</p>
        <p><strong>Nama Sekolah</strong>: SD Islam Plus Al Muhajirin</p>
        <p><strong>Alamat</strong>: Jl. Kasuari No. 1 Komplek Pondok Pucung Indah I, Pondok Pucung, Pondok Aren, Tangerang Selatan, Banten</p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="text-left">Mata Pelajaran</th>
                    <th>UH</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Rata-rata</th>
                    <th>Grade</th>
                    <th>Status Akademis</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_rata2 = 0;
                    $jumlah_mapel = count($nilai);

                    function getStatusAkademis($grade) {
                        return match ($grade) {
                            'A', 'B', 'C'  => 'Tuntas',
                            'D' => 'Belum Tuntas',
                            'E'       => 'Tidak Lulus',
                            default   => '-',
                        };
                    }
                @endphp

                @foreach ($nilai as $n)
                    <tr>
                        <td class="text-left">{{ $n->mataPelajaran->nama_mapel ?? '-' }}</td>
                        <td>{{ $n->nilai_tugas }}</td>
                        <td>{{ $n->nilai_uts }}</td>
                        <td>{{ $n->nilai_ujian }}</td>
                        <td>{{ $n->rata_rata }}</td>
                        <td>{{ $n->grade }}</td>
                        <td>{{ getStatusAkademis($n->grade) }}</td>
                    </tr>
                    @php $total_rata2 += $n->rata_rata; @endphp
                @endforeach

                <tr class="total-row">
                    <td class="text-left" colspan="4">Rata-rata Keseluruhan</td>
                    <td colspan="3">
                        {{ $jumlah_mapel > 0 ? number_format($total_rata2 / $jumlah_mapel, 2) : '0.00' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

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
                (___________________)
            </td>
        </tr>
    </table>

</body>
</html>
