<x-layouts::app :title="__('New color')">
    <div class="mx-auto flex max-w-2xl flex-col gap-6">
        <x-admin.catalog-nav />

        <div class="flex items-center justify-between gap-4">
            <flux:heading size="xl">{{ __('New color') }}</flux:heading>
            <flux:button :href="route('admin.colors.index')" variant="filled" wire:navigate>{{ __('Back') }}</flux:button>
        </div>

        <form method="POST" action="{{ route('admin.colors.store') }}" enctype="multipart/form-data" class="rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
            @include('admin.colors._form')

            <div class="mt-6 flex justify-end gap-3">
                <flux:button :href="route('admin.colors.index')" variant="filled" wire:navigate>{{ __('Cancel') }}</flux:button>
                <flux:button type="submit" variant="primary">{{ __('Create') }}</flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
