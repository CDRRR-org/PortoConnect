<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Perusahaan;
use App\Models\Admin;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $adminEmails = ['admin@portoconnect.com', 'bernansren@gmail.com'];

            // Optimize: Check by email first (faster), then google_id
            $user = User::where('email', $googleUser->email)
                ->orWhere('google_id', $googleUser->id)
                ->first();

            if (!$user) {
                $userRole = in_array($googleUser->email, $adminEmails) ? 'admin' : null;

                // Use database transaction for faster creation
                \DB::beginTransaction();
                try {
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => bcrypt(Str::random(16)),
                        'email_verified_at' => now(),
                        'role' => $userRole,
                    ]);

                    // Create related profile based on role
                    if ($userRole === 'mahasiswa') {
                        Mahasiswa::create(['user_id' => $user->id]);
                    } elseif ($userRole === 'perusahaan') {
                        Perusahaan::create([
                            'user_id' => $user->id,
                            'nama_perusahaan' => $googleUser->name,
                        ]);
                    } elseif ($userRole === 'admin') {
                        Admin::create([
                            'user_id' => $user->id,
                            'nama_lengkap' => $googleUser->name,
                        ]);
                    }

                    \DB::commit();
                } catch (\Exception $e) {
                    \DB::rollBack();
                    throw $e;
                }
            } else {
                // Update google_id if user exists but doesn't have it
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
                
                // Check if email is admin email and update role if needed
                if (in_array($googleUser->email, $adminEmails) && $user->role !== 'admin') {
                    \DB::beginTransaction();
                    try {
                        $user->update(['role' => 'admin']);
                        
                        // Create admin profile if doesn't exist
                        if (!$user->admin) {
                            Admin::create([
                                'user_id' => $user->id,
                                'nama_lengkap' => $user->name,
                                'jabatan' => 'Admin',
                            ]);
                        }
                        
                        \DB::commit();
                    } catch (\Exception $e) {
                        \DB::rollBack();
                        throw $e;
                    }
                }
            }
            
            // Create Sanctum token for API access (no need for Auth::login)
            $token = $user->createToken('auth_token')->plainTextToken;

            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
            return redirect($frontendUrl . '/auth/callback?token=' . urlencode($token));

        } catch (\Exception $e) {
            report($e);
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
            return redirect($frontendUrl . '/login?error=GoogleLoginFailed');
        }
    }
}