<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::all();

        return response()->json([
            'succes' => true,
            'data' => $tags,
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
    public function store(StoreTagRequest $request)
    {
        $validated = $request->validated();

        $tag = Tag::create(array_merge($validated, [
            'created_by' => $request->user()->id,
            'slug' => Str::slug($validated['name']),
        ]));

        return response()->json([
            'succes' => true,
            'message' => 'Tag created successfully',
            'data' => $tag,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        return response()->json([
            'succes' => true,
            'data' => $tag->load('posts'),
        ]);
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
        $validated = $request->validated();

        $tag->update(array_merge($validated, [
            'slug' => Str::slug($validated['name']),
            'updated_by' => $request->user()->id,
        ]));

        return response()->json([
            'succes' => true,
            'message' => 'Tag updated successfully',
            'data' => $tag,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->update([
            'deleted_by' => request()->user()->id,
        ]);

        $tag->delete();

        return response()->json([
            'succes' => true,
            'message' => 'Tag deleted successfully',
        ]);
    }
}
