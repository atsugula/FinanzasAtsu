<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\V1\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    private $types = [
        'income' => 'Ingreso',
        'expense' => 'Gasto',
        'saving' => 'Ahorro',
        'debt' => 'Deuda'
    ];

    /**
     * GET /api/v1/categories
     * Listar categorías propias
     */
    public function index()
    {
        $categories = Category::where('created_by', Auth::id())
            ->orderBy('id', 'DESC')
            ->paginate(15);

        return response()->json($categories);
    }

    /**
     * POST /api/v1/categories
     * Crear categoría
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'type' => 'required|in:income,expense,saving,debt',
        ]);

        $validated['created_by'] = Auth::id();

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Category created successfully.',
            'data' => $category
        ], 201);
    }

    /**
     * GET /api/v1/categories/{id}
     * Mostrar una categoría
     */
    public function show($id)
    {
        $category = Category::where('id', $id)
            ->where('created_by', Auth::id())
            ->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category);
    }

    /**
     * PUT/PATCH /api/v1/categories/{id}
     * Actualizar categoría
     */
    public function update(Request $request, $id)
    {
        $category = Category::where('id', $id)
            ->where('created_by', Auth::id())
            ->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'type' => 'required|in:income,expense,saving,debt',
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Category updated successfully.',
            'data' => $category
        ]);
    }

    /**
     * DELETE /api/v1/categories/{id}
     * Eliminar categoría
     */
    public function destroy($id)
    {
        $category = Category::where('id', $id)
            ->where('created_by', Auth::id())
            ->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.']);
    }
}
