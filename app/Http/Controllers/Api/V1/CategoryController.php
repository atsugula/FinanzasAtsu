<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
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

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
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

        $category = Category::create([
            'user_id' => auth()->id(),
            'type' => $data['type'],
            'name' => $data['name'],
            'icon' => $data['icon'] ?? null,
            'is_archived' => false,
        ]);

        return response()->json([
            'success' => true,
            'data' => $category,
        ], 201);
    }

    public function show(int $id)
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $category,
        ]);
    }

    public function update(Request $request, int $id)
    {
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

        return response()->json([
            'success' => true,
            'data' => $category->fresh(),
        ]);
    }

    public function destroy(int $id)
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);

        // MVP: archivamos, no borramos
        $category->update(['is_archived' => true]);

        return response()->json([
            'success' => true,
            'data' => $category->fresh(),
        ]);
    }
}
