<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CertificateController extends Controller
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

        $certificates = $mahasiswa->certificates()->orderBy('urutan')->get();

        return response()->json(['certificates' => $certificates]);
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
            'penerbit' => 'required|string|max:255',
            'tanggal_terbit' => 'required|date',
            'tanggal_kadaluarsa' => 'nullable|date|after_or_equal:tanggal_terbit',
            'file_path' => 'nullable|string',
            'link' => 'nullable|url',
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

        $certificate = $mahasiswa->certificates()->create($request->all());

        return response()->json([
            'message' => 'Sertifikat berhasil ditambahkan',
            'certificate' => $certificate
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        $certificate = Certificate::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$certificate) {
            return response()->json(['message' => 'Sertifikat tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'portofolio_id' => 'nullable|exists:portofolios,id',
            'nama' => 'sometimes|required|string|max:255',
            'penerbit' => 'sometimes|required|string|max:255',
            'tanggal_terbit' => 'sometimes|required|date',
            'tanggal_kadaluarsa' => 'nullable|date|after_or_equal:tanggal_terbit',
            'file_path' => 'nullable|string',
            'link' => 'nullable|url',
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

        $certificate->update($request->all());

        return response()->json([
            'message' => 'Sertifikat berhasil diperbarui',
            'certificate' => $certificate
        ]);
    }

    public function destroy($id, Request $request)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        $certificate = Certificate::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$certificate) {
            return response()->json(['message' => 'Sertifikat tidak ditemukan'], 404);
        }

        $certificate->delete();

        return response()->json(['message' => 'Sertifikat berhasil dihapus']);
    }
}

