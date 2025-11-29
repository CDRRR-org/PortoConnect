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

        // Update mahasiswa (no_telp, alamat, deskripsi_diri)
        $mahasiswaData = [];
        if ($request->has('no_telp')) {
            $mahasiswaData['no_telp'] = $request->no_telp;
        }
        if ($request->has('alamat')) {
            $mahasiswaData['alamat'] = $request->alamat;
        }
        if ($request->has('deskripsi_diri')) {
            $mahasiswaData['deskripsi_diri'] = $request->deskripsi_diri;
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
     * Get profil mahasiswa (view only)
     * Tidak termasuk alamat di response
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

        // Load data tanpa alamat
        $mahasiswaData = $mahasiswa->load(['user']);
        
        // Remove alamat from response
        $mahasiswaArray = $mahasiswaData->toArray();
        unset($mahasiswaArray['alamat']);

        return response()->json([
            'mahasiswa' => $mahasiswaArray,
            'user' => $user->only(['id', 'name', 'email'])
        ]);
    }
}

