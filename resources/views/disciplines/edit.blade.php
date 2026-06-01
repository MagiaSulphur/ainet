<x-layouts::app :title="__('Edit discipline')">
    <form method="POST" action="{{ route('disciplines.update', $discipline) }}" class="flex max-w-4xl flex-col gap-6">
        @method('PUT')

        <flux:heading size="xl">{{ __('Edit discipline') }}</flux:heading>

        @include('disciplines._form')

        <div class="flex items-center gap-3">
            <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            <flux:button variant="filled" :href="route('disciplines.show', $discipline)">{{ __('Cancel') }}</flux:button>
        </div>
    </form>
</x-layouts::app>
