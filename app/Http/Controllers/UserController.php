<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Retorna a lista de usuários importados de forma paginada.
     */
    public function getedUsers(Request $request)
    {
        $users = $this->userService->getImportedUsers($request);

        return response()->json($users);
    }
}
