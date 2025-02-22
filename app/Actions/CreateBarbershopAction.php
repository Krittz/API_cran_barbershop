<?php

namespace App\Actions;

use App\Models\Barbershop;

class CreateBarbershopAction
{
    public function __invoke(array $data, $userId) {
        return Barbershop::create([
            'name'=>$data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'owner_id' => $userId
        ]);
    }
}
