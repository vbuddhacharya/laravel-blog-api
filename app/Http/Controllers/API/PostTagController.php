<?php

namespace App\Http\Controllers\API;

use App\Actions\GetPerPageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttachTagRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\TagResource;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostTagController extends Controller
{
    public function index(Request $request, Post $post)
    {
        $tags = $post->tags()->paginate(GetPerPageAction::execute($request));

        return TagResource::collection($tags);
    }

    public function store(AttachTagRequest $request, Post $post)
    {
        if ($request->user()->cannot('update', $post)) {
            return response()->json([
                'message' => 'You do not have permission to attach tags to this post',
            ], 403);
        }

        $tags = $request->validated()['tags'];

        $post->tags()->syncWithoutDetaching($tags);

        return new PostResource($post->load('tags'));
    }

    public function destroy(Post $post, Tag $tag)
    {
        if (request()->user()->cannot('update', $post)) {
            return response()->json([
                'message' => 'You do not have permission to detach tags from this post',
            ], 403);
        }

        $post->tags()->detach($tag->id);

        return new PostResource($post->load('tags'));
    }
}
