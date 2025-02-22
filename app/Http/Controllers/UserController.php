<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function listUsers(Request $request)
    {

        if (strval($request->user()->type) !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Você não tem permissão para acessar essa rota'
            ], 403);
        }

        $users = User::all();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }


    public function store(Request $request)
    {
        try {
            $validated =  $request->validate(
                [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users,email',
                    'password' => 'required|min:8|max:255|confirmed',
                    'phone' => 'required|string|max:255|unique:users,phone',
                    'type' => 'in:cliente,barbeiro,admin'
                ]
            );

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'phone' => $validated['phone'],
                'type' => $validated['type']
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Usuário criado com sucesso',
                'data' => $user
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
