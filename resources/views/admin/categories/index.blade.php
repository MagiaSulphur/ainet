<x-layouts::app :title="__('Categories')">
    <div class="flex flex-col gap-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                <flux:heading size="xl">{{ __('Categories') }}</flux:heading>
                <flux:text>{{ __('Organize public catalog images.') }}</flux:text>
            </div>
            <flux:button :href="route('admin.categories.create')" variant="primary" wire:navigate>{{ __('New category') }}</flux:button>
        </div>

        @if (session('status'))
            <flux:callout color="green">{{ session('status') }}</flux:callout>
        @endif

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($categories as $category)
                <article class="flex gap-4 rounded-xl border border-neutral-200 p-4 dark:border-neutral-700">
                    <img
                        src="{{ $category->image_url ? asset('storage/categories/'.$category->image_url) : asset('storage/categories/default_category.png') }}"
                        alt="{{ $category->name }}"
                        class="size-20 rounded-lg object-cover"
                    >
                    <div class="flex min-w-0 flex-1 flex-col gap-3">
                        <div>
                            <flux:heading>{{ $category->name }}</flux:heading>
                            <flux:text>{{ $category->tshirt_images_count }} {{ __('images') }}</flux:text>
                        </div>
                        <div class="flex gap-2">
                            <flux:button size="sm" variant="filled" :href="route('admin.categories.edit', $category)" wire:navigate>{{ __('Edit') }}</flux:button>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                @csrf
                                @method('DELETE')
                                <flux:button size="sm" type="submit" variant="danger">{{ __('Delete') }}</flux:button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{ $categories->links() }}
    </div>
</x-layouts::app>
