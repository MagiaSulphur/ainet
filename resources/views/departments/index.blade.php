<x-layouts::app :title="__('Departments')">
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <flux:heading size="xl">{{ __('Departments') }}</flux:heading>
                <flux:text>{{ __('Manage academic departments.') }}</flux:text>
            </div>

            <flux:button variant="primary" :href="route('departments.create')">{{ __('New') }}</flux:button>
        </div>

        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900">
                    <tr>
                        <th class="px-4 py-3">{{ __('Code') }}</th>
                        <th class="px-4 py-3">{{ __('Name') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($departments as $department)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $department->abbreviation }}</td>
                            <td class="px-4 py-3">{{ $department->name }}</td>
                            <td class="px-4 py-3 text-right">
                                <flux:button size="sm" variant="filled" :href="route('departments.show', $department)">{{ __('Open') }}</flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-neutral-500" colspan="3">{{ __('No departments found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $departments->links() }}
    </div>
</x-layouts::app>
