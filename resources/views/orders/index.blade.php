<x-layouts::app :title="__('Orders')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <flux:heading size="xl">{{ __('Orders') }}</flux:heading>
                <flux:text>{{ __('Track purchase and processing history.') }}</flux:text>
            </div>

            @if (auth()->user()->isAdmin())
                <form method="GET" action="{{ route('orders.index') }}" class="flex flex-col gap-3 md:flex-row">
                    <flux:select name="status">
                        <option value="">{{ __('All statuses') }}</option>
                        @foreach (['pending', 'closed', 'canceled'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </flux:select>
                    <flux:input name="customer" placeholder="{{ __('Customer') }}" value="{{ request('customer') }}" />
                    <flux:button type="submit" variant="primary">{{ __('Filter') }}</flux:button>
                </form>
            @endif
        </div>

        @if (session('status'))
            <flux:callout color="green">{{ session('status') }}</flux:callout>
        @endif

        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900">
                    <tr>
                        <th class="px-4 py-3">{{ __('Order') }}</th>
                        <th class="px-4 py-3">{{ __('Customer') }}</th>
                        <th class="px-4 py-3">{{ __('Date') }}</th>
                        <th class="px-4 py-3">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($orders as $order)
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('orders.show', $order) }}" class="font-medium underline" wire:navigate>#{{ $order->id }}</a>
                            </td>
                            <td class="px-4 py-3">{{ $order->customer->user->name ?? 'Deleted Customer' }}</td>
                            <td class="px-4 py-3">{{ $order->date?->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">{{ ucfirst($order->status) }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format((float) $order->total_price, 2) }} EUR</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-neutral-500 dark:text-neutral-400" colspan="5">{{ __('No orders found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $orders->links() }}
    </div>
</x-layouts::app>
