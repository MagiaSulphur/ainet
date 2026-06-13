<x-layouts::app :title="$tshirtImage->name">
    @php
        $selectedColor = $colors->firstWhere('code', old('color_code')) ?? $colors->first();
        $selectedSize = old('size', 'M');
    @endphp

    <div class="shop-shell flex flex-col gap-6">
        <a href="{{ route('catalog.index') }}" class="shop-link-button shop-link-button-secondary w-fit px-4" wire:navigate>
            {{ __('Back to catalog') }}
        </a>

        <section class="grid gap-6 lg:grid-cols-[minmax(0,460px)_1fr]">
            <div class="shop-card overflow-hidden">
                <div
                    class="shop-image-panel shop-tshirt-preview"
                    data-tshirt-preview
                    data-selected-color="{{ $selectedColor?->code }}"
                >
                    <img
                        data-tshirt-base
                        src="{{ $selectedColor ? asset('storage/tshirt_base/'.$selectedColor->code.'.jpg') : '' }}"
                        alt="{{ $selectedColor ? __('T-shirt in :color', ['color' => $selectedColor->name]) : __('T-shirt') }}"
                        class="shop-tshirt-base"
                    >
                    <img
                        src="{{ asset('storage/tshirt_images/'.$tshirtImage->image_url) }}"
                        alt="{{ $tshirtImage->name }}"
                        class="shop-tshirt-design"
                    >
                </div>
                <div class="shop-tshirt-caption">
                    <span>{{ __('Selected color') }}</span>
                    <strong data-selected-color-name class="dark:text-black">{{ $selectedColor?->name ?? __('Unavailable') }}</strong>
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
                            <div class="text-2xl font-black text-[#1f1f24] dark:text-black">{{ number_format((float) $price->unit_price_catalog_discount, 2) }} EUR</div>
                        </div>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('cart.store', $tshirtImage) }}"
                    class="shop-card grid gap-5 p-5"
                    data-product-configurator
                >
                    @csrf
                    <input type="hidden" name="color_code" value="{{ $selectedColor?->code }}" data-selected-color-input>
                    <input type="hidden" name="size" value="{{ $selectedSize }}" data-selected-size-input>

                    <div>
                        <div class="mb-3 text-sm font-black text-[#1f1f24] dark:text-white">{{ __('Available colors') }}</div>
                        <div class="flex flex-wrap gap-2" role="group" aria-label="{{ __('Available colors') }}">
                            @foreach ($colors as $color)
                                @php
                                    $cssColor = str_starts_with($color->code, '#') || ! preg_match('/^[A-Fa-f0-9]{3,8}$/', $color->code)
                                        ? $color->code
                                        : '#'.$color->code;
                                @endphp
                                <button
                                    type="button"
                                    class="shop-color-option"
                                    data-color-option
                                    data-color-code="{{ $color->code }}"
                                    data-color-name="{{ $color->name }}"
                                    data-base-image="{{ asset('storage/tshirt_base/'.$color->code.'.jpg') }}"
                                    aria-pressed="{{ $selectedColor?->code === $color->code ? 'true' : 'false' }}"
                                >
                                    <span class="size-4 rounded-full border border-neutral-300" style="background-color: {{ $cssColor }}"></span>
                                    {{ $color->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <div class="mb-3 text-sm font-black text-[#1f1f24] dark:text-white">{{ __('Available sizes') }}</div>
                        <div class="flex flex-wrap gap-2" role="group" aria-label="{{ __('Available sizes') }}">
                            @foreach ($sizes as $size)
                                <button
                                    type="button"
                                    class="shop-size-option"
                                    data-size-option
                                    data-size="{{ $size }}"
                                    aria-pressed="{{ $selectedSize === $size ? 'true' : 'false' }}"
                                >
                                    {{ $size }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="shop-purchase-row">
                        <label class="shop-quantity-field">
                            <span>{{ __('Quantity') }}</span>
                            <input
                                type="number"
                                name="qty"
                                min="1"
                                max="99"
                                value="{{ old('qty', 1) }}"
                                required
                            >
                        </label>

                        <button type="submit" class="shop-add-to-cart">
                            {{ __('Add to cart') }}
                        </button>
                    </div>

                    @if ($errors->any())
                        <div class="shop-form-error" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif
                </form>
            </div>
        </section>
    </div>
</x-layouts::app>
