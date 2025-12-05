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
        // Load semua relationships yang diperlukan
        if ($portfolioId) {
            $portofolio = $mahasiswa->portofolios()
                ->with(['projects', 'skills', 'certificates', 'experiences'])
                ->find($portfolioId);
        } else {
            $portofolio = $mahasiswa->portofolios()
                ->with(['projects', 'skills', 'certificates', 'experiences'])
                ->first();
        }

        if (!$portofolio) {
            return response()->json(['message' => 'Portofolio tidak ditemukan'], 404);
        }

        // Load mahasiswa data (user dan global data sebagai fallback)
        $mahasiswa->load(['user', 'projects', 'skills', 'certificates', 'experiences']);

        // Generate PDF dengan optimasi performa dan error handling
        try {
            $pdf = Pdf::loadView('portfolio-pdf', [
                'mahasiswa' => $mahasiswa,
                'portofolio' => $portofolio
            ])
            ->setPaper('a4', 'portrait')
            ->setOption('enable-local-file-access', true)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false)
            ->setOption('dpi', 150)
            ->setOption('defaultFont', 'DejaVu Sans');

            // Generate filename
            $filename = 'portfolio_' . str_replace(' ', '_', $mahasiswa->user->name) . '_' . time() . '.pdf';
            
            // Return PDF as download directly
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'message' => 'Gagal membuat PDF',
                'error' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat membuat PDF'
            ], 500);
        }
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
        try {
            // Load portfolio dengan semua relationships yang diperlukan
            $portofolio = Portofolio::where('public_link', $publicLink)
                ->where('is_public', true)
                ->with([
                    'mahasiswa.user',
                    'projects',
                    'skills',
                    'certificates',
                    'experiences'
                ])
                ->first();

            if (!$portofolio) {
                return response()->json(['message' => 'Portofolio tidak ditemukan atau tidak publik'], 404);
            }

            $mahasiswa = $portofolio->mahasiswa;

            if (!$mahasiswa) {
                return response()->json(['message' => 'Data mahasiswa tidak ditemukan'], 404);
            }

            if (!$mahasiswa->user) {
                return response()->json(['message' => 'Data user tidak ditemukan'], 404);
            }

            // Load mahasiswa global data sebagai fallback jika portfolio tidak punya data
            // Portfolio-specific data sudah di-load di atas, ini untuk data global mahasiswa
            if (!$portofolio->projects || $portofolio->projects->isEmpty()) {
                $mahasiswa->load('projects');
            }
            if (!$portofolio->skills || $portofolio->skills->isEmpty()) {
                $mahasiswa->load('skills');
            }
            if (!$portofolio->certificates || $portofolio->certificates->isEmpty()) {
                $mahasiswa->load('certificates');
            }
            if (!$portofolio->experiences || $portofolio->experiences->isEmpty()) {
                $mahasiswa->load('experiences');
            }

            // Generate PDF dengan optimasi performa dan error handling
            $pdf = Pdf::loadView('portfolio-pdf', [
                'mahasiswa' => $mahasiswa,
                'portofolio' => $portofolio
            ])
            ->setPaper('a4', 'portrait')
            ->setOption('enable-local-file-access', true)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false)
            ->setOption('dpi', 150)
            ->setOption('defaultFont', 'DejaVu Sans');

            // Generate filename
            $filename = 'portfolio_' . str_replace(' ', '_', $mahasiswa->user->name) . '_' . time() . '.pdf';
            
            // Return PDF as download directly
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'message' => 'Gagal membuat PDF',
                'error' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat membuat PDF'
            ], 500);
        }
    }
}

