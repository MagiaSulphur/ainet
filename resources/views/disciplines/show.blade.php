<x-layouts::app :title="$discipline->name">
    <div class="flex max-w-4xl flex-col gap-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <flux:heading size="xl">{{ $discipline->name }}</flux:heading>
                <flux:text>{{ $discipline->abbreviation }} · {{ $discipline->course }}</flux:text>
            </div>

            <flux:button variant="primary" :href="route('disciplines.edit', $discipline)">{{ __('Edit') }}</flux:button>
        </div>

        <dl class="grid gap-4 md:grid-cols-2">
            <div>
                <dt class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Year') }}</dt>
                <dd>{{ $discipline->year }}</dd>
            </div>
            <div>
                <dt class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Semester') }}</dt>
                <dd>{{ $discipline->semester }}</dd>
            </div>
            <div>
                <dt class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('ECTS') }}</dt>
                <dd>{{ $discipline->ECTS }}</dd>
            </div>
            <div>
                <dt class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Hours') }}</dt>
                <dd>{{ $discipline->hours }}</dd>
            </div>
        </dl>
    </div>
</x-layouts::app>
