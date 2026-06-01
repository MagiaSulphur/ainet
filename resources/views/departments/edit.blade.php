<x-layouts::app :title="__('Edit department')">
    <form method="POST" action="{{ route('departments.update', $department) }}" class="flex max-w-3xl flex-col gap-6">
        @method('PUT')

        <flux:heading size="xl">{{ __('Edit department') }}</flux:heading>

        @include('departments._form')

        <div class="flex items-center gap-3">
            <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            <flux:button variant="filled" :href="route('departments.show', $department)">{{ __('Cancel') }}</flux:button>
        </div>
    </form>
</x-layouts::app>
