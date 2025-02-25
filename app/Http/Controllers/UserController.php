<?php

namespace App\Http\Controllers;

use App\Actions\User\CreateUserAction;
use App\Actions\User\DeleteUserAction;
use App\Actions\User\UpdateUserAction;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

        public function list()
        {
            return response()->json([
                'status' => 'success',
                'data' => User::all()
            ]);
        }
    public function store(CreateUserRequest $request, CreateUserAction $createUser)
    {
        $user = $createUser($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Usuário criado com sucesso',
            'data' => $user->only(['id', 'name', 'email', 'phone', 'role'])
        ], 201);
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $updateUserAction)
    {
        try {
            $updatedUser = $updateUserAction($user, $request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Dados atualizados com sucesso',
                'data' => $updatedUser->only(['id', 'name', 'phone'])
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'error' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao processar requisição',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy(DeleteUserRequest $request, User $user, DeleteUserAction $deleteUser)
    {
        $deleteUser($user);
        return response()->json([
            'status' => 'success',
            'message' => 'Usuário desativado com sucesso.'
        ], 200);
    }
}
