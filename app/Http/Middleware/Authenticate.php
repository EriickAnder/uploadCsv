<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            // Se você não tem rota de login, pode retornar uma resposta JSON
            return response()->json([
                'message' => 'Não autenticado. Faça login para acessar este recurso.'
            ], 401);
        }
    }
}
