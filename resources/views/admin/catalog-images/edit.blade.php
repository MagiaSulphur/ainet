<x-layouts::app :title="__('Edit catalog image')">
    <form method="POST" action="{{ route('admin.catalog-images.update', $image) }}" enctype="multipart/form-data" class="flex max-w-3xl flex-col gap-6">
        @method('PUT')

        <div>
            <flux:heading size="xl">{{ __('Edit catalog image') }}</flux:heading>
            <flux:text>{{ __('Update name, category, description or image file.') }}</flux:text>
        </div>

        @include('admin.catalog-images._form')

        <div class="flex gap-3">
            <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
            <flux:button :href="route('admin.catalog-images.index')" variant="filled" wire:navigate>{{ __('Cancel') }}</flux:button>
        </div>
    </form>
</x-layouts::app>
