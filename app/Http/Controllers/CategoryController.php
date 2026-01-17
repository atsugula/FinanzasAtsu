<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $categories = Category::query()
            ->where('user_id', $user->id)
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(25);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'type' => ['required', 'in:income,expense'],
            'icon' => ['nullable', 'string', 'max:80'],
            'is_archived' => ['nullable', 'boolean'],
        ]);

        Category::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'type' => $data['type'],
            'icon' => $data['icon'] ?? null,
            'is_archived' => (bool) ($data['is_archived'] ?? false),
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría creada.');
    }

    public function edit(Request $request, Category $category)
    {
        $category = $this->ownedCategory($request->user()->id, $category->id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $category = $this->ownedCategory($request->user()->id, $category->id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'type' => ['required', 'in:income,expense'],
            'icon' => ['nullable', 'string', 'max:80'],
            'is_archived' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'icon' => $data['icon'] ?? null,
            'is_archived' => (bool) ($data['is_archived'] ?? false),
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada.');
    }

    private function ownedCategory(int $userId, int $id): Category
    {
        return Category::query()
            ->where('user_id', $userId)
            ->findOrFail($id);
    }
}
