<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        Gate::authorize('view', $comment);

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
        if ($request->user()->cannot('update', $comment)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update this comment',
            ], 403);
        }

        $validated = $request->validated();

        $currentUserId = $request->user()->id;

        $comment->update(array_merge(
            $validated,
            [
                'updated_by' => $request->user()->id,
                'user_id' => $request->user()->isAdmin() ? $validated['user_id'] ?? $currentUserId : $currentUserId, // Ensure only admin can set different user id
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
        if (request()->user()->cannot('delete', $comment)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this comment',
            ], 403);
        }

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
