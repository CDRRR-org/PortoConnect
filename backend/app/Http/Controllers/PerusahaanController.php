<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PerusahaanController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();
        
        if ($user->role !== 'perusahaan') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $perusahaan = $user->perusahaan;
        
        if (!$perusahaan) {
            return response()->json(['message' => 'Profil perusahaan tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'sometimes|required|string|max:255',
            'industri' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
            'website' => 'nullable|url',
            'logo' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $perusahaan->update($request->all());

        // Update user name if provided
        if ($request->has('name')) {
            $user->update(['name' => $request->name]);
        }

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'perusahaan' => $perusahaan->load('user')
        ]);
    }

    public function show(Request $request)
    {
        $user = $request->user();
        
        if ($user->role !== 'perusahaan') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $perusahaan = $user->perusahaan;
        
        if (!$perusahaan) {
            return response()->json(['message' => 'Profil perusahaan tidak ditemukan'], 404);
        }

        return response()->json([
            'perusahaan' => $perusahaan->load('user')
        ]);
    }
}

