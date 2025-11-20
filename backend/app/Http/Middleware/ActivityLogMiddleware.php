<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class ActivityLogMiddleware
{
    /**
     * Handle an incoming request.
     * Log aktivitas user untuk tracking
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya log jika user sudah authenticated
        if ($request->user()) {
            try {
                $user = $request->user();
                $method = $request->method();
                $path = $request->path();
                $action = $this->determineAction($method, $path);

                // Skip logging untuk beberapa endpoint yang terlalu sering dipanggil
                $skipPaths = ['/api/me', '/api/mahasiswa/portfolio', '/api/company/search-history'];
                if (in_array('/' . $path, $skipPaths) && $method === 'GET') {
                    return $response;
                }

                // Hanya log untuk method yang penting (POST, PUT, DELETE)
                if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                    $description = $this->generateDescription($user, $method, $path, $request);

                    // Jika admin, log ke ActivityLog
                    if ($user->role === 'admin' && $user->admin) {
                        ActivityLog::create([
                            'admin_id' => $user->admin->id,
                            'user_id' => $user->id,
                            'action' => $action,
                            'model_type' => $this->getModelType($path),
                            'model_id' => $this->getModelId($path, $request),
                            'description' => $description,
                            'ip_address' => $request->ip(),
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Jangan gagalkan request jika logging error
                Log::error('ActivityLogMiddleware Error: ' . $e->getMessage());
            }
        }

        return $response;
    }

    /**
     * Tentukan action berdasarkan method dan path
     */
    private function determineAction(string $method, string $path): string
    {
        if (str_contains($path, 'verify')) {
            return 'verify';
        }

        return match($method) {
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'access',
        };
    }

    /**
     * Generate description untuk activity log
     */
    private function generateDescription($user, string $method, string $path, Request $request): string
    {
        $userName = $user->name;
        $action = match($method) {
            'POST' => 'membuat',
            'PUT', 'PATCH' => 'memperbarui',
            'DELETE' => 'menghapus',
            default => 'mengakses',
        };

        $resource = $this->getResourceName($path);

        return "{$userName} {$action} {$resource}";
    }

    /**
     * Get resource name dari path
     */
    private function getResourceName(string $path): string
    {
        $segments = explode('/', $path);
        $resource = end($segments);

        // Mapping resource names
        $resourceMap = [
            'users' => 'user',
            'projects' => 'project',
            'skills' => 'skill',
            'certificates' => 'certificate',
            'experiences' => 'experience',
            'portfolio' => 'portfolio',
            'profile' => 'profile',
            'search' => 'pencarian',
        ];

        return $resourceMap[$resource] ?? $resource;
    }

    /**
     * Get model type dari path
     */
    private function getModelType(string $path): string
    {
        $segments = explode('/', $path);
        
        foreach ($segments as $segment) {
            if (in_array($segment, ['users', 'projects', 'skills', 'certificates', 'experiences', 'portfolio'])) {
                return ucfirst(rtrim($segment, 's'));
            }
        }

        return 'Unknown';
    }

    /**
     * Get model ID dari path atau request
     */
    private function getModelId(string $path, Request $request): ?int
    {
        $segments = explode('/', $path);
        
        // Cari ID di path
        foreach ($segments as $segment) {
            if (is_numeric($segment)) {
                return (int) $segment;
            }
        }

        // Cari ID di request
        if ($request->has('id')) {
            return (int) $request->input('id');
        }

        return null;
    }
}

