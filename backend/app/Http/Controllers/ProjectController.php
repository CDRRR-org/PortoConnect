<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
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

        $projects = $mahasiswa->projects()->orderBy('urutan')->get();

        return response()->json(['projects' => $projects]);
    }

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
            'portofolio_id' => 'nullable|exists:portofolios,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'link' => ['nullable', function ($attribute, $value, $fail) {
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL) && !preg_match('/^https?:\/\//', $value)) {
                    $fail('Link harus berupa URL yang valid (contoh: http://example.com atau https://example.com)');
                }
            }],
            'gambar' => 'nullable|string',
            'teknologi' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'urutan' => 'nullable|integer',
        ]);

        // Validate portofolio belongs to mahasiswa if provided
        if ($request->has('portofolio_id')) {
            $portofolio = $mahasiswa->portofolios()->find($request->portofolio_id);
            if (!$portofolio) {
                return response()->json([
                    'message' => 'Portofolio tidak ditemukan atau tidak dimiliki oleh mahasiswa ini'
                ], 404);
            }
        }

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $project = $mahasiswa->projects()->create($request->all());

        return response()->json([
            'message' => 'Proyek berhasil ditambahkan',
            'project' => $project
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        $project = Project::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$project) {
            return response()->json(['message' => 'Proyek tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'portofolio_id' => 'nullable|exists:portofolios,id',
            'judul' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
            'link' => ['nullable', function ($attribute, $value, $fail) {
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL) && !preg_match('/^https?:\/\//', $value)) {
                    $fail('Link harus berupa URL yang valid (contoh: http://example.com atau https://example.com)');
                }
            }],
            'gambar' => 'nullable|string',
            'teknologi' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'urutan' => 'nullable|integer',
        ]);

        // Validate portofolio belongs to mahasiswa if provided
        if ($request->has('portofolio_id')) {
            $portofolio = $mahasiswa->portofolios()->find($request->portofolio_id);
            if (!$portofolio) {
                return response()->json([
                    'message' => 'Portofolio tidak ditemukan atau tidak dimiliki oleh mahasiswa ini'
                ], 404);
            }
        }

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $project->update($request->all());

        return response()->json([
            'message' => 'Proyek berhasil diperbarui',
            'project' => $project
        ]);
    }

    public function destroy($id, Request $request)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        $project = Project::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$project) {
            return response()->json(['message' => 'Proyek tidak ditemukan'], 404);
        }

        $project->delete();

        return response()->json(['message' => 'Proyek berhasil dihapus']);
    }
}

