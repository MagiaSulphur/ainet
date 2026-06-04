<x-layouts::app :title="__('Order #:id', ['id' => $order->id])">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div>
                <flux:heading size="xl">{{ __('Order #:id', ['id' => $order->id]) }}</flux:heading>
                <flux:text>{{ $order->customer->user->name }} · {{ $order->date?->format('Y-m-d') }} · {{ ucfirst($order->status) }}</flux:text>
            </div>

            <div class="flex flex-wrap gap-3">
                @if ($order->receipt_url && (auth()->user()->isAdmin() || auth()->user()->isCustomer()))
                    <flux:button :href="route('orders.receipt', $order)" variant="filled">{{ __('Receipt') }}</flux:button>
                @endif

                @if ((auth()->user()->isAdmin() || auth()->user()->isEmployee()) && $order->status === 'pending')
                    <form method="POST" action="{{ route('orders.close', $order) }}">
                        @csrf
                        @method('PATCH')
                        <flux:button type="submit" variant="primary">{{ __('Close') }}</flux:button>
                    </form>
                @endif
            </div>
        </div>

        @if (session('status'))
            <flux:callout color="green">{{ session('status') }}</flux:callout>
        @endif

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-xl border border-neutral-200 p-4">
                <flux:heading>{{ __('Delivery') }}</flux:heading>
                <dl class="mt-3 space-y-2 text-sm">
                    <div><dt class="font-medium">{{ __('NIF') }}</dt><dd>{{ $order->nif }}</dd></div>
                    <div><dt class="font-medium">{{ __('Address') }}</dt><dd>{{ $order->address }}</dd></div>
                    <div><dt class="font-medium">{{ __('Payment') }}</dt><dd>{{ $order->payment_type }} · {{ $order->payment_ref }}</dd></div>
                    @if ($order->notes)
                        <div><dt class="font-medium">{{ __('Notes') }}</dt><dd>{{ $order->notes }}</dd></div>
                    @endif
                </dl>
            </div>

            @if (auth()->user()->isAdmin())
                <form method="POST" action="{{ route('orders.cancel', $order) }}" class="rounded-xl border border-neutral-200 p-4">
                    @csrf
                    @method('PATCH')
                    <flux:heading>{{ __('Cancellation') }}</flux:heading>
                    <flux:textarea name="reason_for_cancellation" class="mt-3" :label="__('Reason')">{{ old('reason_for_cancellation', $order->reason_for_cancellation) }}</flux:textarea>
                    <flux:button type="submit" variant="danger" class="mt-3">{{ __('Cancel order') }}</flux:button>
                </form>
            @elseif ($order->reason_for_cancellation)
                <div class="rounded-xl border border-neutral-200 p-4">
                    <flux:heading>{{ __('Cancellation reason') }}</flux:heading>
                    <flux:text class="mt-3">{{ $order->reason_for_cancellation }}</flux:text>
                </div>
            @endif
        </div>

        <div class="overflow-hidden rounded-xl border border-neutral-200">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-200 bg-neutral-50">
                    <tr>
                        <th class="px-4 py-3">{{ __('Image') }}</th>
                        <th class="px-4 py-3">{{ __('Color') }}</th>
                        <th class="px-4 py-3">{{ __('Size') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Qty') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Subtotal') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img
                                        src="{{ route('tshirt-images.file', $item->tshirtImage) }}"
                                        alt="{{ $item->tshirtImage->name }}"
                                        class="size-12 rounded bg-white object-contain p-1"
                                    >
                                    <span>{{ $item->tshirtImage->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $item->color->name }}</td>
                            <td class="px-4 py-3">{{ $item->size }}</td>
                            <td class="px-4 py-3 text-right">{{ $item->qty }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format((float) $item->sub_total, 2) }} EUR</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-right">
            <flux:heading size="lg">{{ __('Total') }}: {{ number_format((float) $order->total_price, 2) }} EUR</flux:heading>
        </div>
    </div>
</x-layouts::app>
