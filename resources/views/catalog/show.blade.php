<x-layouts::app :title="$tshirtImage->name">
    <div class="shop-shell flex flex-col gap-6">
        <a href="{{ route('catalog.index') }}" class="shop-link-button shop-link-button-secondary w-fit px-4" wire:navigate>
            {{ __('Back to catalog') }}
        </a>

        <section class="grid gap-6 lg:grid-cols-[minmax(0,460px)_1fr]">
            <div class="shop-card overflow-hidden">
                <div class="shop-image-panel">
                    <img
                        src="{{ asset('storage/tshirt_images/'.$tshirtImage->image_url) }}"
                        alt="{{ $tshirtImage->name }}"
                        class="aspect-square w-full object-contain p-8"
                    >
                </div>
            </div>

            <div class="flex flex-col gap-6">
                <div class="shop-hero p-6">
                    <div class="shop-kicker">{{ __('Catalog design') }}</div>
                    <h1 class="mt-2 text-4xl font-black leading-tight text-[#1f1f24] dark:text-white">{{ $tshirtImage->name }}</h1>

                    @if ($tshirtImage->category)
                        <div class="mt-4">
                            <span class="shop-chip px-3 py-1">{{ $tshirtImage->category->name }}</span>
                        </div>
                    @endif

                    <p class="mt-4 text-base font-medium text-neutral-600 dark:text-neutral-400 dark:text-neutral-400">{{ $tshirtImage->description }}</p>
                </div>

                @if ($price)
                    <div class="shop-card grid gap-4 p-5 sm:grid-cols-2">
                        <div>
                            <div class="text-sm font-bold text-neutral-500 dark:text-neutral-400">{{ __('Unit price') }}</div>
                            <div class="text-3xl font-black text-[#ff2dd1]">{{ number_format((float) $price->unit_price_catalog, 2) }} EUR</div>
                        </div>
                        <div class="rounded-lg border-2 border-[#1f1f24] bg-[#4dffbe] p-4">
                            <div class="text-sm font-bold text-neutral-600 dark:text-neutral-400 dark:text-neutral-400">{{ __('From :qty units', ['qty' => $price->qty_discount]) }}</div>
                            <div class="text-2xl font-black text-[#1f1f24] dark:text-white">{{ number_format((float) $price->unit_price_catalog_discount, 2) }} EUR</div>
                        </div>
                    </div>
                @endif

                <div class="shop-card grid gap-5 p-5">
                    <div>
                        <div class="mb-3 text-sm font-black text-[#1f1f24] dark:text-white">{{ __('Available colors') }}</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($colors as $color)
                                @php
                                    $cssColor = str_starts_with($color->code, '#') || ! preg_match('/^[A-Fa-f0-9]{3,8}$/', $color->code)
                                        ? $color->code
                                        : '#'.$color->code;
                                @endphp
                                <span class="inline-flex items-center gap-2 rounded-full border-2 border-[#1f1f24] bg-white px-3 py-2 text-sm font-bold dark:text-black">
                                    <span class="size-4 rounded-full border border-neutral-300" style="background-color: {{ $cssColor }}"></span>
                                    {{ $color->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <div class="mb-3 text-sm font-black text-[#1f1f24] dark:text-white">{{ __('Available sizes') }}</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($sizes as $size)
                                <span class="inline-flex min-w-11 justify-center rounded-lg border-2 border-[#1f1f24] bg-[#63c8ff] px-3 py-2 text-sm font-black text-[#1f1f24] dark:text-white">{{ $size }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layouts::app>
