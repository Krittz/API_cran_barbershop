<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Actions\User\CreateUserAction;
use App\Actions\User\DeleteUserAction;
use App\Actions\User\UpdateUserAction;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Traits\ApiResponse;

class UserController extends Controller
{
    use ApiResponse;


    public function store(CreateUserRequest $request, CreateUserAction $createUser)
    {
        $user = $createUser($request->validated());

        return $this->successResponse(
            $user->only(['id', 'name', 'email', 'phone', 'role']),
            'Usuarios criado com sucesso',
            201
        );
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $updateUserAction)
    {
        $updatedUser = $updateUserAction($user, $request->validated());
        return $this->successResponse(
            $updatedUser->only(['id', 'name', 'phone']),
            'Dados atualizados com sucesso'
        );
    }

    public function destroy(DeleteUserRequest $request, User $user, DeleteUserAction $deleteUser)
    {
        $deleteUser($user);
        return $this->successResponse(null, 'Usuário desativado com sucesso.');
    }

    public function list()
    {
        return $this->successResponse(
            User::paginate(10),
            'Lista de usuários recuperada com sucesso.'
        );
    }
}
