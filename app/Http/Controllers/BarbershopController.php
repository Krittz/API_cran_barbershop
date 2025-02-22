<?php

namespace App\Http\Controllers;

use App\Actions\CreateBarbershopAction;
use App\Models\Barbershop;
use Illuminate\Http\Request;

class BarbershopController extends Controller
{
    protected $createBarbershopAction;
    public function __construct(CreateBarbershopAction $createBarbershopAction)
    {
        $this->createBarbershopAction = $createBarbershopAction;
    }

    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255|unique:barbershops,address',
                'phone' => 'required|string|max:20|unique:barbershops,phone',
            ]);
            $barbershop = $this->createBarbershopAction->__invoke($validated, $request->user()->id);

            return response()->json([
                'status' => 'success',
                'message' => 'Barbearia criada com sucesso.',
                'data' => $barbershop
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao cadastrar barbearia',
                'errors' => $e->getMessage()
            ]);
        }
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
