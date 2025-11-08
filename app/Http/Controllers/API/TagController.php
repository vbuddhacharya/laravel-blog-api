<?php

namespace App\Http\Controllers\API;

use App\Actions\GetPerPageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Tag::class);

        $tags = Tag::withCount('posts')->paginate(GetPerPageAction::execute($request));

        return TagResource::collection($tags);
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
    public function store(StoreTagRequest $request)
    {
        if ($request->user()->cannot('create', Tag::class)) {
            return response()->json([
                'message' => 'You do not have permission to create a tag.',
            ], 403);
        }

        $validated = $request->validated();

        $tag = Tag::create(array_merge($validated, [
            'created_by' => $request->user()->id,
            'slug' => Str::slug($validated['name']),
        ]));

        return new TagResource($tag);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        Gate::authorize('view', $tag);

        return new TagResource($tag->load('posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit() {}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        if ($request->user()->cannot('update', $tag)) {
            return response()->json([
                'message' => 'You do not have permission to update this tag.',
            ], 403);
        }

        $validated = $request->validated();

        $tag->update(array_merge($validated, [
            'slug' => Str::slug($validated['name']),
            'updated_by' => $request->user()->id,
        ]));

        return new TagResource($tag);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        if (request()->user()->cannot('delete', $tag)) {
            return response()->json([
                'message' => 'You do not have permission to delete this tag.',
            ], 403);
        }

        $tag->update([
            'deleted_by' => request()->user()->id,
        ]);

        $tag->delete();

        return response()->json([
            'message' => 'Tag deleted successfully',
        ]);
    }
}
