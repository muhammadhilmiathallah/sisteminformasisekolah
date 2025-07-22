<?php

namespace App\Http\Controllers\Api;

use App\Models\Siswa;
use App\Models\NilaiSiswa;
use Illuminate\Http\Request;
use App\Models\TahunPelajaran;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NilaiSiswaController extends Controller
{
    private function hitungRataRata($tugas, $uts, $ujian)
    {
        $total = 0;
        $bobot = 0;

        if (!is_null($tugas)) {
            $total += $tugas * 0.2;
            $bobot += 0.2;
        }

        if (!is_null($uts)) {
            $total += $uts * 0.4;
            $bobot += 0.4;
        }

        if (!is_null($ujian)) {
            $total += $ujian * 0.4;
            $bobot += 0.4;
        }

        return $bobot > 0 ? round($total / $bobot, 2) : null;
    }

    private function getGrade($rata2)
    {
        if ($rata2 >= 90) return 'A';
        if ($rata2 >= 80) return 'B';
        if ($rata2 >= 75) return 'C';
        if ($rata2 >= 60) return 'D';
        return 'E';
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'tahun_pelajaran_id' => 'required|exists:tahun_pelajaran,id',
            'nilai_tugas' => 'nullable|integer|min:0|max:100',
            'nilai_uts' => 'nullable|integer|min:0|max:100',
            'nilai_ujian' => 'nullable|integer|min:0|max:100',
        ]);

        if (
            is_null($request->nilai_tugas) &&
            is_null($request->nilai_uts) &&
            is_null($request->nilai_ujian)
        ) {
            return response()->json(['message' => 'Minimal satu nilai harus diisi.'], 422);
        }

        $rata2 = $this->hitungRataRata($request->nilai_tugas, $request->nilai_uts, $request->nilai_ujian);
        $grade = $rata2 !== null ? $this->getGrade($rata2) : null;

        $nilai = NilaiSiswa::updateOrCreate(
            [
                'siswa_id' => $request->siswa_id,
                'mata_pelajaran_id' => $request->mata_pelajaran_id,
                'tahun_pelajaran_id' => $request->tahun_pelajaran_id,
            ],
            [
                'nilai_tugas' => $request->nilai_tugas,
                'nilai_uts' => $request->nilai_uts,
                'nilai_ujian' => $request->nilai_ujian,
                'rata_rata' => $rata2,
                'grade' => $grade,
            ]
        );

        $message = $nilai->wasRecentlyCreated ? 'Nilai berhasil disimpan.' : 'Nilai berhasil diperbarui.';
        return response()->json(['message' => $message]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nilai_tugas' => 'nullable|integer|min:0|max:100',
            'nilai_uts' => 'nullable|integer|min:0|max:100',
            'nilai_ujian' => 'nullable|integer|min:0|max:100',
        ]);

        if (
            is_null($request->nilai_tugas) &&
            is_null($request->nilai_uts) &&
            is_null($request->nilai_ujian)
        ) {
            return response()->json(['message' => 'Minimal satu nilai harus diisi.'], 422);
        }

        $nilai = NilaiSiswa::find($id);
        if (!$nilai) {
            return response()->json(['message' => 'Data nilai tidak ditemukan.'], 404);
        }

        $rata2 = $this->hitungRataRata($request->nilai_tugas, $request->nilai_uts, $request->nilai_ujian);
        $grade = $rata2 !== null ? $this->getGrade($rata2) : null;

        $nilai->update([
            'nilai_tugas' => $request->nilai_tugas,
            'nilai_uts' => $request->nilai_uts,
            'nilai_ujian' => $request->nilai_ujian,
            'rata_rata' => $rata2,
            'grade' => $grade,
        ]);

        return response()->json(['message' => 'Nilai berhasil diperbarui.']);
    }

    public function storeBatch(Request $request)
    {
        $request->validate([
            'tahun_pelajaran_id' => 'required|exists:tahun_pelajaran,id',
            'data' => 'required|array',
            'data.*.siswa_id' => 'required|exists:siswas,id',
            'data.*.mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'data.*.nilai_tugas' => 'nullable|integer|min:0|max:100',
            'data.*.nilai_uts' => 'nullable|integer|min:0|max:100',
            'data.*.nilai_ujian' => 'nullable|integer|min:0|max:100',
        ]);

        foreach ($request->data as $item) {
            if (
                is_null($item['nilai_tugas']) &&
                is_null($item['nilai_uts']) &&
                is_null($item['nilai_ujian'])
            ) {
                continue; // skip jika semua kosong
            }

            $rata2 = $this->hitungRataRata($item['nilai_tugas'], $item['nilai_uts'], $item['nilai_ujian']);
            $grade = $rata2 !== null ? $this->getGrade($rata2) : null;

            NilaiSiswa::updateOrCreate(
                [
                    'siswa_id' => $item['siswa_id'],
                    'mata_pelajaran_id' => $item['mata_pelajaran_id'],
                    'tahun_pelajaran_id' => $request->tahun_pelajaran_id,
                ],
                [
                    'nilai_tugas' => $item['nilai_tugas'],
                    'nilai_uts' => $item['nilai_uts'],
                    'nilai_ujian' => $item['nilai_ujian'],
                    'rata_rata' => $rata2,
                    'grade' => $grade,
                ]
            );
        }

        return response()->json(['message' => 'Batch nilai berhasil disimpan.']);
    }

    public function index()
    {
        $data = NilaiSiswa::with(['siswa', 'mataPelajaran', 'tahunPelajaran'])
            ->orderBy('siswa_id')
            ->get();

        return response()->json($data);
    }

    public function showBySiswa($siswa_id)
    {
        $data = NilaiSiswa::with(['mataPelajaran', 'tahunPelajaran'])
            ->where('siswa_id', $siswa_id)
            ->get();

        return response()->json($data);
    }

    public function getNilaiByKelasMapel($kelasId, $mapelId, $tahunId)
    {
        if (!TahunPelajaran::find($tahunId)) {
            return response()->json(['message' => 'Tahun pelajaran tidak ditemukan'], 404);
        }

        $siswa = Siswa::where('kelas_id', $kelasId)->get();

        $result = $siswa->map(function ($s) use ($mapelId, $tahunId) {
            $nilai = $s->nilaiSiswa()
                ->where('mata_pelajaran_id', $mapelId)
                ->where('tahun_pelajaran_id', $tahunId)
                ->first();

            return [
                'id' => $nilai?->id,
                'siswa_id' => $s->id,
                'nama_lengkap_siswa' => $s->nama_lengkap_siswa,
                'mata_pelajaran_id' => $mapelId,
                'tahun_pelajaran_id' => $tahunId,
                'nilai_tugas' => $nilai->nilai_tugas ?? null,
                'nilai_uts' => $nilai->nilai_uts ?? null,
                'nilai_ujian' => $nilai->nilai_ujian ?? null,
                'rata_rata' => $nilai->rata_rata ?? null,
                'grade' => $nilai->grade ?? null,
            ];
        });

        return response()->json($result);
    }

    public function getNilaiBySiswaDanTahun($siswaId, $tahunId)
    {
        $nilai = NilaiSiswa::with('mataPelajaran')
            ->where('siswa_id', $siswaId)
            ->where('tahun_pelajaran_id', $tahunId)
            ->get()
            ->map(function ($item) {
                return [
                    'nama_mapel' => $item->mataPelajaran->nama_mapel ?? '-',
                    'nilai_tugas' => $item->nilai_tugas ?? 0,
                    'nilai_uts' => $item->nilai_uts ?? 0,
                    'nilai_ujian' => $item->nilai_ujian ?? 0,
                    'rata_rata' => $item->rata_rata ?? 0,
                    'grade' => $item->grade ?? '-',
                ];
            });

        return response()->json($nilai);
    }

    public function getNilaiSiswaLogin($tahunId)
    {
        $user = Auth::user();
        if (!$user || !$user->siswa) {
            return response()->json(['message' => 'Siswa tidak ditemukan untuk user ini'], 404);
        }

        $siswaId = $user->siswa->id;

        $nilai = NilaiSiswa::with('mataPelajaran')
            ->where('siswa_id', $siswaId)
            ->where('tahun_pelajaran_id', $tahunId)
            ->get()
            ->map(function ($item) {
                return [
                    'nama_mapel' => $item->mataPelajaran->nama_mapel ?? '-',
                    'nilai_tugas' => $item->nilai_tugas ?? 0,
                    'nilai_uts' => $item->nilai_uts ?? 0,
                    'nilai_ujian' => $item->nilai_ujian ?? 0,
                    'rata_rata' => $item->rata_rata ?? 0,
                    'grade' => $item->grade ?? '-',
                ];
            });

        return response()->json($nilai);
    }
}
