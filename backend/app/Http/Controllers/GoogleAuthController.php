<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $adminEmail = 'admin@portoconnect.com';

            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                $userRole = ($googleUser->email == $adminEmail) ? 'admin' : null;

                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(),
                    'role' => $userRole,
                ]);
            }

            Auth::login($user);

            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
            return redirect($frontendUrl . '/auth/callback');

        } catch (\Exception $e) {
            report($e);
            $failUrl = env('FRONTEND_URL', 'http://localhost:5173') . '/login?error=GoogleLoginFailed';
            return redirect($failUrl);
        }
    }
}