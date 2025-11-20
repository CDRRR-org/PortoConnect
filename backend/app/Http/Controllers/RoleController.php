<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function setRole(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Jika user sudah punya role, tidak bisa diubah
        if ($user->role && $user->role !== null) {
            return response()->json([
                'message' => 'Role sudah ditetapkan. Tidak bisa diubah.',
                'user' => $user->load(['mahasiswa', 'perusahaan', 'admin'])
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'role' => 'required|in:mahasiswa,perusahaan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update user role
        $user->update(['role' => $request->role]);

        // Create related profile based on role
        if ($request->role === 'mahasiswa') {
            $mahasiswa = Mahasiswa::firstOrCreate(
                ['user_id' => $user->id],
                []
            );
            // Create portfolio automatically
            if (!$mahasiswa->portofolio) {
                $mahasiswa->portofolio()->create([
                    'public_link' => \Illuminate\Support\Str::random(32),
                    'is_public' => false,
                ]);
            }
        } elseif ($request->role === 'perusahaan') {
            Perusahaan::firstOrCreate(
                ['user_id' => $user->id],
                ['nama_perusahaan' => $user->name]
            );
        }

        return response()->json([
            'message' => 'Role berhasil ditetapkan',
            'user' => $user->load(['mahasiswa', 'perusahaan', 'admin'])
        ]);
    }
}


