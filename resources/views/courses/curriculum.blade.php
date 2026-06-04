<x-layouts::app :title="__('Curriculum')">
    <div class="flex flex-col gap-6">
        <div>
            <flux:heading size="xl">{{ __('Curriculum') }} · {{ $course->name }}</flux:heading>
            <flux:text>{{ $course->abbreviation }}</flux:text>
        </div>

        <div class="overflow-hidden rounded-xl border border-neutral-200">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-200 bg-neutral-50">
                    <tr>
                        <th class="px-4 py-3">{{ __('Year') }}</th>
                        <th class="px-4 py-3">{{ __('Semester') }}</th>
                        <th class="px-4 py-3">{{ __('Code') }}</th>
                        <th class="px-4 py-3">{{ __('Name') }}</th>
                        <th class="px-4 py-3">{{ __('ECTS') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse ($course->disciplines as $discipline)
                        <tr>
                            <td class="px-4 py-3">{{ $discipline->year }}</td>
                            <td class="px-4 py-3">{{ $discipline->semester }}</td>
                            <td class="px-4 py-3 font-medium">{{ $discipline->abbreviation }}</td>
                            <td class="px-4 py-3">{{ $discipline->name }}</td>
                            <td class="px-4 py-3">{{ $discipline->ECTS }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-neutral-500" colspan="5">{{ __('No disciplines found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
