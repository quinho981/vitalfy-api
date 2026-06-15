<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Link de redefinição enviado para o e-mail informado.'
            ]);
        }

        return response()->json([
            'message' => 'Não foi possível enviar o link. Verifique o e-mail informado.'
        ], 422);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                $user->tokens()->delete();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Senha redefinida com sucesso.'
            ]);
        }

        $errorMessages = [
            Password::INVALID_TOKEN => 'Token inválido ou expirado.',
            Password::INVALID_USER  => 'Usuário não encontrado.',
        ];

        return response()->json([
            'message' => $errorMessages[$status] ?? 'Não foi possível redefinir a senha.'
        ], 422);
    }
}
