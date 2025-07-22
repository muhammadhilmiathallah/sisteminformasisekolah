<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 40px;
            font-size: 13px;
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
            width: 85px;
        }

        .kop-logo img {
            width: 85px;
        }

        .kop-text {
            display: table-cell;
            text-align: center;
            color: #006400;
            vertical-align: middle;
        }

        .kop-text h1 {
            font-size: 26px;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }

        .kop-text h1 .plus {
            font-size: 17px;
            vertical-align: super;
        }

        .kop-text h2 {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 1px;
            margin: 0;
            line-height: 1.2;
        }

        .kop-text p {
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            line-height: 1.4;
        }

        .kop-surat hr {
            border: 0;
            height: 3px;
            background: #009344;
            margin: 6px 0 0 0;
        }

        .kop-surat hr+hr {
            height: 1.5px;
        }

        .info-siswa {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #444;
            padding: 6px 8px;
            text-align: center;
        }

        th {
            background: #eee;
        }

        .total-row {
            font-weight: 700;
            background: #f9f9f9;
        }

        .ttd {
            margin-top: 50px;
            width: 100%;
        }

        .ttd td {
            border: none;
            text-align: center;
            padding-top: 50px;
        }

        .text-left {
            text-align: left;
        }
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


    <div class="info">
        <strong>Laporan:</strong> {{ $judul }} <br>
        <strong>Dicetak oleh:</strong> {{ $username ?? 'Tidak diketahui' }} <br>
        <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pengirim</th>
                <th>Kelas</th>
                <th>Judul</th>
                <th>Isi</th>
                <th>Tanggapan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kritikSarans as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                     <td>{{ $item->siswa->nama_lengkap_siswa ?? '-' }}</td>
                       <td>{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $item->judul ?? '-' }}</td>
                    <td>{{ $item->isi ?? '-' }}</td>
                    <td>{{ $item->tanggapan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem pada {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    </div>

</body>
</html>
