<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'string'],
        ]);

        $query = TshirtImage::query()
            ->whereNull('customer_id')
            ->with('category');

        if ($request->filled('search')) {
            $search = $validated['search'];

            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (($validated['category_id'] ?? null) === 'none') {
            $query->whereNull('category_id');
        } elseif ($request->filled('category_id')) {
            $query->where('category_id', $validated['category_id']);
        }

        $tshirtImages = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
            ->withCount(['tshirtImages' => fn ($query) => $query->whereNull('customer_id')])
            ->orderBy('name')
            ->get();

        return view('catalog.index', [
            'tshirtImages' => $tshirtImages,
            'categories' => $categories,
            'price' => Price::query()->first(),
        ]);
    }

    public function show(TshirtImage $tshirtImage)
    {
        abort_if($tshirtImage->customer_id !== null, 404);

        return view('catalog.show', [
            'tshirtImage' => $tshirtImage->load('category'),
            'colors' => Color::query()->orderBy('name')->get(),
            'price' => Price::query()->first(),
            'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
        ]);
    }
}
