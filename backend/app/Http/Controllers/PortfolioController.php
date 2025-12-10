<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    /**
     * Get all portfolios for authenticated mahasiswa
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            if ($user->role !== 'mahasiswa') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            // Get portfolios using model method
            $portofolios = Portofolio::getByUserId($user->id);

            return response()->json([
                'portofolios' => $portofolios,
                'total' => $portofolios->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in index portfolios: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal memuat portofolio',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
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

        // Optimasi: Query langsung dengan eager loading untuk menghindari N+1 query
        $portofolio = Portofolio::where('id', $id)
            ->whereHas('mahasiswa', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with([
                'mahasiswa.user:id,name,email',
                'projects',
                'skills',
                'certificates',
                'experiences'
            ])
            ->first();
        
        if (!$portofolio) {
            return response()->json(['message' => 'Portofolio tidak ditemukan'], 404);
        }

        return response()->json([
            'portofolio' => $portofolio,
            'mahasiswa' => $portofolio->mahasiswa
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

        $oldBidang = $portofolio->bidang;
        $portofolio->update($request->only(['nama', 'bidang', 'education', 'language', 'deskripsi', 'is_public', 'custom_css']));

        // Clear cache when portfolio is updated
        Cache::forget('public_portfolios_all');
        if ($oldBidang) {
            Cache::forget('public_portfolios_' . $oldBidang);
        }
        if ($portofolio->bidang && $portofolio->bidang !== $oldBidang) {
            Cache::forget('public_portfolios_' . $portofolio->bidang);
        }

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

        $bidang = $portofolio->bidang;
        $portofolio->delete();

        // Clear cache when portfolio is deleted
        Cache::forget('public_portfolios_all');
        if ($bidang) {
            Cache::forget('public_portfolios_' . $bidang);
        }

        return response()->json([
            'message' => 'Portofolio berhasil dihapus'
        ]);
    }

    public function getPublicPortfolio(Request $request, $publicLink)
    {
        $portofolio = Portofolio::findByPublicLink($publicLink);

        if (!$portofolio) {
            return response()->json(['message' => 'Portofolio tidak ditemukan atau tidak publik'], 404);
        }

        // Check if current user is the owner
        $isOwner = false;
        
        // Try to get user from Authorization header if provided
        $authHeader = $request->header('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = str_replace('Bearer ', '', $authHeader);
            try {
                $personalAccessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
                if ($personalAccessToken) {
                    $user = $personalAccessToken->tokenable;
                    if ($user && $user->role === 'mahasiswa' && $user->mahasiswa && $user->mahasiswa->id === $portofolio->mahasiswa_id) {
                        $isOwner = true;
                    }
                }
            } catch (\Exception $e) {
                // If token check fails, continue as guest
            }
        }

        return response()->json([
            'portofolio' => $portofolio,
            'is_owner' => $isOwner
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

        // Clear cache when portfolio becomes public
        Cache::forget('public_portfolios_all');
        if ($portofolio->bidang) {
            Cache::forget('public_portfolios_' . $portofolio->bidang);
        }

        return response()->json([
            'message' => 'Link publik berhasil dibuat',
            'public_link' => $portofolio->public_link,
            'public_url' => url("/portfolio/{$portofolio->public_link}")
        ]);
    }

    public function getPublicPortfolios(Request $request)
    {
        try {
            $bidang = $request->query('bidang');
            $cacheKey = $bidang ? 'public_portfolios_' . Str::slug($bidang) : 'public_portfolios_all';
            
            // Try to use cache, but fallback to direct query if cache fails
            try {
            $portfolios = Cache::remember($cacheKey, 300, function () use ($bidang) {
                    return Portofolio::getPublicPortfolios($bidang);
                });
            } catch (\Exception $cacheException) {
                // If cache fails (e.g., database connection issue), query directly
                Log::warning('Cache failed, using direct query: ' . $cacheException->getMessage());
                $portfolios = Portofolio::getPublicPortfolios($bidang);
            }

            return response()->json(['portfolios' => $portfolios]);
        } catch (\Exception $e) {
            Log::error('Error in getPublicPortfolios: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal memuat portofolio',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get all public portfolios by mahasiswa ID
     * Public endpoint untuk melihat semua CV/portofolio yang sudah dibuat oleh seorang mahasiswa
     */
    public function getPublicPortfoliosByMahasiswa($mahasiswaId)
    {
        $portofolios = Portofolio::getPublicPortfoliosByMahasiswa($mahasiswaId);

        return response()->json([
            'portfolios' => $portofolios,
            'total' => $portofolios->count()
        ]);
    }
}

