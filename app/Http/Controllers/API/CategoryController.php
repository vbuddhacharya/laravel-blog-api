<?php

namespace App\Http\Controllers\API;

use App\Actions\GetPerPageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Category::class);

        $categories = Category::paginate(GetPerPageAction::execute($request));

        return CategoryResource::collection($categories);
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
    public function store(StoreCategoryRequest $request)
    {
        if ($request->user()->cannot('create', Category::class)) {
            return response()->json([
                'message' => 'You do not have permission to create a category',
            ], 403);
        }

        $validated = $request->validated();

        $category = Category::create(array_merge(
            $validated,
            [
                'created_by' => $request->user()->id,
            ]
        ));

        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        Gate::authorize('view', $category);

        return new CategoryResource($category->load('posts'));
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
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if ($request->user()->cannot('update', $category)) {
            return response()->json([
                'message' => 'You do not have permission to update this category',
            ], 403);
        }

        $validated = $request->validated();

        $category->update(array_merge(
            $validated,
            [
                'slug' => Str::slug($validated['name']),
                'updated_by' => $request->user()->id,
            ]
        ));

        return new CategoryResource($category->load('posts'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if (request()->user()->cannot('delete', $category)) {
            return response()->json([
                'message' => 'You do not have permission to delete this category',
            ], 403);
        }

        $category->update([
            'deleted_by' => request()->user()->id,
        ]);

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully',
        ], 200);
    }
}
