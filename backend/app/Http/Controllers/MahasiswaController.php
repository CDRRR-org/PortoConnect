<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    /**
     * Update profil mahasiswa (global)
     * Hanya bisa edit: nama, email, no_telp, alamat, deskripsi_diri (bio)
     */
    public function update(Request $request)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return response()->json(['message' => 'Profil mahasiswa tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'deskripsi_diri' => 'nullable|string', // Bio
            'nim' => 'nullable|string|max:50',
            'jurusan' => 'nullable|string|max:255',
            'fakultas' => 'nullable|string|max:255',
            'universitas' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'linkedin' => 'nullable|string|max:255|url',
            'github' => 'nullable|string|max:255|url',
            'website' => 'nullable|string|max:255|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update user (nama dan email)
        if ($request->has('name')) {
            $user->update(['name' => $request->name]);
        }
        if ($request->has('email')) {
            $user->update(['email' => $request->email]);
        }

        // Update mahasiswa (semua field mahasiswa)
        $mahasiswaData = [];
        $mahasiswaFields = ['no_telp', 'alamat', 'deskripsi_diri', 'nim', 'jurusan', 'fakultas', 'universitas', 'tanggal_lahir', 'linkedin', 'github', 'website'];
        
        foreach ($mahasiswaFields as $field) {
            if ($request->has($field)) {
                $mahasiswaData[$field] = $request->$field;
            }
        }
        
        if (!empty($mahasiswaData)) {
            $mahasiswa->update($mahasiswaData);
        }

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user->fresh(),
            'mahasiswa' => $mahasiswa->fresh()->load('user')
        ]);
    }

    /**
     * Get profil mahasiswa
     * Mengembalikan semua data termasuk alamat untuk keperluan edit
     */
    public function show(Request $request)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return response()->json(['message' => 'Profil mahasiswa tidak ditemukan'], 404);
        }

        // Load semua data termasuk alamat
        $mahasiswaData = $mahasiswa->load(['user']);

        return response()->json([
            'mahasiswa' => $mahasiswaData,
            'user' => $user->only(['id', 'name', 'email'])
        ]);
    }

    /**
     * Get public profile of mahasiswa by ID (view-only)
     * Bisa diakses tanpa authentication untuk melihat profil owner portofolio
     */
    public function getPublicProfile($id)
    {
        $mahasiswa = Mahasiswa::with(['user'])
            ->where('id', $id)
            ->first();

        if (!$mahasiswa) {
            return response()->json(['message' => 'Profil mahasiswa tidak ditemukan'], 404);
        }

        // Return data tanpa alamat untuk keamanan
        $mahasiswaData = $mahasiswa->toArray();
        unset($mahasiswaData['alamat']);

        return response()->json([
            'mahasiswa' => $mahasiswaData,
            'user' => $mahasiswa->user->only(['id', 'name', 'email'])
        ]);
    }
}

