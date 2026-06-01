<x-layouts::app :title="__('New discipline')">
    <form method="POST" action="{{ route('disciplines.store') }}" class="flex max-w-4xl flex-col gap-6">
        <flux:heading size="xl">{{ __('New discipline') }}</flux:heading>

        @include('disciplines._form')

        <div class="flex items-center gap-3">
            <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            <flux:button variant="filled" :href="route('disciplines.index')">{{ __('Cancel') }}</flux:button>
        </div>
    </form>
</x-layouts::app>
