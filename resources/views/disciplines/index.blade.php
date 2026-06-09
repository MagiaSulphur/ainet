<x-layouts::app :title="__('Disciplines')">
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <flux:heading size="xl">{{ __('Disciplines') }}</flux:heading>
                <flux:text>{{ __('Manage course disciplines.') }}</flux:text>
            </div>

            <flux:button variant="primary" :href="route('disciplines.create')">{{ __('New') }}</flux:button>
        </div>

        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900">
                    <tr>
                        <th class="px-4 py-3">{{ __('Course') }}</th>
                        <th class="px-4 py-3">{{ __('Code') }}</th>
                        <th class="px-4 py-3">{{ __('Name') }}</th>
                        <th class="px-4 py-3">{{ __('Year') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($disciplines as $discipline)
                        <tr>
                            <td class="px-4 py-3">{{ $discipline->course }}</td>
                            <td class="px-4 py-3 font-medium">{{ $discipline->abbreviation }}</td>
                            <td class="px-4 py-3">{{ $discipline->name }}</td>
                            <td class="px-4 py-3">{{ $discipline->year }}</td>
                            <td class="px-4 py-3 text-right">
                                <flux:button size="sm" variant="filled" :href="route('disciplines.show', $discipline)">{{ __('Open') }}</flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-neutral-500 dark:text-neutral-400" colspan="5">{{ __('No disciplines found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $disciplines->links() }}
    </div>
</x-layouts::app>
