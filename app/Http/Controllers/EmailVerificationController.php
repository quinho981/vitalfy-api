<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailVerificationController extends Controller
{
    public function verify(Request $request, int $id, string $hash): RedirectResponse
    {
        $frontendUrl = config('app.frontend_url');
        $user = User::find($id);

        if (!$user) {
            return redirect("{$frontendUrl}/auth/email-verified?status=invalid");
        }

        if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return redirect("{$frontendUrl}/auth/email-verified?status=invalid");
        }

        if ($user->hasVerifiedEmail()) {
            return redirect("{$frontendUrl}/auth/email-verified?status=already_verified");
        }

        $user->markEmailAsVerified();

        return redirect("{$frontendUrl}/auth/email-verified?status=success");
    }

    public function resend(Request $request): Response
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response(['message' => 'E-mail já verificado.'], 422);
        }

        $user->sendEmailVerificationNotification();

        return response(['message' => 'Link de verificação reenviado com sucesso.']);
    }
}
