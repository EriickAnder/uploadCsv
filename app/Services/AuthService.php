<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthService
{
    public function login(array $credentials)
    {
        try {
            if (!$token = Auth::guard('admin')->attempt($credentials)) {
                return ['error' => 'Unauthorized'];
            }

            return [
                'access_token' => 'bearer ' . $token,

                'expires_in' => config('jwt.ttl') * 60
            ];
        } catch (\Exception $e) {
            Log::error("Erro ao realizar autenticação");
            return ['error' => 'Erro ao realizar ação'];
        }
    }
}
