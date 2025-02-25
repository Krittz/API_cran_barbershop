<?php

namespace App\Actions\User;

use App\Models\User;

class CreateUserAction
{
    public function __invoke(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'],
            'role' => $data['role'] ?? 'user'
        ]);
    }
}
