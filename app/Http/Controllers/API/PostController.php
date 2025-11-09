<?php

namespace App\Http\Controllers\API;

use App\Actions\GetPerPageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
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
                AllowedFilter::callback('search', function (Builder $query, $value) { // for global search
                    $query->where('title', 'like', "%{$value}%")
                        ->orWhereHas('author', function ($query) use ($value) {
                            $query->where('name', 'like', "%{$value}%");
                        })
                        ->orWhereHas('category', function ($query) use ($value) {
                            $query->where('name', 'like', "%{$value}%");
                        })
                        ->orWhereHas('tags', function ($query) use ($value) {
                            $query->where('name', 'like', "%{$value}%");
                        });
                }),
            ])
            ->paginate(GetPerPageAction::execute($request));

        return PostResource::collection($posts);
    }

    public function store(StorePostRequest $request)
    {
        if (request()->user()->cannot('create', Post::class)) {
            return response()->json([
                'message' => 'You do not have permission to create post',
            ], 403);
        }

        $validated = $request->validated();

        $postOwnerId = $validated['user_id'] ?? $request->user()->id;

        $post = Post::create(array_merge($validated, [
            'user_id' => $request->user()->isAdmin() ? $postOwnerId : $request->user()->id, // only let admin set custom user_id, else set request's user id
            'created_by' => $request->user()->id,
        ]));

        if (! empty($validated['tags'])) {
            $post->tags()->attach($validated['tags']);
        }

        return new PostResource($post->load(['author', 'category', 'tags']));
    }

    public function show(Post $post)
    {
        Gate::authorize('view', $post);

        return new PostResource($post->load(['author', 'category', 'tags', 'comments']));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        if (request()->user()->cannot('update', $post)) {
            return response()->json([
                'message' => 'You do not have permission to edit this post',
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

    public function destroy(Post $post)
    {
        if (request()->user()->cannot('delete', $post)) {
            return response()->json([
                'message' => 'You do not have permission to delet this post',
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
