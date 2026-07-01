<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addHour(),
            ['id' => $user->getKey(), 'hash' => sha1($user->email)]
        );

        UserRegistered::dispatch($user, $verificationUrl);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'remember' => 'boolean',
        ]);

        if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user      = Auth::user();
        $remember  = $request->boolean('remember', false);
        $expiresAt = $remember ? now()->addDays(30) : now()->addDay();
        $minutes   = (int) now()->diffInMinutes($expiresAt);

        $token = $user->createToken('auth_token', ['*'], $expiresAt)->plainTextToken;

        return response()
            ->json(['remember' => $remember])
            ->withCookie($this->tokenCookie($token, $minutes))
            ->withCookie($this->loggedInCookie($minutes))
            ->withCookie($this->nonceCookie($minutes));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()
            ->json(['message' => 'Logged out successfully'])
            ->withCookie(cookie()->forget('api_token'))
            ->withCookie(cookie()->forget('logged_in'))
            ->withCookie(cookie()->forget('client_nonce'));
    }

    public function changePassword(Request $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json(['message' => 'A senha atual está incorreta!'], 422);
        }

        $user->update(['password' => Hash::make($validated['new_password'])]);

        return response()->json(['message' => 'Password updated successfully']);
    }

    public function tokens(Request $request): JsonResponse
    {
        return response()->json($request->user()->tokens);
    }

    public function revokeToken(Request $request, string $id): JsonResponse
    {
        $request->user()->tokens()->where('id', $id)->delete();

        return response()->json(['message' => 'Token revoked successfully']);
    }

    // --- TODO: Criar helper - helpers de cookie ---
    private function isProduction(): bool
    {
        return config('app.env') === 'production';
    }

    private function tokenCookie(string $token, int $minutes): \Symfony\Component\HttpFoundation\Cookie
    {
        // HttpOnly=true — inacessível via JS, protegido contra XSS
        return cookie(
            'api_token', $token, $minutes,
            '/', null,
            $this->isProduction(), // Secure
            true,                  // HttpOnly
            false,
            $this->isProduction() ? 'None' : 'Lax'  // SameSite
        );
    }

    private function loggedInCookie(int $minutes): \Symfony\Component\HttpFoundation\Cookie
    {
        // Não é HttpOnly — o frontend precisa ler para verificar autenticação
        return cookie(
            'logged_in', '1', $minutes,
            '/', null,
            $this->isProduction(), // Secure
            false,                 // HttpOnly
            false,
            $this->isProduction() ? 'None' : 'Lax'
        );
    }

    private function nonceCookie(int $minutes): \Symfony\Component\HttpFoundation\Cookie
    {
        // Chave de assinatura para HMAC no frontend — não é HttpOnly
        return cookie(
            'client_nonce', Str::random(40), $minutes,
            '/', null,
            $this->isProduction(), // Secure
            false,                 // HttpOnly
            false,
            $this->isProduction() ? 'None' : 'Lax'
        );
    }
}
