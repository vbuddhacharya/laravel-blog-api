<?php

namespace App\Http\Controllers\API;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {

        $user = User::create(array_merge(
            $request->validated(),
            ['role' => RoleEnum::AUTHOR]
        ));

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => $user,
        ], 201);
    }
}
