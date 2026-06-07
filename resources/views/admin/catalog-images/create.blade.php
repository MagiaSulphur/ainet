<x-layouts::app :title="__('New catalog image')">
    <form method="POST" action="{{ route('admin.catalog-images.store') }}" enctype="multipart/form-data" class="flex max-w-3xl flex-col gap-6">
        <div>
            <flux:heading size="xl">{{ __('New catalog image') }}</flux:heading>
            <flux:text>{{ __('Upload a public design for the FunShirt catalog.') }}</flux:text>
        </div>

        @include('admin.catalog-images._form')

        <div class="flex gap-3">
            <flux:button type="submit" variant="primary">{{ __('Create') }}</flux:button>
            <flux:button :href="route('admin.catalog-images.index')" variant="filled" wire:navigate>{{ __('Cancel') }}</flux:button>
        </div>
    </form>
</x-layouts::app>
