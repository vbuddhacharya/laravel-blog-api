<?php

namespace App\Http\Controllers\API;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(UserService $service, RegisterUserRequest $request)
    {
        $user = $service->store($request->validated());
        
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

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully',
            'data' => null,
        ], 200);
    }
}
