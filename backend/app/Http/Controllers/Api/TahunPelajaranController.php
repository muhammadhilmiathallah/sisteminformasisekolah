<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\TahunPelajaran;
use App\Http\Controllers\Controller;

class TahunPelajaranController extends Controller
{
    /**
     * Menampilkan semua data Tahun Pelajaran secara ascending.
     */
    public function index()
    {
        // Mengurutkan berdasarkan tahun_pelajaran secara ascending
        return response()->json(
            TahunPelajaran::orderBy('tahun_pelajaran', 'asc')->get()
        );
    }

    /**
     * Menyimpan data Tahun Pelajaran baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_pelajaran' => 'required|string',
        ]);

        $tahunpelajaran = TahunPelajaran::create($validated);

        return response()->json($tahunpelajaran, 201);
    }

    /**
     * Menampilkan detail Tahun Pelajaran berdasarkan ID.
     */
    public function show($id)
    {
        $tahunpelajaran = TahunPelajaran::findOrFail($id);
        return response()->json($tahunpelajaran);
    }

    /**
     * Memperbarui data Tahun Pelajaran berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        $tahunpelajaran = TahunPelajaran::findOrFail($id);

        $validated = $request->validate([
            'tahun_pelajaran' => 'required|string',
        ]);

        $tahunpelajaran->update($validated);

        return response()->json($tahunpelajaran);
    }

    /**
     * Menghapus data Tahun Pelajaran berdasarkan ID.
     */
    public function destroy($id)
    {
        $tahunpelajaran = TahunPelajaran::findOrFail($id);
        $tahunpelajaran->delete();

        return response()->json(null, 204);
    }
}
