<x-layouts::app :title="$department->name">
    <div class="flex max-w-3xl flex-col gap-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <flux:heading size="xl">{{ $department->name }}</flux:heading>
                <flux:text>{{ $department->abbreviation }}</flux:text>
            </div>

            <flux:button variant="primary" :href="route('departments.edit', $department)">{{ __('Edit') }}</flux:button>
        </div>

        <dl class="grid gap-4 md:grid-cols-2">
            <div>
                <dt class="text-sm text-neutral-500">{{ __('Portuguese name') }}</dt>
                <dd>{{ $department->name_pt }}</dd>
            </div>
        </dl>
    </div>
</x-layouts::app>
