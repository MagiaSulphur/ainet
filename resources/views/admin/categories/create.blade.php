<x-layouts::app :title="__('New category')">
    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="flex max-w-2xl flex-col gap-6">
        <div>
            <flux:heading size="xl">{{ __('New category') }}</flux:heading>
            <flux:text>{{ __('Create a category for catalog designs.') }}</flux:text>
        </div>

        @include('admin.categories._form')

        <div class="flex gap-3">
            <flux:button type="submit" variant="primary">{{ __('Create') }}</flux:button>
            <flux:button :href="route('admin.categories.index')" variant="filled" wire:navigate>{{ __('Cancel') }}</flux:button>
        </div>
    </form>
</x-layouts::app>
