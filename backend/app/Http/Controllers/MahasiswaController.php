<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
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
            'nim' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'fakultas' => 'nullable|string|max:255',
            'universitas' => 'nullable|string|max:255',
            'deskripsi_diri' => 'nullable|string',
            'foto_profil' => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'linkedin' => 'nullable|url',
            'github' => 'nullable|url',
            'website' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $mahasiswa->update($request->all());

        // Update user name if provided
        if ($request->has('name')) {
            $user->update(['name' => $request->name]);
        }

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'mahasiswa' => $mahasiswa->load('user')
        ]);
    }

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

        return response()->json([
            'mahasiswa' => $mahasiswa->load(['user', 'projects', 'skills', 'certificates', 'experiences', 'portofolio'])
        ]);
    }
}

