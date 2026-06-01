<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(CartService $cart): View|RedirectResponse
    {
        $this->ensureCustomer();

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('status', __('Your cart is empty.'));
        }

        $customer = auth()->user()->customer;

        return view('checkout.create', [
            ...$cart->summary(),
            'customer' => $customer,
        ]);
    }

    public function store(Request $request, CartService $cart): RedirectResponse
    {
        $this->ensureCustomer();

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('status', __('Your cart is empty.'));
        }

        $validated = $request->validate([
            'nif' => ['required', 'digits:9'],
            'address' => ['required', 'string', 'max:1000'],
            'payment_type' => ['required', Rule::in(['Visa', 'PayPal', 'MB WAY'])],
            'payment_ref' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $this->validatePaymentReference($validated['payment_type'], $validated['payment_ref']);

        $summary = $cart->summary();
        $this->processPayment($validated['payment_type'], $validated['payment_ref'], $summary['total']);

        $order = DB::transaction(function () use ($validated, $summary) {
            $order = Order::create([
                'status' => 'pending',
                'customer_id' => auth()->id(),
                'date' => now()->toDateString(),
                'total_price' => $summary['total'],
                'notes' => $validated['notes'] ?? null,
                'nif' => $validated['nif'],
                'address' => $validated['address'],
                'payment_type' => $validated['payment_type'],
                'payment_ref' => $validated['payment_ref'],
            ]);

            foreach ($summary['items'] as $item) {
                $order->items()->create([
                    'tshirt_image_id' => $item['image']->id,
                    'color_code' => $item['color_code'],
                    'size' => $item['size'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'sub_total' => $item['sub_total'],
                ]);
            }

            return $order;
        });

        $cart->clear();

        return redirect()
            ->route('orders.show', $order)
            ->with('status', __('Order created successfully.'));
    }

    private function ensureCustomer(): void
    {
        abort_unless(auth()->user()?->isCustomer(), 403);
    }

    private function validatePaymentReference(string $type, string $reference): void
    {
        $valid = match ($type) {
            'Visa' => preg_match('/^4\d{15}$/', $reference),
            'PayPal' => filter_var($reference, FILTER_VALIDATE_EMAIL),
            'MB WAY' => preg_match('/^9\d{8}$/', $reference),
        };

        if (! $valid) {
            throw ValidationException::withMessages([
                'payment_ref' => __('The payment reference does not match the selected payment method.'),
            ]);
        }
    }

    private function processPayment(string $type, string $reference, float $value): void
    {
        $endpoint = rtrim(config('services.payments.url', 'https://ainet-payments-api.vercel.app'), '/').'/api/payments';

        $response = Http::timeout(10)->post($endpoint, [
            'type' => $type,
            'reference' => $reference,
            'value' => $value,
        ]);

        if (! $response->created()) {
            throw ValidationException::withMessages([
                'payment_ref' => $response->json('message') ?? __('Payment was rejected.'),
            ]);
        }
    }
}
