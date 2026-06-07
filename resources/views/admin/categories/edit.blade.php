<x-layouts::app :title="__('Edit category')">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="flex max-w-2xl flex-col gap-6">
        @method('PUT')

        <div>
            <flux:heading size="xl">{{ __('Edit category') }}</flux:heading>
            <flux:text>{{ __('Update category name or representative image.') }}</flux:text>
        </div>

        @include('admin.categories._form')

        <div class="flex gap-3">
            <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
            <flux:button :href="route('admin.categories.index')" variant="filled" wire:navigate>{{ __('Cancel') }}</flux:button>
        </div>
    </form>
</x-layouts::app>
