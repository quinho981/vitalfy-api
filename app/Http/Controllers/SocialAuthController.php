<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        $frontendUrl = config('app.frontend_url');

        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Throwable) {
            return redirect("{$frontendUrl}/auth/social-callback?error=social_auth_failed");
        }

        $user = User::where('google_id', $googleUser->getId())->first()
            ?? User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            if (! $user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            }
        } else {
            $user = User::create([
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
                'password'  => Hash::make(Str::random(32)),
            ]);

            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addHour(),
                ['id' => $user->getKey(), 'hash' => sha1($user->email)]
            );

            UserRegistered::dispatch($user, $verificationUrl);
        }

        $expiresAt = now()->addDays(30);
        $minutes   = (int) now()->diffInMinutes($expiresAt);

        $token = $user->createToken('google_auth', ['*'], $expiresAt)->plainTextToken;

        $isProduction = config('app.env') === 'production';
        $sameSite     = $isProduction ? 'None' : 'Lax';
        $secure       = $isProduction;

        // Token vai em cookie HttpOnly — sem token na URL (eliminando exposição em logs/histórico)
        return redirect("{$frontendUrl}/auth/social-callback?social_auth=success")
            ->withCookie(cookie('api_token',     $token,              $minutes, '/', null, $secure, true,  false, $sameSite))
            ->withCookie(cookie('logged_in',     '1',                 $minutes, '/', null, $secure, false, false, $sameSite))
            ->withCookie(cookie('client_nonce',  Str::random(40),     $minutes, '/', null, $secure, false, false, $sameSite));
    }
}
