<x-layouts::app :title="__('Edit color')">
    <div class="mx-auto flex max-w-2xl flex-col gap-6">
        <x-admin.catalog-nav />

        <div class="flex items-center justify-between gap-4">
            <flux:heading size="xl">{{ __('Edit color') }}</flux:heading>
            <flux:button :href="route('admin.colors.index')" variant="filled" wire:navigate>{{ __('Back') }}</flux:button>
        </div>

        <form method="POST" action="{{ route('admin.colors.update', $color) }}" enctype="multipart/form-data" class="rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
            @method('PUT')
            @include('admin.colors._form')

            <div class="mt-6 flex justify-end gap-3">
                <flux:button :href="route('admin.colors.index')" variant="filled" wire:navigate>{{ __('Cancel') }}</flux:button>
                <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
