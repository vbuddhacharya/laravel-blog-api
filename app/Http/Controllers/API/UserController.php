<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::withCount('posts')->paginate();

        return UserResource::collection($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserService $service, RegisterUserRequest $request)
    {
        if ($request->user()->cannot('create', User::class)) {
            return response()->json([
                'message' => 'Unauthorized to create user',
            ], 403);
        }

        $user = $service->store($request->validated());

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user->load('posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if ($request->user()->cannot('update', $user)) {
            return response()->json([
                'message' => 'Unauthorized to update this user',
            ], 403);
        }

        $user->update(array_merge($request->validated(), [
            'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
            'updated_by' => $request->user()->id,
        ]));

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->user()->cannot('delete', $user)) {
            return response()->json([
                'message' => 'Unauthorized to delete this user',
            ], 403);
        }

        $user->update([
            'deleted_by' => auth()->user()->id,
        ]);

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ], 200);
    }
}
