<x-layouts::app :title="$course->name">
    <div class="flex max-w-4xl flex-col gap-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <flux:heading size="xl">{{ $course->name }}</flux:heading>
                <flux:text>{{ $course->abbreviation }} · {{ $course->type }}</flux:text>
            </div>

            <div class="flex gap-3">
                <flux:button variant="filled" :href="route('courses.curriculum', $course)">{{ __('Curriculum') }}</flux:button>
                <flux:button variant="primary" :href="route('courses.edit', $course)">{{ __('Edit') }}</flux:button>
            </div>
        </div>

        <dl class="grid gap-4 md:grid-cols-2">
            <div>
                <dt class="text-sm text-neutral-500">{{ __('Semesters') }}</dt>
                <dd>{{ $course->semesters }}</dd>
            </div>
            <div>
                <dt class="text-sm text-neutral-500">{{ __('ECTS') }}</dt>
                <dd>{{ $course->ECTS }}</dd>
            </div>
            <div>
                <dt class="text-sm text-neutral-500">{{ __('Places') }}</dt>
                <dd>{{ $course->places }}</dd>
            </div>
            <div>
                <dt class="text-sm text-neutral-500">{{ __('Contact') }}</dt>
                <dd>{{ $course->contact }}</dd>
            </div>
        </dl>

        <flux:text>{{ $course->objectives }}</flux:text>
    </div>
</x-layouts::app>
