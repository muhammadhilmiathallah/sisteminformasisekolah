<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Gurus;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Ambil semua user secara ASCENDING berdasarkan username
    public function index()
    {
        $users = User::with('role')
            ->orderBy('username', 'asc') // Tambahan ASCENDING
            ->get();

        return response()->json($users);
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'   => 'required|string|unique:users,username',
            'password'   => 'required|string|min:6|confirmed',
            'role_id'    => 'required|exists:roles,id',
            'guru_id'    => 'nullable|exists:gurus,id',
            'siswa_id'   => 'nullable|exists:siswas,id',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role_id'  => $validated['role_id'],
        ]);

        if (!empty($validated['guru_id'])) {
            Gurus::where('id', $validated['guru_id'])->update([
                'user_id' => $user->id,
            ]);
        }

        if (!empty($validated['siswa_id'])) {
            Siswa::where('id', $validated['siswa_id'])->update([
                'user_id' => $user->id,
            ]);
        }

        return response()->json([
            'message' => 'User berhasil dibuat.',
            'user'    => $user
        ], 201);
    }

    // Tampilkan user berdasarkan ID
    public function show($id)
    {
        $user = User::with(['role', 'guru', 'siswa'])->findOrFail($id);
        return response()->json($user);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'sometimes|required|string|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json(['message' => 'User berhasil diupdate.', 'user' => $user]);
    }

    // Ganti password user
    public function updatePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'Password berhasil diubah',
        ], 200);
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus.']);
    }
}
