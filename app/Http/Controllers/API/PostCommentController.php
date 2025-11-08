<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;

class PostCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post)
    {
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
        $validated = $request->validated();

        $comment = $post->comments()->create(array_merge(
            $validated,
            [
                'created_by' => $request->user()->id,
                'user_id' => $validated['user_id'] ?? $request->user()->id,
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
    public function show(Comment $comment)
    {
        return response()->json([
            'success' => true,
            'data' => $comment->load('commentable', 'user'),
        ]);
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
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $validated = $request->validated();
        $comment->update(array_merge(
            $validated,
            [
                'updated_by' => $request->user()->id,
                'user_id' => $validated['user_id'] ?? $comment->user_id,
            ]
        ));

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully',
            'data' => $comment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->update([
            'deleted_by' => request()->user()->id,
        ]);

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ]);
    }
}
