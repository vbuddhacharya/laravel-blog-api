<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;

class PostCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post)
    {
        Gate::authorize('viewAny', Comment::class);

        $comments = $post->comments()->with('user')->get();

        return response()->json([
            'success' => true,
            'data' => $comments,
        ]);
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
    public function store(StoreCommentRequest $request, Post $post)
    {
        if (request()->user()->cannot('create', Comment::class)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to create a comment',
            ], 403);
        }

        $validated = $request->validated();

        $currentUserId = $request->user()->id;

        $comment = $post->comments()->create(array_merge(
            $validated,
            [
                'created_by' => $request->user()->id,
                'user_id' => $request->user()->isAdmin() ? $validated['user_id'] ?? $currentUserId : $currentUserId, // Ensure only admin can set different user id
            ]
        ));

        return response()->json([
            'success' => true,
            'message' => 'Comment created successfully',
            'data' => $comment,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
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
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
}
