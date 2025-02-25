<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UpdateUserAction
{
    public function __invoke(User $user, array $data)
    {
        if(Auth::id() !== $user->id){
            throw ValidationException::withMessages([
                'auth'=> ['Você não tem permissão para alterar os dados de outro usuário.'],
            ]);
        }
        if (!Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['A senha fornecida está incorreta.'],
            ]);
        }
        $user->update([
            'name' => $data['name'] ?? $user->name,
            'phone' => $data['phone'] ?? $user->phone
        ]);

        return $user;
    }
}
