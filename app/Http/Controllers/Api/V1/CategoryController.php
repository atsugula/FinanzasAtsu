<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'type' => ['nullable', 'in:income,expense'],
        ]);

        $q = Category::query()
            ->where('user_id', auth()->id());

        if ($request->filled('type')) {
            $q->where('type', $request->string('type'));
        }

        $categories = $q->orderBy('is_archived')
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return CategoryResource::collection($categories);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create([
            'user_id' => auth()->id(),
            ...$request->validated(),
        ]);

        return (new CategoryResource($category))->response()->setStatusCode(201);
    }

    public function show(int $id)
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);
        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, int $id)
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);
        $category->update($request->validated());

        return new CategoryResource($category);
    }

    public function destroy(int $id)
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);

        // MVP: archivar, no borrar
        $category->update(['is_archived' => true]);

        return response()->json(['message' => 'Categoría archivada.']);
    }
}
