<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TshirtImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CatalogImageController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAdmin();

        $query = TshirtImage::query()
            ->whereNull('customer_id')
            ->with('category')
            ->withCount('orderItems');

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('category_id')) {
            $request->category_id === 'none'
                ? $query->whereNull('category_id')
                : $query->where('category_id', $request->category_id);
        }

        return view('admin.catalog-images.index', [
            'images' => $query->latest()->paginate(20)->withQueryString(),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        $this->authorizeAdmin();

        return view('admin.catalog-images.create', [
            'image' => new TshirtImage(),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $this->validated($request, true);
        $file = $validated['image'];
        unset($validated['image']);

        $image = TshirtImage::create([
            ...$validated,
            'customer_id' => null,
            'category_id' => $validated['category_id'] ?: null,
            'image_url' => 'pending-upload',
        ]);

        $this->storeImage($file, $image);

        return redirect()->route('admin.catalog-images.index')->with('status', __('Catalog image created.'));
    }

    public function edit(TshirtImage $catalogImage): View
    {
        $this->authorizeAdmin();
        abort_if($catalogImage->customer_id !== null, 404);

        return view('admin.catalog-images.edit', [
            'image' => $catalogImage,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, TshirtImage $catalogImage): RedirectResponse
    {
        $this->authorizeAdmin();
        abort_if($catalogImage->customer_id !== null, 404);

        $validated = $this->validated($request, false);
        $file = $validated['image'] ?? null;
        unset($validated['image']);

        $catalogImage->update([
            ...$validated,
            'category_id' => $validated['category_id'] ?: null,
        ]);

        if ($file) {
            Storage::disk('public')->delete('tshirt_images/'.$catalogImage->image_url);
            $this->storeImage($file, $catalogImage);
        }

        return redirect()->route('admin.catalog-images.index')->with('status', __('Catalog image updated.'));
    }

    public function destroy(TshirtImage $catalogImage): RedirectResponse
    {
        $this->authorizeAdmin();
        abort_if($catalogImage->customer_id !== null, 404);

        $catalogImage->delete();

        return redirect()->route('admin.catalog-images.index')->with('status', __('Catalog image removed.'));
    }

    private function validated(Request $request, bool $imageRequired): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'max:8192'],
        ]);
    }

    private function storeImage(\Illuminate\Http\UploadedFile $file, TshirtImage $image): void
    {
        $name = str_pad((string) $image->id, 5, '0', STR_PAD_LEFT).'_'.$file->hashName();
        $file->storeAs('tshirt_images', $name, 'public');
        $image->update(['image_url' => $name]);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}
