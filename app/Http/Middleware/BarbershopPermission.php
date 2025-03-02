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
        if (!in_array($request->user()?->role, ['barber', 'adm'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Você não tem permissão para criar uma barbearia'
            ], 403);
        }
        return $next($request);
    }
}
