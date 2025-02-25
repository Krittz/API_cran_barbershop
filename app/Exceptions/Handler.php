<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;
use Illuminate\Http\JsonResponse;
use App\Exceptions\InvalidPasswordException;

class Handler extends ExceptionHandler
{
    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Erro de validação',
            'errors' => $exception->errors(),
        ], $exception->status);
    }

    public function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Não autenticado. Faça login para continuar.',
        ], 401);
    }

    protected function forbidden($request, UnauthorizedActionException $exception)
    {
        return response()->json([
            'status' => 'error',
            'message' => $exception->getMessage(),
        ], 403);
    }

    protected function invalidPassword($request, InvalidPasswordException $exception)
    {
        return response()->json([
            'status' => 'error',
            'message' => $exception->getMessage(),
            'errors' => ['password' => [$exception->getMessage()]],
        ], 422);
    }

    protected function serverError($request, Throwable $exception)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Erro interno no servidor. Tente novamente mais tarde.'
        ], 500);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->invalidJson($request, $exception);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof UnauthorizedActionException) {
            return $this->forbidden($request, $exception);
        }

        if ($exception instanceof InvalidPasswordException) {
            return $this->invalidPassword($request, $exception);
        }

        return parent::render($request, $exception);
    }
}
