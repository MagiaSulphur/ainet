<x-layouts::app :title="__('Colors')">
    <div class="flex flex-col gap-6">
        <x-admin.catalog-nav />

        <div class="flex items-end justify-between gap-4">
            <div>
                <flux:heading size="xl">{{ __('Colors') }}</flux:heading>
                <flux:text>{{ __('Manage t-shirt colors available for sale.') }}</flux:text>
            </div>
            <flux:button :href="route('admin.colors.create')" variant="primary" wire:navigate>{{ __('New color') }}</flux:button>
        </div>

        @if (session('status'))
            <flux:callout color="green">{{ session('status') }}</flux:callout>
        @endif

        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900">
                    <tr>
                        <th class="px-4 py-3">{{ __('Color') }}</th>
                        <th class="px-4 py-3">{{ __('Base t-shirt') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Orders') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($colors as $color)
                        @php
                            $cssColor = str_starts_with($color->code, '#') || ! preg_match('/^[A-Fa-f0-9]{3,8}$/', $color->code)
                                ? $color->code
                                : '#'.$color->code;
                        @endphp
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="size-8 rounded border border-neutral-300" style="background-color: {{ $cssColor }}"></span>
                                    <div>
                                        <div class="font-medium">{{ $color->name }}</div>
                                        <div class="font-mono text-xs text-neutral-500 dark:text-neutral-400">{{ $color->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <img
                                    src="{{ asset('storage/tshirt_base/'.$color->code.'.jpg') }}"
                                    alt="{{ $color->name }}"
                                    class="size-14 rounded bg-white object-contain p-1"
                                >
                            </td>
                            <td class="px-4 py-3 text-right">{{ $color->order_items_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <flux:button size="sm" variant="filled" :href="route('admin.colors.edit', $color)" wire:navigate>{{ __('Edit') }}</flux:button>
                                    <form method="POST" action="{{ route('admin.colors.destroy', $color) }}">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button size="sm" type="submit" variant="danger">{{ __('Delete') }}</flux:button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-neutral-500 dark:text-neutral-400">{{ __('No colors found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $colors->links() }}
    </div>
</x-layouts::app>
