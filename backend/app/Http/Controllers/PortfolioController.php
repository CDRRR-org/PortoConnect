<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    /**
     * Get all portfolios for authenticated mahasiswa
     */
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

        $portofolios = $mahasiswa->portofolios()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'portofolios' => $portofolios,
            'total' => $portofolios->count()
        ]);
    }

    /**
     * Get single portfolio by ID
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return response()->json(['message' => 'Profil mahasiswa tidak ditemukan'], 404);
        }

        $portofolio = $mahasiswa->portofolios()->find($id);
        
        if (!$portofolio) {
            return response()->json(['message' => 'Portofolio tidak ditemukan'], 404);
        }

        // Load content specific to this portfolio
        $portofolio->load([
            'mahasiswa.user',
            'projects',
            'skills',
            'certificates',
            'experiences'
        ]);

        return response()->json([
            'portofolio' => $portofolio,
            'mahasiswa' => $mahasiswa->load('user')
        ]);
    }

    /**
     * Create new portfolio
     */
    public function store(Request $request)
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
            'nama' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_public' => 'boolean',
            'custom_css' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $portofolio = Portofolio::create([
            'mahasiswa_id' => $mahasiswa->id,
            'nama' => $request->nama ?? 'Portofolio ' . ($mahasiswa->portofolios()->count() + 1),
            'bidang' => $request->bidang ?? null,
            'education' => $request->education ?? null,
            'language' => $request->language ?? null,
            'deskripsi' => $request->deskripsi ?? null,
            'public_link' => Str::random(32),
            'is_public' => $request->is_public ?? false,
            'custom_css' => $request->custom_css ?? null,
        ]);

        return response()->json([
            'message' => 'Portofolio berhasil dibuat',
            'portofolio' => $portofolio->load('mahasiswa.user')
        ], 201);
    }

    /**
     * Update portfolio
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return response()->json(['message' => 'Profil mahasiswa tidak ditemukan'], 404);
        }

        $portofolio = $mahasiswa->portofolios()->find($id);
        
        if (!$portofolio) {
            return response()->json(['message' => 'Portofolio tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'nullable|string|max:255',
            'bidang' => 'nullable|in:backend,frontend,fullstack,QATester',
            'education' => 'nullable|string',
            'language' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'is_public' => 'boolean',
            'custom_css' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $portofolio->update($request->only(['nama', 'bidang', 'education', 'language', 'deskripsi', 'is_public', 'custom_css']));

        return response()->json([
            'message' => 'Portofolio berhasil diperbarui',
            'portofolio' => $portofolio->load('mahasiswa.user')
        ]);
    }

    /**
     * Delete portfolio
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return response()->json(['message' => 'Profil mahasiswa tidak ditemukan'], 404);
        }

        $portofolio = $mahasiswa->portofolios()->find($id);
        
        if (!$portofolio) {
            return response()->json(['message' => 'Portofolio tidak ditemukan'], 404);
        }

        $portofolio->delete();

        return response()->json([
            'message' => 'Portofolio berhasil dihapus'
        ]);
    }

    public function getPublicPortfolio($publicLink)
    {
        $portofolio = Portofolio::where('public_link', $publicLink)
            ->where('is_public', true)
            ->with(['mahasiswa.user', 'projects', 'skills', 'certificates', 'experiences'])
            ->first();

        if (!$portofolio) {
            return response()->json(['message' => 'Portofolio tidak ditemukan atau tidak publik'], 404);
        }

        return response()->json([
            'portofolio' => $portofolio
        ]);
    }

    /**
     * Generate public link for specific portfolio
     */
    public function generatePublicLink(Request $request, $id)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return response()->json(['message' => 'Profil mahasiswa tidak ditemukan'], 404);
        }

        $portofolio = $mahasiswa->portofolios()->find($id);
        
        if (!$portofolio) {
            return response()->json(['message' => 'Portofolio tidak ditemukan'], 404);
        }

        $portofolio->update([
            'public_link' => Str::random(32),
            'is_public' => true,
        ]);

        return response()->json([
            'message' => 'Link publik berhasil dibuat',
            'public_link' => $portofolio->public_link,
            'public_url' => url("/portfolio/{$portofolio->public_link}")
        ]);
    }

    public function getPublicPortfolios(Request $request)
    {
        $query = Portofolio::where('is_public', true)
            ->whereNotNull('public_link')
            ->with([
                'mahasiswa.user',
                'skills' => function($q) {
                    $q->limit(5); // Limit skills untuk preview
                }
            ]);

        // Filter by bidang if provided
        if ($request->has('bidang') && $request->bidang) {
            $query->where('bidang', $request->bidang);
        }

        $portfolios = $query->orderBy('created_at', 'desc')->get();

        return response()->json(['portfolios' => $portfolios]);
    }
}

