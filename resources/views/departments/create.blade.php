<x-layouts::app :title="__('New department')">
    <form method="POST" action="{{ route('departments.store') }}" class="flex max-w-3xl flex-col gap-6">
        <flux:heading size="xl">{{ __('New department') }}</flux:heading>

        @include('departments._form')

        <div class="flex items-center gap-3">
            <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            <flux:button variant="filled" :href="route('departments.index')">{{ __('Cancel') }}</flux:button>
        </div>
    </form>
</x-layouts::app>
