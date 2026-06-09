<x-layouts::app :title="__('Cart')">
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <flux:heading size="xl">{{ __('Cart') }}</flux:heading>
                <flux:text>{{ __('Review quantities, sizes and colors before checkout.') }}</flux:text>
            </div>

            @if ($items->isNotEmpty())
                <form method="POST" action="{{ route('cart.clear') }}">
                    @csrf
                    @method('DELETE')
                    <flux:button type="submit" variant="danger">{{ __('Clear') }}</flux:button>
                </form>
            @endif
        </div>

        @if (session('status'))
            <flux:callout color="green">{{ session('status') }}</flux:callout>
        @endif

        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            @forelse ($items as $item)
                <div class="grid gap-4 border-b border-neutral-200 p-4 last:border-b-0 dark:border-neutral-700 lg:grid-cols-[96px_1fr_auto]">
                    <img
                        src="{{ asset('storage/tshirt_images/'.$item['image']->image_url) }}"
                        alt="{{ $item['image']->name }}"
                        class="size-24 rounded-lg bg-white object-contain p-2"
                    >

                    <div class="flex flex-col gap-2">
                        <flux:heading>{{ $item['image']->name }}</flux:heading>
                        <flux:text>
                            {{ __('Unit') }}: {{ number_format($item['unit_price'], 2) }} EUR
                            @if ($item['discounted'])
                                · {{ __('quantity discount applied') }}
                            @endif
                        </flux:text>

                        <form method="POST" action="{{ route('cart.update', $item['key']) }}" class="grid gap-3 md:grid-cols-4">
                            @csrf
                            @method('PATCH')
                            <flux:select name="color_code" :label="__('Color')">
                                @foreach ($colors as $color)
                                    <option value="{{ $color->code }}" @selected($item['color_code'] === $color->code)>{{ $color->name }}</option>
                                @endforeach
                            </flux:select>
                            <flux:select name="size" :label="__('Size')">
                                @foreach ($sizes as $size)
                                    <option value="{{ $size }}" @selected($item['size'] === $size)>{{ $size }}</option>
                                @endforeach
                            </flux:select>
                            <flux:input name="qty" :label="__('Qty')" type="number" min="0" max="99" value="{{ $item['qty'] }}" />
                            <div class="flex items-end gap-2">
                                <flux:button type="submit" variant="filled">{{ __('Update') }}</flux:button>
                            </div>
                        </form>
                    </div>

                    <div class="flex flex-col items-start justify-between gap-3 lg:items-end">
                        <flux:heading>{{ number_format($item['sub_total'], 2) }} EUR</flux:heading>
                        <form method="POST" action="{{ route('cart.destroy', $item['key']) }}">
                            @csrf
                            @method('DELETE')
                            <flux:button type="submit" variant="danger">{{ __('Remove') }}</flux:button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-6 text-neutral-500 dark:text-neutral-400">{{ __('Your cart is empty.') }}</div>
            @endforelse
        </div>

        <div class="flex flex-col items-end gap-4">
            <flux:heading size="lg">{{ __('Total') }}: {{ number_format($total, 2) }} EUR</flux:heading>
            <div class="flex gap-3">
                <flux:button :href="route('catalog.index')" variant="filled" wire:navigate>{{ __('Continue shopping') }}</flux:button>
                @if ($items->isNotEmpty())
                    <flux:button :href="route('checkout.create')" variant="primary" wire:navigate>{{ __('Checkout') }}</flux:button>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
