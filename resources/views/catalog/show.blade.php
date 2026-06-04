<x-layouts::app :title="$tshirtImage->name">
    <div class="grid gap-6 lg:grid-cols-[minmax(0,420px)_1fr]">
        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white">
            <img
                src="{{ asset('storage/tshirt_images/'.$tshirtImage->image_url) }}"
                alt="{{ $tshirtImage->name }}"
                class="aspect-square w-full object-contain p-8"
            >
        </div>

        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-3">
                <flux:heading size="xl">{{ $tshirtImage->name }}</flux:heading>
                @if ($tshirtImage->category)
                    <flux:badge>{{ $tshirtImage->category->name }}</flux:badge>
                @endif
                <flux:text>{{ $tshirtImage->description }}</flux:text>
            </div>

            <form method="POST" action="{{ route('cart.store', $tshirtImage) }}" class="grid gap-4 rounded-xl border border-neutral-200 p-4">
                @csrf

                <flux:select name="color_code" :label="__('Color')" required>
                    @foreach ($colors as $color)
                        <option value="{{ $color->code }}" @selected(old('color_code') === $color->code)>
                            {{ $color->name }}
                        </option>
                    @endforeach
                </flux:select>

                <flux:select name="size" :label="__('Size')" required>
                    @foreach ($sizes as $size)
                        <option value="{{ $size }}" @selected(old('size', 'M') === $size)>{{ $size }}</option>
                    @endforeach
                </flux:select>

                <flux:input name="qty" :label="__('Quantity')" type="number" min="1" max="99" value="{{ old('qty', 1) }}" required />

                <div class="flex gap-3">
                    <flux:button type="submit" variant="primary">{{ __('Add to cart') }}</flux:button>
                    <flux:button :href="route('catalog.index')" variant="filled" wire:navigate>{{ __('Back') }}</flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
