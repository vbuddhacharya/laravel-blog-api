<?php

namespace App\Http\Controllers\API;

use App\Actions\GetPerPageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Post::class);

        $posts = QueryBuilder::for(Post::class)
            ->with(['author', 'category', 'tags'])
            ->allowedFilters([
                'title',
                AllowedFilter::partial('author', 'author.name'),
                AllowedFilter::partial('tag', 'tags.name'),
                AllowedFilter::partial('category', 'category.name'),
            ])
            ->paginate(GetPerPageAction::execute($request));

        return PostResource::collection($posts);
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
    public function store(StorePostRequest $request)
    {
        if (request()->user()->cannot('create', Post::class)) {
            return response()->json([
                'message' => 'Unauthorized to create post',
            ], 403);
        }

        $validated = $request->validated();

        $post = Post::create(array_merge($validated, [
            'user_id' => $validated['user_id'] ?? $request->user()->id,
            'created_by' => $request->user()->id,
        ]));

        return new PostResource($post->load(['author', 'category', 'tags']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        Gate::authorize('view', $post);

        return new PostResource($post->load(['author', 'category', 'tags', 'comments']));
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
    public function update(UpdatePostRequest $request, Post $post)
    {
        if (request()->user()->cannot('update', $post)) {
            return response()->json([
                'message' => 'Unauthorized to update post',
            ], 403);
        }

        $validated = $request->validated();

        $post->update(array_merge(
            $validated,
            [
                'updated_by' => $request->user()->id,
                'user_id' => $validated['user_id'] ?? $post->user_id, // only update user_id if provided
            ]
        ));

        return new PostResource($post->load(['author', 'category', 'tags', 'comments']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (request()->user()->cannot('delete', $post)) {
            return response()->json([
                'message' => 'Unauthorized to delete post',
            ], 403);
        }

        $post->update([
            'deleted_by' => request()->user()->id,
        ]);

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }
}
