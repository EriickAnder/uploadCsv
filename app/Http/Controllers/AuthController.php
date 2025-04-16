<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function login(LoginRequest $request)
    {
        // Filtrando as credenciais do request
        $credentials = $request->only('email', 'password');

        // Chamando o método de login do AuthService
        $result = $this->authService->login($credentials);

        // Verificando se houve erro na autenticação
        if (isset($result['error'])) {
            return response()->json($result, 401);
        }

        // Retornando o token de acesso
        return response()->json($result);
    }
}
