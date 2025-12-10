<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Perusahaan;
use App\Models\Admin;
use App\Models\ActivityLog;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        // Middleware sudah di-handle di routes, tidak perlu lagi di sini
        // Tapi tetap bisa digunakan jika diperlukan validasi tambahan
    }

    public function getUsers(Request $request)
    {
        $role = $request->query('role');
        $search = $request->query('search');
        
        $users = User::getFilteredUsers($role, $search)->paginate(15);
        
        return response()->json($users);
    }

    public function getUser($id)
    {
        $user = User::withRoleProfile($id);
        
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }
        
        return response()->json(['user' => $user]);
    }

    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:mahasiswa,perusahaan,admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);

        // Create related profile
        if ($request->role === 'mahasiswa') {
            Mahasiswa::create(['user_id' => $user->id]);
        } elseif ($request->role === 'perusahaan') {
            Perusahaan::create([
                'user_id' => $user->id,
                'nama_perusahaan' => $request->name,
            ]);
        } elseif ($request->role === 'admin') {
            Admin::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->name,
            ]);
        }

        // Log activity
        ActivityLog::create([
            'admin_id' => $request->user()->admin->id,
            'user_id' => $user->id,
            'action' => 'create',
            'model_type' => 'User',
            'model_id' => $user->id,
            'description' => "Admin membuat user baru: {$user->name} ({$user->email})",
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'User berhasil dibuat',
            'user' => $user->load(['mahasiswa', 'perusahaan', 'admin'])
        ], 201);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role' => 'sometimes|required|in:mahasiswa,perusahaan,admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $oldData = $user->toArray();
        
        $updateData = $request->only(['name', 'email', 'role']);
        if ($request->has('password')) {
            $updateData['password'] = Hash::make($request->password);
        }
        
        $user->update($updateData);

        // Log activity
        ActivityLog::create([
            'admin_id' => $request->user()->admin->id,
            'user_id' => $user->id,
            'action' => 'update',
            'model_type' => 'User',
            'model_id' => $user->id,
            'description' => "Admin memperbarui user: {$user->name}",
            'changes' => ['old' => $oldData, 'new' => $user->toArray()],
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'User berhasil diperbarui',
            'user' => $user->load(['mahasiswa', 'perusahaan', 'admin'])
        ]);
    }

    public function deleteUser(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $userName = $user->name;
        $userEmail = $user->email;

        // Log activity before deletion
        ActivityLog::create([
            'admin_id' => $request->user()->admin->id,
            'user_id' => $user->id,
            'action' => 'delete',
            'model_type' => 'User',
            'model_id' => $user->id,
            'description' => "Admin menghapus user: {$userName} ({$userEmail})",
            'ip_address' => $request->ip(),
        ]);

        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus']);
    }

    public function verifyUser(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        if ($user->role === 'mahasiswa') {
            $profile = $user->mahasiswa;
            if ($profile) {
                $profile->update(['is_verified' => true]);
            }
        } elseif ($user->role === 'perusahaan') {
            $profile = $user->perusahaan;
            if ($profile) {
                $profile->update(['is_verified' => true]);
            }
        }

        if (!$user->email_verified_at) {
            $user->update(['email_verified_at' => now()]);
        }

        // Log activity
        ActivityLog::create([
            'admin_id' => $request->user()->admin->id,
            'user_id' => $user->id,
            'action' => 'verify',
            'model_type' => 'User',
            'model_id' => $user->id,
            'description' => "Admin memverifikasi user: {$user->name}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'User berhasil diverifikasi',
            'user' => $user->load(['mahasiswa', 'perusahaan', 'admin'])
        ]);
    }

    public function getActivityLogs(Request $request)
    {
        $logs = ActivityLog::with(['admin.user', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return response()->json($logs);
    }

    public function getDashboardStats(Request $request)
    {
        // Cache stats for 5 minutes
        $stats = \Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_users' => User::count(),
                'total_mahasiswa' => User::where('role', 'mahasiswa')->count(),
                'total_perusahaan' => User::where('role', 'perusahaan')->count(),
                'total_admin' => User::where('role', 'admin')->count(),
                'verified_mahasiswa' => Mahasiswa::where('is_verified', true)->count(),
                'verified_perusahaan' => Perusahaan::where('is_verified', true)->count(),
                'recent_activities' => ActivityLog::with(['admin:id,user_id', 'admin.user:id,name', 'user:id,name'])
                    ->select('id', 'admin_id', 'user_id', 'action', 'model_type', 'description', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get(),
            ];
        });
        
        return response()->json($stats);
    }

    /**
     * Get all portfolios for admin
     */
    public function getPortfolios(Request $request)
    {
        $bidang = $request->has('bidang') && $request->bidang ? $request->bidang : null;
        $portfolios = Portofolio::with([
            'mahasiswa:id,user_id,universitas,jurusan',
            'mahasiswa.user:id,name',
            'skills:id,portofolio_id,nama,level'
        ])
            ->select('id', 'mahasiswa_id', 'nama', 'bidang', 'deskripsi', 'public_link', 'is_public', 'created_at')
            ->byBidang($bidang)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['portfolios' => $portfolios]);
    }

    /**
     * Delete portfolio by admin
     */
    public function deletePortfolio(Request $request, $id)
    {
        $portfolio = Portofolio::with('mahasiswa.user')->find($id);
        
        if (!$portfolio) {
            return response()->json(['message' => 'Portofolio tidak ditemukan'], 404);
        }

        $portfolioName = $portfolio->nama;
        $studentName = $portfolio->mahasiswa->user->name ?? 'Unknown';

        // Log activity before deletion
        ActivityLog::create([
            'admin_id' => $request->user()->admin->id,
            'user_id' => $portfolio->mahasiswa->user_id ?? null,
            'action' => 'delete',
            'model_type' => 'Portofolio',
            'model_id' => $portfolio->id,
            'description' => "Admin menghapus portofolio: {$portfolioName} (milik: {$studentName})",
            'ip_address' => $request->ip(),
        ]);

        $bidang = $portfolio->bidang;
        $portfolio->delete();

        // Clear cache when portfolio is deleted
        \Cache::forget('public_portfolios_all');
        if ($bidang) {
            \Cache::forget('public_portfolios_' . $bidang);
        }

        return response()->json(['message' => 'Portofolio berhasil dihapus']);
    }
}

