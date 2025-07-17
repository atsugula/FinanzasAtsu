<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\V1\ExpensesCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExpensesCategoryController extends Controller
{
    /**
     * Listar todas las categorías de gastos del usuario autenticado.
     */
    public function index()
    {
        $id_auth = Auth::id();
        $expensesCategories = ExpensesCategory::where('created_by', $id_auth)
            ->orderBy('id', 'DESC')
            ->paginate();

        return response()->json($expensesCategories);
    }

    /**
     * Guardar una nueva categoría de gastos.
     */
    public function store(Request $request)
    {
        $request->validate(ExpensesCategory::$rules);

        $data = $request->all();
        $data['created_by'] = Auth::id();

        $expensesCategory = ExpensesCategory::create($data);
        
        return response()->json([
                'message' => 'Category create successfully.',
                'data' => $expensesCategory
            ], 201);
    }

    /**
     * Mostrar una categoría específica.
     */
    public function show($id)
    {
        try {
            $expensesCategory = ExpensesCategory::where('id', $id)
                ->where('created_by', Auth::id())
                ->firstOrFail();

            return response()->json($expensesCategory);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    /**
     * Actualizar una categoría de gastos.
     */
    public function update(Request $request, $id)
    {
        try {
            $expensesCategory = ExpensesCategory::where('id', $id)
                ->where('created_by', Auth::id())
                ->firstOrFail();

            $request->validate(ExpensesCategory::$rules);
            $expensesCategory->update($request->all());

            return response()->json([
                'message' => 'Category updated successfully.',
                'data' => $expensesCategory
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    /**
     * Eliminar una categoría de gastos.
     */
    public function destroy($id)
    {
        try {
            $expensesCategory = ExpensesCategory::where('id', $id)
                ->where('created_by', Auth::id())
                ->firstOrFail();

            $expensesCategory->delete();

            return response()->json(['message' => 'Category deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }
}
