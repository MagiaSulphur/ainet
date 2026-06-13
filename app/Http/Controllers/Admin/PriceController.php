<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Price;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PriceController extends Controller
{
    public function edit(): View
    {
        $this->authorizeAdmin();

        return view('admin.prices.edit', [
            'price' => $this->price(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'unit_price_catalog' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'unit_price_own' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'unit_price_catalog_discount' => ['required', 'numeric', 'min:0.01', 'max:999999.99', 'lte:unit_price_catalog'],
            'unit_price_own_discount' => ['required', 'numeric', 'min:0.01', 'max:999999.99', 'lte:unit_price_own'],
            'qty_discount' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $this->price()->update($validated);

        return redirect()->route('admin.prices.edit')->with('status', __('Prices updated.'));
    }

    private function price(): Price
    {
        return Price::query()->firstOrCreate([], [
            'unit_price_catalog' => 10,
            'unit_price_own' => 15,
            'unit_price_catalog_discount' => 8.5,
            'unit_price_own_discount' => 12,
            'qty_discount' => 5,
        ]);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}
