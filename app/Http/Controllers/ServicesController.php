<?php

namespace App\Http\Controllers;

use App\Models\Barbershop;
use App\Models\Services;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function store(Request $request)
    {
      
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'time' => 'required|integer',
            'barbershop_id' => 'required|integer|exists:barbershops,id'
        ]);
        if ($request->user()->type === 'barbeiro') {
            $barbershop = Barbershop::where('id', $validated['barbershop_id'])
                ->where('owner_id', $request->user()->id)
                ->first();

            if (!$barbershop) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Você não pode criar serviços para essa barbearia'
                ], 403);
            }
        }
        $service = Services::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'time' => $validated['time'],
            'barbershop_id' => $validated['barbershop_id']
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Serviço criado com sucesso',
            'data' => $service
        ], 201);
    }

    public function list(Request $request)
    {
        $perPage = $request->query('per_page') ?? 10;
        $query = Services::query();
        if ($request->has('name')) {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }

        if ($request->has('barbershop_id')) {
            $query->where('barbershop_id', $request->query('barbershop_id'));
        }
        if ($request->user() && strval($request->user()->type) === 'admin') {
            $services = $query->paginate($perPage);
        } else {
            $services = $query->select('name', 'price', 'time')->paginate($perPage);
        }
        return response()->json([
            'status' => 'success',
            'data' => $services
        ]);
    }

    public function listByBarbershop(Request $request, $barbershop_id)
    {

        $perPage = $request->query('per_page', 10);

        $barbershop = Barbershop::find($barbershop_id);
        if (!$barbershop) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barbearia não encontrada'
            ], 404);
        }
        if ($request->user() && strval($request->user()->type) === 'admin') {
            $services = Services::where('barbershop_id', $barbershop_id)->paginate($perPage);
        } else {
            $services = Services::where('barbershop_id', $barbershop_id)
                ->select('name', 'price', 'time')
                ->paginate($perPage);
        }
        return response()->json([
            'status' => 'success',
            'barbershop' => $barbershop->name,
            'data' => $services
        ]);
    }
}
