<?php

namespace App\Http\Controllers;

use App\Actions\CreateUserAction;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    protected $createUserAction;

    public function __construct(CreateUserAction $createUserAction)
    {
        $this->createUserAction = $createUserAction;
    }
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
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|min:8|max:255|confirmed',
                'phone' => 'required|string|max:255|unique:users,phone',
                'type' => 'required|string|in:cliente,barbeiro,admin'
            ]);
            $user = $this->createUserAction->execute($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Usuário criado com sucesso',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->getMessage()
            ], 422);
        }
    }
}
