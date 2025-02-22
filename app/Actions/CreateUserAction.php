<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function execute(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }
}
