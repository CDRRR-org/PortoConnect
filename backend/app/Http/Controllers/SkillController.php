<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkillController extends Controller
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

        $skills = $mahasiswa->skills()->orderBy('urutan')->get();

        return response()->json(['skills' => $skills]);
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
            'nama' => 'required|string|max:255',
            'level' => 'required|in:beginner,intermediate,advanced,expert',
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

        $skill = $mahasiswa->skills()->create($request->only([
            'portofolio_id',
            'nama',
            'level',
            'urutan',
        ]));

        return response()->json([
            'message' => 'Skill berhasil ditambahkan',
            'skill' => $skill
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        $skill = Skill::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$skill) {
            return response()->json(['message' => 'Skill tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'portofolio_id' => 'nullable|exists:portofolios,id',
            'nama' => 'sometimes|required|string|max:255',
            'level' => 'sometimes|required|in:beginner,intermediate,advanced,expert',
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

        $skill->update($request->only([
            'portofolio_id',
            'nama',
            'level',
            'urutan',
        ]));

        return response()->json([
            'message' => 'Skill berhasil diperbarui',
            'skill' => $skill
        ]);
    }

    public function destroy($id, Request $request)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        $skill = Skill::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$skill) {
            return response()->json(['message' => 'Skill tidak ditemukan'], 404);
        }

        $skill->delete();

        return response()->json(['message' => 'Skill berhasil dihapus']);
    }
}

