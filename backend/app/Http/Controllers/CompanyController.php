<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Portofolio;
use App\Models\SearchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function searchStudents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keyword' => 'nullable|string|max:255',
            'skill' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'fakultas' => 'nullable|string|max:255',
            'universitas' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $perusahaan = $request->user()->perusahaan;
        
        if (!$perusahaan) {
            return response()->json(['message' => 'Profil perusahaan tidak ditemukan'], 404);
        }

        $query = Mahasiswa::with([
                'user:id,name,email',
                'portofolios' => function($q) {
                    $q->where('is_public', true)
                      ->select('id', 'mahasiswa_id', 'nama', 'bidang', 'deskripsi', 'public_link', 'is_public');
                }
            ])
            ->select('id', 'user_id', 'nim', 'jurusan', 'fakultas', 'universitas', 'deskripsi_diri')
            ->whereHas('portofolios', function($q) {
                $q->where('is_public', true);
            });
            // Removed is_verified filter to allow searching all students with public portfolios

        // Search by keyword (name, NIM, description)
        if ($request->has('keyword') && $request->keyword) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nim', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi_diri', 'like', "%{$keyword}%")
                  ->orWhereHas('user', function($userQuery) use ($keyword) {
                      $userQuery->where('name', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('portofolios', function($portfolioQuery) use ($keyword) {
                      $portfolioQuery->where('is_public', true)
                          ->where(function($pq) use ($keyword) {
                              $pq->where('nama', 'like', "%{$keyword}%")
                                 ->orWhere('deskripsi', 'like', "%{$keyword}%");
                          });
                  });
            });
        }

        // Filter by skill - skills are now in portfolios, not directly in mahasiswa
        if ($request->has('skill') && $request->skill) {
            $query->whereHas('portofolios', function($q) use ($request) {
                $q->where('is_public', true)
                  ->whereHas('skills', function($skillQuery) use ($request) {
                      $skillQuery->where('nama', 'like', "%{$request->skill}%");
                  });
            });
        }

        // Filter by jurusan
        if ($request->has('jurusan') && $request->jurusan) {
            $query->where('jurusan', 'like', "%{$request->jurusan}%");
        }

        // Filter by fakultas
        if ($request->has('fakultas') && $request->fakultas) {
            $query->where('fakultas', 'like', "%{$request->fakultas}%");
        }

        // Filter by universitas
        if ($request->has('universitas') && $request->universitas) {
            $query->where('universitas', 'like', "%{$request->universitas}%");
        }

        $results = $query->paginate(12);

        // Save search history
        SearchHistory::create([
            'perusahaan_id' => $perusahaan->id,
            'keyword' => $request->keyword,
            'filters' => $request->only(['skill', 'jurusan', 'fakultas', 'universitas']),
            'results_count' => $results->total(),
        ]);

        return response()->json($results);
    }

    public function getStudentPortfolio($id, Request $request)
    {
        $mahasiswa = Mahasiswa::with(['user', 'portofolios' => function($q) {
            $q->where('is_public', true);
        }, 'projects', 'skills', 'certificates', 'experiences'])
            ->where('id', $id)
            ->whereHas('portofolios', function($q) {
                $q->where('is_public', true);
            })
            ->first();

        if (!$mahasiswa) {
            return response()->json(['message' => 'Portofolio tidak ditemukan atau tidak publik'], 404);
        }

        return response()->json(['mahasiswa' => $mahasiswa]);
    }

    public function getSearchHistory(Request $request)
    {
        $perusahaan = $request->user()->perusahaan;
        
        if (!$perusahaan) {
            return response()->json(['message' => 'Profil perusahaan tidak ditemukan'], 404);
        }

        $histories = $perusahaan->searchHistories()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($histories);
    }
}

