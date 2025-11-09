<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function show(Comment $comment)
    {
        Gate::authorize('view', $comment);

        return new CommentResource($comment->load(['user', 'commentable']));
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        if ($request->user()->cannot('update', $comment)) {
            return response()->json([
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

        return new CommentResource($comment->load('user'));
    }

    public function destroy(Comment $comment)
    {
        if (request()->user()->cannot('delete', $comment)) {
            return response()->json([
                'message' => 'You do not have permission to delete this comment',
            ], 403);
        }

        $comment->update([
            'deleted_by' => request()->user()->id,
        ]);

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully',
        ]);
    }
}
