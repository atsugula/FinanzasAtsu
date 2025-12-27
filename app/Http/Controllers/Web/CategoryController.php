<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{

    public $types = [
        'income' => 'Ingreso',
        'expense' => 'Gasto',
    ];

    public function index(Request $request)
    {
        $request->validate([
            'type' => ['nullable', 'in:income,expense'],
            'archived' => ['nullable', 'in:0,1'],
        ]);

        $q = Category::query()
            ->where('user_id', auth()->id());

        if ($request->filled('type')) {
            $q->where('type', $request->string('type'));
        }

        if ($request->filled('archived')) {
            $q->where('is_archived', (bool) $request->integer('archived'));
        }

        $categories = $q->orderBy('is_archived')
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(30);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $category = new Category();
        $types = $this->types;
        return view('categories.create', compact('category', 'types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'icon' => ['nullable', 'string', 'max:120'],
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('categories', 'name')
                    ->where('user_id', auth()->id())
                    ->where('type', $request->input('type')),
            ],
        ]);

        Category::create([
            'user_id' => auth()->id(),
            'type' => $data['type'],
            'name' => $data['name'],
            'icon' => $data['icon'] ?? null,
            'is_archived' => false,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada.');
    }

    public function show(int $id)
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);
        $types = $this->types;
        return view('categories.show', compact('category', 'types'));
    }

    public function edit(int $id)
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);
        $types = $this->types;
        return view('categories.edit', compact('category', 'types'));
    }

    public function update(Request $request, int $id)
    {
        // dd($request->all());
        $category = Category::where('user_id', auth()->id())->findOrFail($id);

        $data = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'icon' => ['nullable', 'string', 'max:120'],
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('categories', 'name')
                    ->where('user_id', auth()->id())
                    ->where('type', $request->input('type'))
                    ->ignore($category->id),
            ],
            'is_archived' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'type' => $data['type'],
            'name' => $data['name'],
            'icon' => $data['icon'] ?? null,
            'is_archived' => (bool) ($data['is_archived'] ?? $category->is_archived),
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría actualizada.');
    }

    public function destroy(int $id)
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);

        // MVP: archivamos, no borramos
        $category->update(['is_archived' => true]);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría archivada.');
    }
}
