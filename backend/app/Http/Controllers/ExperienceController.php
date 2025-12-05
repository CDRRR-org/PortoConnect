<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExperienceController extends Controller
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

        $experiences = $mahasiswa->experiences()->orderBy('urutan')->get();

        return response()->json(['experiences' => $experiences]);
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
            'perusahaan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'masih_berlangsung' => 'boolean',
            'tipe' => 'required|in:kerja,magang,organisasi,volunteer,lainnya',
            'urutan' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate portofolio belongs to mahasiswa if provided
        if ($request->has('portofolio_id')) {
            $portofolio = $mahasiswa->portofolios()->find($request->portofolio_id);
            if (!$portofolio) {
                return response()->json([
                    'message' => 'Portofolio tidak ditemukan atau tidak dimiliki oleh mahasiswa ini'
                ], 404);
            }
        }

        $experience = $mahasiswa->experiences()->create($request->all());

        return response()->json([
            'message' => 'Pengalaman berhasil ditambahkan',
            'experience' => $experience
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        $experience = Experience::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$experience) {
            return response()->json(['message' => 'Pengalaman tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'portofolio_id' => 'nullable|exists:portofolios,id',
            'judul' => 'sometimes|required|string|max:255',
            'perusahaan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'sometimes|required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'masih_berlangsung' => 'boolean',
            'tipe' => 'sometimes|required|in:kerja,magang,organisasi,volunteer,lainnya',
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

        $experience->update($request->all());

        return response()->json([
            'message' => 'Pengalaman berhasil diperbarui',
            'experience' => $experience
        ]);
    }

    public function destroy($id, Request $request)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        $experience = Experience::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$experience) {
            return response()->json(['message' => 'Pengalaman tidak ditemukan'], 404);
        }

        $experience->delete();

        return response()->json(['message' => 'Pengalaman berhasil dihapus']);
    }
}

