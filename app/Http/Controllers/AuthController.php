<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|string|max:255',
            'password' => 'required|string|max:255|min:8'
        ]);

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            $user = Auth::user();

            $token = $user->createToken('CrAnBarber')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Usuário logado com sucesso',
            ])->cookie('token', $token, 60, null, null, false, true);
        }
        throw ValidationException::withMessages([
            'email' => ['As credenciais fornecidas estão incorretas']
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Usuário deslogado com sucesso'
        ], 200);
    }
}
