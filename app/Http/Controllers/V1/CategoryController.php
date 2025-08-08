<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\V1\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Mostrar listado de categorías del usuario.
     */
    public function index()
    {
        $categories = Category::where('created_by', Auth::id())
            ->orderBy('id', 'DESC')->paginate();

        return view('categories.index', compact('categories'));
    }

    /**
     * Formulario para crear categoría.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Guardar categoría.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'icon'  => 'nullable|string|max:255',
            'type'  => 'required|in:income,expense,saving,debt',
        ]);

        Category::create([
            'name'       => $request->name,
            'icon'       => $request->icon,
            'type'       => $request->type,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Mostrar detalles de categoría.
     */
    public function show(Category $category)
    {
        $this->authorizeCategory($category);
        return view('categories.show', compact('category'));
    }

    /**
     * Formulario para editar categoría.
     */
    public function edit(Category $category)
    {
        $this->authorizeCategory($category);
        return view('categories.edit', compact('category'));
    }

    /**
     * Actualizar categoría.
     */
    public function update(Request $request, Category $category)
    {
        $this->authorizeCategory($category);

        $request->validate([
            'name'  => 'required|string|max:255',
            'icon'  => 'nullable|string|max:255',
            'type'  => 'required|in:income,expense,saving,debt',
        ]);

        $category->update([
            'name'  => $request->name,
            'icon'  => $request->icon,
            'type'  => $request->type,
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Eliminar categoría.
     */
    public function destroy(Category $category)
    {
        $this->authorizeCategory($category);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoría eliminada correctamente.');
    }

    /**
     * Evitar que un usuario acceda a categorías ajenas.
     */
    protected function authorizeCategory(Category $category)
    {
        if ($category->created_by !== Auth::id()) {
            abort(403, 'No tienes permiso para acceder a esta categoría.');
        }
    }
}
