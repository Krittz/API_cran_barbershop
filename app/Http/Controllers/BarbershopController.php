<?php

namespace App\Http\Controllers;

use App\Models\Barbershop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarbershopController extends Controller
{
    public function store(Request $request)
    {
        if (!in_array(strval($request->user()->type), ['barbeiro', 'admin'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Você não tem permissão para criar uma barbearia'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string|max:20|unique:barbershops,phone',
        ]);

        $barbershop = Barbershop::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'owner_id' => $request->user()->id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Barbearia criada com sucesso',
            'data' => $barbershop
        ], 201);
    }

    public function list(Request $request)
    {
        $perPage = $request->query('per_page') ?? 10;


        if ($request->user() && strval($request->user()->type) === 'admin') {
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
