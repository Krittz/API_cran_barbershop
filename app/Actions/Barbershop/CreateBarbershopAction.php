<?php

namespace App\Actions\Barbershop;

use App\Models\Barbershop;
use Illuminate\Validation\ValidationException;

class CreateBarbershopAction
{
    public function __invoke(array $data, $userId)
    {
        try {
            return Barbershop::create([
                'name' => $data['name'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'owner_id' => $userId
            ]);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'error' => ['Erro ao criar usuário: ' . $e->getMessage()]
            ]);
        }
    }
}
