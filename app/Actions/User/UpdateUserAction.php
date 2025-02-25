<?php

namespace App\Actions\User;

use App\Exceptions\InvalidPasswordException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    public function __invoke(User $user, array $data)
    {

        // Atualiza apenas os campos fornecidos
        $user->update([
            'name' => $data['name'] ?? $user->name,
            'phone' => $data['phone'] ?? $user->phone,
        ]);

        return $user;
    }
}
