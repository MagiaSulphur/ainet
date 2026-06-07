<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdmin();

        return view('admin.categories.index', [
            'categories' => Category::query()
                ->withCount('tshirtImages')
                ->orderBy('name')
                ->paginate(20),
        ]);
    }

    public function create(): View
    {
        $this->authorizeAdmin();

        return view('admin.categories.create', [
            'category' => new Category(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $this->validated($request);
        unset($validated['image']);

        $category = Category::create($validated);
        $this->storeImage($request, $category);

        return redirect()->route('admin.categories.index')->with('status', __('Category created.'));
    }

    public function edit(Category $category): View
    {
        $this->authorizeAdmin();

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $this->validated($request);
        unset($validated['image']);

        $category->update($validated);
        $this->storeImage($request, $category);

        return redirect()->route('admin.categories.index')->with('status', __('Category updated.'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorizeAdmin();

        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', __('Category removed.'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function storeImage(Request $request, Category $category): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        if ($category->image_url) {
            Storage::disk('public')->delete('categories/'.$category->image_url);
        }

        $path = $request->file('image')->store('categories', 'public');
        $category->update(['image_url' => basename($path)]);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}
