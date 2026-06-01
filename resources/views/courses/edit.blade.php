<x-layouts::app :title="__('Edit course')">
    <form method="POST" action="{{ route('courses.update', $course) }}" class="flex max-w-4xl flex-col gap-6">
        @method('PUT')

        <flux:heading size="xl">{{ __('Edit course') }}</flux:heading>

        @include('courses._form')

        <div class="flex items-center gap-3">
            <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            <flux:button variant="filled" :href="route('courses.show', $course)">{{ __('Cancel') }}</flux:button>
        </div>
    </form>
</x-layouts::app>
