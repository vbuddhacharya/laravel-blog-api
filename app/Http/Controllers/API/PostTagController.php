<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttachTagRequest;
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
        return response()->json([
            'success' => true,
            'data' => $post->tags,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttachTagRequest $request, Post $post)
    {
        $tags = $request->validated()['tags'];

        $post->tags()->syncWithoutDetaching($tags);

        return response()->json([
            'success' => true,
            'message' => 'Tags attached successfully',
            'data' => $post->tags,
        ], 200);
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
        $post->tags()->detach($tag->id);

        return response()->json([
            'success' => true,
            'message' => 'Tag detached successfully',
            'data' => $post->tags,
        ], 200);
    }
}
