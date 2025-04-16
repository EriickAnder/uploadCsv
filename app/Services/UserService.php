<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * Retorna a lista de usuários importados com paginação.
     */
    public function getImportedUsers(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 15); // Deixei 15 por padrão
            return User::paginate($perPage);
        } catch (\Exception $e) {
            Log::error("Erro ao buscar usuários importados");
            return response()->json(['error' => 'Erro ao buscar usuários importados: ' . $e->getMessage()], 500);
        }
    }
}
