<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // Get portfolio ID from request if provided
        $portfolioId = $request->input('portofolio_id');
        
        // Load portfolio if ID provided, otherwise get first portfolio
        if ($portfolioId) {
            $portofolio = $mahasiswa->portofolios()->with(['projects', 'skills', 'certificates', 'experiences'])->find($portfolioId);
        } else {
            $portofolio = $mahasiswa->portofolios()->with(['projects', 'skills', 'certificates', 'experiences'])->first();
        }

        // Load all related data
        $mahasiswa->load(['user', 'projects', 'skills', 'certificates', 'experiences']);

        // Generate PDF
        $pdf = Pdf::loadView('portfolio-pdf', [
            'mahasiswa' => $mahasiswa,
            'portofolio' => $portofolio
        ]);

        // Generate filename
        $filename = 'portfolio_' . str_replace(' ', '_', $mahasiswa->user->name) . '_' . time() . '.pdf';
        
        // Return PDF as download directly
        return $pdf->download($filename);
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

    /**
     * Export portfolio to PDF from public link (no auth required)
     */
    public function exportPublicPortfolio($publicLink, Request $request)
    {
        $portofolio = Portofolio::findByPublicLink($publicLink);

        if (!$portofolio) {
            return response()->json(['message' => 'Portofolio tidak ditemukan atau tidak publik'], 404);
        }

        $mahasiswa = $portofolio->mahasiswa;

        // Load all related data
        $mahasiswa->load(['user', 'projects', 'skills', 'certificates', 'experiences']);

        // Generate PDF
        $pdf = Pdf::loadView('portfolio-pdf', [
            'mahasiswa' => $mahasiswa,
            'portofolio' => $portofolio
        ]);

        // Generate filename
        $filename = 'portfolio_' . str_replace(' ', '_', $mahasiswa->user->name) . '_' . time() . '.pdf';
        
        // Return PDF as download directly
        return $pdf->download($filename);
    }
}

