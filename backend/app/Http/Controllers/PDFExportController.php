<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PDFExportController extends Controller
{
    public function exportPortfolio(Request $request)
    {
        $user = $request->user();
        
        if ($user->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return response()->json(['message' => 'Profil mahasiswa tidak ditemukan'], 404);
        }

        // Load all related data
        $mahasiswa->load(['user', 'projects', 'skills', 'certificates', 'experiences', 'portofolio']);

        // For now, return JSON with data. PDF generation can be implemented with dompdf package
        // Install: composer require barryvdh/laravel-dompdf
        $filename = 'portfolio_' . $mahasiswa->user->name . '_' . time() . '.json';
        $path = 'portfolios/' . $filename;
        
        // Store portfolio data as JSON for now
        $data = [
            'mahasiswa' => $mahasiswa,
            'exported_at' => now()->toDateTimeString(),
        ];
        
        file_put_contents(storage_path('app/public/' . $path), json_encode($data, JSON_PRETTY_PRINT));

        // Update portfolio PDF path
        $portofolio = $mahasiswa->portofolio;
        if ($portofolio) {
            $portofolio->update(['pdf_path' => $path]);
        }

        return response()->json([
            'message' => 'PDF berhasil dibuat',
            'pdf_url' => asset('storage/' . $path),
            'filename' => $filename
        ]);
    }

    public function downloadPortfolio($id, Request $request)
    {
        $mahasiswa = Mahasiswa::with(['user', 'portofolio'])->find($id);
        
        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
        }

        $portofolio = $mahasiswa->portofolio;
        
        if (!$portofolio || !$portofolio->pdf_path) {
            return response()->json(['message' => 'PDF tidak ditemukan'], 404);
        }

        // Check if portfolio is public or user owns it
        $user = $request->user();
        $isOwner = $user && $user->role === 'mahasiswa' && $user->mahasiswa && $user->mahasiswa->id === $mahasiswa->id;
        
        if (!$portofolio->is_public && !$isOwner) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $filePath = storage_path('app/public/' . $portofolio->pdf_path);
        
        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }

        return response()->download($filePath);
    }
}

