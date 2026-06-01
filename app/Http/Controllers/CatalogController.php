<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\TshirtImage;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = TshirtImage::query()
            ->whereNull('customer_id')
            ->with('category');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $tshirtImages = $query->paginate(12);
        $categories = Category::orderBy('name')->get();

        return view('catalog.index', compact('tshirtImages', 'categories'));
    }

    public function show(TshirtImage $tshirtImage)
    {
        abort_if($tshirtImage->customer_id !== null, 404);

        return view('catalog.show', [
            'tshirtImage' => $tshirtImage->load('category'),
            'colors' => Color::query()->orderBy('name')->get(),
            'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
        ]);
    }
}
