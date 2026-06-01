<x-layouts::app :title="__('Catalog')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <flux:heading size="xl">{{ __('FunShirt Catalog') }}</flux:heading>
                <flux:text>{{ __('Browse available t-shirt designs.') }}</flux:text>
            </div>

            <form method="GET" action="{{ route('catalog.index') }}" class="flex flex-col gap-3 md:flex-row">
                <flux:input
                    name="search"
                    placeholder="{{ __('Search t-shirts...') }}"
                    value="{{ request('search') }}"
                />

                <flux:select name="category_id">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </flux:select>

                <flux:button type="submit" variant="primary">{{ __('Search') }}</flux:button>
            </form>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($tshirtImages as $tshirtImage)
                <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white transition hover:border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-600">
                    <img
                        src="{{ asset('storage/tshirt_images/'.$tshirtImage->image_url) }}"
                        alt="{{ $tshirtImage->name }}"
                        class="aspect-square w-full object-cover"
                    >

                    <div class="flex flex-col gap-2 p-4">
                        <flux:heading size="lg">{{ $tshirtImage->name }}</flux:heading>
                        <flux:text class="line-clamp-2">{{ $tshirtImage->description }}</flux:text>

                        @if ($tshirtImage->category)
                            <flux:badge>{{ $tshirtImage->category->name }}</flux:badge>
                        @endif

                        <flux:button :href="route('catalog.show', $tshirtImage)" variant="primary" wire:navigate>
                            {{ __('Customize') }}
                        </flux:button>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-neutral-200 p-6 text-neutral-500 dark:border-neutral-700">
                    {{ __('No t-shirts found.') }}
                </div>
            @endforelse
        </div>

        {{ $tshirtImages->links() }}
    </div>
</x-layouts::app>
