<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BarbershopPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array($request->user()->type, ['barbeiro', 'admin'])) {
            return response()->json([
                'status' => 'erorr',
                'message' => 'Você não tem permissão para criar uma barbearia'
            ], 403);
        }
        return $next($request);
    }
}
