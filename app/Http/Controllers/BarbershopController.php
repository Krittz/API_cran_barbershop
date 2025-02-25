<?php

namespace App\Http\Controllers;

use App\Actions\Barbershop\CreateBarbershopAction;
use App\Http\Requests\Barbershop\CreateBarbershopRequest;
use App\Models\Barbershop;
use Illuminate\Http\Request;

class BarbershopController extends Controller
{

    public function store(CreateBarbershopRequest $request, CreateBarbershopAction $createBarbershop)
    {
        $barbershop = $createBarbershop($request->validated(), $request->user()->id);
        return response()->json([
            'status' => 'success',
            'message' => 'Barbearia criada com sucesso',
            'data' => $barbershop->only(['name', 'phone', 'address'])
        ], 201);
    }

    public function list(Request $request)
    {
        $perPage = $request->query('per_page') ?? 10;
        if ($request->user() && strval($request->user()->role) === 'adm') {
            $barbershops = Barbershop::paginate($perPage);
        } else {
            $barbershops = Barbershop::select('name', 'address', 'phone')->paginate($perPage);
        }
        return response()->json([
            'status' => 'success',
            'data' =>  $barbershops
        ]);
    }
}
