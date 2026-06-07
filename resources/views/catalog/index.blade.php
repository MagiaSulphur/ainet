<x-layouts::app :title="__('Catalog')">
    <div class="shop-shell flex flex-col gap-8">
        <section class="shop-hero grid gap-6 p-6 md:grid-cols-[1fr_auto] md:items-end">
            <div class="max-w-2xl">
                <div class="shop-kicker">{{ __('FunShirt store') }}</div>
                <h1 class="mt-2 text-4xl font-black text-[#1f1f24] sm:text-5xl">{{ __('Catalog') }}</h1>
                <p class="mt-3 max-w-xl text-base font-medium text-neutral-700">
                    {{ __('Explore public t-shirt designs by category, style and name.') }}
                </p>
            </div>

            @if ($price)
                <div class="grid min-w-52 gap-2 rounded-lg border-2 border-[#1f1f24] bg-white p-4">
                    <div class="text-sm font-bold text-neutral-500">{{ __('From') }}</div>
                    <div class="text-3xl font-black text-[#ff2dd1]">{{ number_format((float) $price->unit_price_catalog, 2) }} EUR</div>
                    <div class="rounded-md bg-[#4dffbe] px-3 py-2 text-sm font-bold text-[#1f1f24]">
                        {{ __('Discount from :qty units', ['qty' => $price->qty_discount]) }}
                    </div>
                </div>
            @endif
        </section>

        <form method="GET" action="{{ route('catalog.index') }}" class="shop-search grid gap-3 p-4 lg:grid-cols-[minmax(220px,1fr)_240px_auto_auto]">
            <flux:input
                name="search"
                icon="magnifying-glass"
                placeholder="{{ __('Search designs...') }}"
                value="{{ request('search') }}"
            />

            <flux:select name="category_id">
                <option value="">{{ __('All categories') }}</option>
                <option value="none" @selected(request('category_id') === 'none')>{{ __('Without category') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </flux:select>

            <button type="submit" class="shop-link-button px-5">{{ __('Filter') }}</button>
            <a href="{{ route('catalog.index') }}" class="shop-link-button shop-link-button-secondary px-5" wire:navigate>{{ __('Reset') }}</a>
        </form>

        <section class="flex flex-col gap-3">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <div class="shop-kicker">{{ __('Departments') }}</div>
                    <h2 class="text-2xl font-black text-[#1f1f24]">{{ __('Shop by category') }}</h2>
                </div>
            </div>

            <div class="flex gap-3 overflow-x-auto pb-2">
                <a
                    href="{{ route('catalog.index', request()->only('search')) }}"
                    class="shop-category flex min-w-40 flex-col justify-center p-4"
                    wire:navigate
                >
                    <span class="font-black">{{ __('All designs') }}</span>
                    <span class="mt-1 text-sm font-semibold text-neutral-500">{{ $categories->sum('tshirt_images_count') }} {{ __('items') }}</span>
                </a>

                @foreach ($categories as $category)
                    <a
                        href="{{ route('catalog.index', [...request()->only('search'), 'category_id' => $category->id]) }}"
                        class="shop-category flex min-w-52 items-center gap-3 p-3"
                        wire:navigate
                    >
                        <img
                            src="{{ $category->image_url ? asset('storage/categories/'.$category->image_url) : asset('storage/categories/default_category.png') }}"
                            alt="{{ $category->name }}"
                            class="size-14 rounded-lg border border-[#1f1f24] bg-[#fdffb8] object-cover"
                        >
                        <span class="text-sm">
                            <span class="block font-black">{{ $category->name }}</span>
                            <span class="font-semibold text-neutral-500">{{ $category->tshirt_images_count }} {{ __('items') }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($tshirtImages as $tshirtImage)
                <article class="shop-card flex overflow-hidden">
                    <div class="flex w-full flex-col">
                        <a href="{{ route('catalog.show', $tshirtImage) }}" class="shop-image-panel block" wire:navigate>
                            <img
                                src="{{ asset('storage/tshirt_images/'.$tshirtImage->image_url) }}"
                                alt="{{ $tshirtImage->name }}"
                                class="aspect-square w-full object-contain p-6"
                            >
                        </a>

                        <div class="flex flex-1 flex-col gap-3 p-4">
                            <div class="flex flex-1 flex-col gap-2">
                                @if ($tshirtImage->category)
                                    <span class="shop-chip w-fit px-3 py-1">{{ $tshirtImage->category->name }}</span>
                                @else
                                    <span class="shop-chip w-fit px-3 py-1">{{ __('Without category') }}</span>
                                @endif

                                <h3 class="text-lg font-black leading-tight text-[#1f1f24]">{{ $tshirtImage->name }}</h3>
                                <p class="line-clamp-2 text-sm font-medium text-neutral-600">{{ $tshirtImage->description }}</p>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                @if ($price)
                                    <div>
                                        <div class="text-xs font-bold text-neutral-500">{{ __('Price') }}</div>
                                        <div class="font-black text-[#ff2dd1]">{{ number_format((float) $price->unit_price_catalog, 2) }} EUR</div>
                                    </div>
                                @endif

                                <a href="{{ route('catalog.show', $tshirtImage) }}" class="shop-link-button px-4 text-sm" wire:navigate>
                                    {{ __('View') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="shop-card p-6 text-neutral-600">
                    {{ __('No t-shirts found.') }}
                </div>
            @endforelse
        </section>

        {{ $tshirtImages->links() }}
    </div>
</x-layouts::app>
