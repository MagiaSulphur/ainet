<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\TshirtImage;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(CartService $cart): View
    {
        return view('cart.index', [
            ...$cart->summary(),
            'colors' => Color::query()->orderBy('name')->get(),
            'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
        ]);
    }

    public function store(Request $request, TshirtImage $tshirtImage, CartService $cart): RedirectResponse
    {
        abort_if($tshirtImage->customer_id !== null, 404);

        $validated = $request->validate([
            'color_code' => ['required', 'exists:colors,code'],
            'size' => ['required', 'in:XS,S,M,L,XL'],
            'qty' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart->add($tshirtImage->id, $validated['color_code'], $validated['size'], $validated['qty']);

        return redirect()
            ->route('cart.index')
            ->with('status', __('Item added to cart.'));
    }

    public function update(Request $request, string $key, CartService $cart): RedirectResponse
    {
        $validated = $request->validate([
            'color_code' => ['required', 'exists:colors,code'],
            'size' => ['required', 'in:XS,S,M,L,XL'],
            'qty' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $cart->update($key, $validated['color_code'], $validated['size'], $validated['qty']);

        return redirect()
            ->route('cart.index')
            ->with('status', __('Cart updated.'));
    }

    public function destroy(string $key, CartService $cart): RedirectResponse
    {
        $cart->remove($key);

        return redirect()
            ->route('cart.index')
            ->with('status', __('Item removed.'));
    }

    public function clear(CartService $cart): RedirectResponse
    {
        $cart->clear();

        return redirect()
            ->route('cart.index')
            ->with('status', __('Cart cleared.'));
    }
}
