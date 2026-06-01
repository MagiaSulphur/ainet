<x-layouts::app :title="__('New course')">
    <form method="POST" action="{{ route('courses.store') }}" class="flex max-w-4xl flex-col gap-6">
        <flux:heading size="xl">{{ __('New course') }}</flux:heading>

        @include('courses._form')

        <div class="flex items-center gap-3">
            <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            <flux:button variant="filled" :href="route('courses.index')">{{ __('Cancel') }}</flux:button>
        </div>
    </form>
</x-layouts::app>
