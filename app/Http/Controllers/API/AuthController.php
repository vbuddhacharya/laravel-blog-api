<?php

namespace App\Http\Controllers\API;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
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

    public function login(LoginUserRequest $request)
    {
        $credentials = $request->validated();

        if (! auth()->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
                'data' => null,
            ], 401);
        }

        $user = auth()->user();
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully',
            'data' => [
                'user' => $user->name,
                'access_token' => $token,
            ],
        ], 200);
    }
}
