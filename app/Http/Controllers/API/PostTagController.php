<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttachTagRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\TagResource;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post)
    {
        return TagResource::collection($post->tags);
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
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
