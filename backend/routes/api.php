<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\PDFExportController;
use App\Http\Controllers\RoleController;

// Public routes
Route::get('/portfolio/{publicLink}', [PortfolioController::class, 'getPublicPortfolio']);
Route::get('/portfolios/public', [PortfolioController::class, 'getPublicPortfolios']);
Route::post('/portfolio/{publicLink}/export-pdf', [PDFExportController::class, 'exportPublicPortfolio']);
Route::get('/mahasiswa/{id}/profile', [MahasiswaController::class, 'getPublicProfile']);
Route::get('/mahasiswa/{id}/portfolios', [PortfolioController::class, 'getPublicPortfoliosByMahasiswa']);

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['auth:sanctum', 'activity.log'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Role selection
    Route::post('/set-role', [RoleController::class, 'setRole']);

    // Mahasiswa routes - hanya bisa diakses oleh mahasiswa
    Route::prefix('mahasiswa')->middleware('role:mahasiswa')->group(function () {
        Route::get('/profile', [MahasiswaController::class, 'show']);
        Route::put('/profile', [MahasiswaController::class, 'update']);
        
        // Portfolio routes - support multiple portfolios
        Route::get('/portfolios', [PortfolioController::class, 'index']); // List all portfolios
        Route::post('/portfolios', [PortfolioController::class, 'store']); // Create new portfolio
        Route::get('/portfolios/{id}', [PortfolioController::class, 'show']); // Get single portfolio
        Route::put('/portfolios/{id}', [PortfolioController::class, 'update']); // Update portfolio
        Route::delete('/portfolios/{id}', [PortfolioController::class, 'destroy']); // Delete portfolio
        Route::post('/portfolios/{id}/generate-link', [PortfolioController::class, 'generatePublicLink']); // Generate public link
        
        // Legacy routes (for backward compatibility)
        Route::get('/portfolio', [PortfolioController::class, 'index']); // Redirect to portfolios list
        
        Route::apiResource('projects', ProjectController::class);
        Route::apiResource('skills', SkillController::class);
        Route::apiResource('certificates', CertificateController::class);
        Route::apiResource('experiences', ExperienceController::class);
        
        Route::post('/export-pdf', [PDFExportController::class, 'exportPortfolio']);
    });

    // Company routes - hanya bisa diakses oleh perusahaan
    Route::prefix('company')->middleware('role:perusahaan')->group(function () {
        Route::get('/profile', [PerusahaanController::class, 'show']);
        Route::put('/profile', [PerusahaanController::class, 'update']);
        Route::get('/search', [CompanyController::class, 'searchStudents']);
        Route::get('/students/{id}', [CompanyController::class, 'getStudentPortfolio']);
        Route::get('/search-history', [CompanyController::class, 'getSearchHistory']);
    });

    // Admin routes - hanya bisa diakses oleh admin
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/users', [AdminController::class, 'getUsers']);
        Route::get('/users/{id}', [AdminController::class, 'getUser']);
        Route::post('/users', [AdminController::class, 'createUser']);
        Route::put('/users/{id}', [AdminController::class, 'updateUser']);
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
        Route::post('/users/{id}/verify', [AdminController::class, 'verifyUser']);
        Route::get('/activity-logs', [AdminController::class, 'getActivityLogs']);
        Route::get('/dashboard-stats', [AdminController::class, 'getDashboardStats']);
        Route::get('/portfolios', [AdminController::class, 'getPortfolios']);
        Route::delete('/portfolios/{id}', [AdminController::class, 'deletePortfolio']);
    });

    // PDF download (protected)
    Route::get('/portfolio/{id}/download', [PDFExportController::class, 'downloadPortfolio']);
});

