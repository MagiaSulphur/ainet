<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Order::query()
            ->with(['customer.user'])
            ->latest('date')
            ->latest('id');

        if ($user->isCustomer()) {
            $query->where('customer_id', $user->id);
        } elseif ($user->isEmployee()) {
            $query->where('status', 'pending');
        } else {
            $query
                ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
                ->when($request->filled('customer'), function ($query) use ($request) {
                    $query->whereHas('customer.user', function ($query) use ($request) {
                        $query->where('name', 'like', '%'.$request->customer.'%')
                            ->orWhere('email', 'like', '%'.$request->customer.'%');
                    });
                });
        }

        return view('orders.index', [
            'orders' => $query->paginate(20)->withQueryString(),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        $this->authorizeOrder($request, $order);

        return view('orders.show', [
            'order' => $order->load(['customer.user', 'items.tshirtImage', 'items.color']),
        ]);
    }

    public function close(Request $request, Order $order): RedirectResponse
    {
        abort_unless($request->user()->isAdmin() || $request->user()->isEmployee(), 403);

        $order->update([
            'status' => 'closed',
            'reason_for_cancellation' => null,
        ]);

        return redirect()->route('orders.show', $order)->with('status', __('Order closed.'));
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        abort_unless($request->user()->isAdmin(), 403);

        $validated = $request->validate([
            'reason_for_cancellation' => ['nullable', 'string', 'max:2000'],
        ]);

        $order->update([
            'status' => 'canceled',
            'reason_for_cancellation' => $validated['reason_for_cancellation'] ?? null,
        ]);

        return redirect()->route('orders.show', $order)->with('status', __('Order canceled.'));
    }

    public function receipt(Request $request, Order $order): StreamedResponse
    {
        abort_unless($request->user()->isAdmin() || ($request->user()->isCustomer() && $order->customer_id === $request->user()->id), 403);
        abort_if(! $order->receipt_url, 404);

        return Storage::disk('local')->download('private/pdf_receipts/'.$order->receipt_url);
    }

    private function authorizeOrder(Request $request, Order $order): void
    {
        $user = $request->user();

        abort_unless(
            $user->isAdmin()
            || ($user->isEmployee() && $order->status === 'pending')
            || ($user->isCustomer() && $order->customer_id === $user->id),
            403
        );
    }
}
