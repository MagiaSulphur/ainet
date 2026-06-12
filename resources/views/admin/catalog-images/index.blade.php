<x-layouts::app :title="__('Catalog images')">
    <div class="flex flex-col gap-6">
        <x-admin.catalog-nav />

        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <flux:heading size="xl">{{ __('Catalog images') }}</flux:heading>
                <flux:text>{{ __('Manage public designs available to every visitor.') }}</flux:text>
            </div>

            <flux:button :href="route('admin.catalog-images.create')" variant="primary" wire:navigate>{{ __('New image') }}</flux:button>
        </div>

        @if (session('status'))
            <flux:callout color="green">{{ session('status') }}</flux:callout>
        @endif

        <form method="GET" action="{{ route('admin.catalog-images.index') }}" class="grid gap-3 md:grid-cols-[minmax(220px,1fr)_220px_auto]">
            <flux:input name="search" icon="magnifying-glass" placeholder="{{ __('Search') }}" value="{{ request('search') }}" />
            <flux:select name="category_id">
                <option value="">{{ __('All categories') }}</option>
                <option value="none" @selected(request('category_id') === 'none')>{{ __('Without category') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </flux:select>
            <flux:button type="submit" variant="filled">{{ __('Filter') }}</flux:button>
        </form>

        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900">
                    <tr>
                        <th class="px-4 py-3">{{ __('Image') }}</th>
                        <th class="px-4 py-3">{{ __('Category') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Orders') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($images as $image)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('storage/tshirt_images/'.$image->image_url) }}" alt="{{ $image->name }}" class="size-14 rounded bg-white object-contain p-1">
                                    <div>
                                        <div class="font-medium">{{ $image->name }}</div>
                                        <div class="line-clamp-1 text-neutral-500">{{ $image->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $image->category?->name ?? __('Without category') }}</td>
                            <td class="px-4 py-3 text-right">{{ $image->order_items_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <flux:button size="sm" variant="filled" :href="route('admin.catalog-images.edit', $image)" wire:navigate>{{ __('Edit') }}</flux:button>
                                    <form method="POST" action="{{ route('admin.catalog-images.destroy', $image) }}">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button size="sm" type="submit" variant="danger">{{ __('Delete') }}</flux:button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-neutral-500">{{ __('No catalog images found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $images->links() }}
    </div>
</x-layouts::app>
