<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return response()->json(['message' => 'Profil mahasiswa tidak ditemukan'], 404);
        }

        $portofolio = $mahasiswa->portofolio;
        
        if (!$portofolio) {
            // Create portfolio if doesn't exist
            $portofolio = Portofolio::create([
                'mahasiswa_id' => $mahasiswa->id,
                'public_link' => Str::random(32),
                'is_public' => false,
            ]);
        }

        return response()->json([
            'portofolio' => $portofolio->load('mahasiswa.user'),
            'mahasiswa' => $mahasiswa->load(['projects', 'skills', 'certificates', 'experiences'])
        ]);
    }

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
            'is_public' => 'boolean',
            'custom_css' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $portofolio = $mahasiswa->portofolio;
        
        if (!$portofolio) {
            $portofolio = Portofolio::create([
                'mahasiswa_id' => $mahasiswa->id,
                'public_link' => Str::random(32),
            ]);
        }

        $portofolio->update($request->only(['is_public', 'custom_css']));

        return response()->json([
            'message' => 'Portofolio berhasil diperbarui',
            'portofolio' => $portofolio
        ]);
    }

    public function getPublicPortfolio($publicLink)
    {
        $portofolio = Portofolio::where('public_link', $publicLink)
            ->where('is_public', true)
            ->with(['mahasiswa.user', 'mahasiswa.projects', 'mahasiswa.skills', 'mahasiswa.certificates', 'mahasiswa.experiences'])
            ->first();

        if (!$portofolio) {
            return response()->json(['message' => 'Portofolio tidak ditemukan atau tidak publik'], 404);
        }

        return response()->json([
            'portofolio' => $portofolio
        ]);
    }

    public function generatePublicLink(Request $request)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return response()->json(['message' => 'Profil mahasiswa tidak ditemukan'], 404);
        }

        $portofolio = $mahasiswa->portofolio;
        
        if (!$portofolio) {
            $portofolio = Portofolio::create([
                'mahasiswa_id' => $mahasiswa->id,
                'public_link' => Str::random(32),
            ]);
        } else {
            $portofolio->update([
                'public_link' => Str::random(32),
            ]);
        }

        return response()->json([
            'message' => 'Link publik berhasil dibuat',
            'public_link' => $portofolio->public_link,
            'public_url' => url("/portfolio/{$portofolio->public_link}")
        ]);
    }

    public function getPublicPortfolios()
    {
        $portfolios = Portofolio::where('is_public', true)
            ->with(['mahasiswa.user', 'mahasiswa.skills'])
            ->get();

        return response()->json(['portfolios' => $portfolios]);
    }
}

