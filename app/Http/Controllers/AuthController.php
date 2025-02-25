<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginAction;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{

    public function login(LoginRequest $request, LoginAction $loginAction)
    {
        $validated = $request->validated();
        $token = $loginAction($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Usuário logado com sucesso',

        ])->cookie('token', $token, 60, null, null, false, true);
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
