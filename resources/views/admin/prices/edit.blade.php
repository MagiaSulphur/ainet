<x-layouts::app :title="__('Prices')">
    <div class="mx-auto flex max-w-2xl flex-col gap-6">
        <x-admin.catalog-nav />

        <div>
            <flux:heading size="xl">{{ __('Prices') }}</flux:heading>
            <flux:text>{{ __('Configure catalog and custom image prices, including quantity discounts.') }}</flux:text>
        </div>

        @if (session('status'))
            <flux:callout color="green">{{ session('status') }}</flux:callout>
        @endif

        <form method="POST" action="{{ route('admin.prices.update') }}" class="rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:input name="unit_price_catalog" :label="__('Catalog unit price')" type="number" step="0.01" min="0.01" value="{{ old('unit_price_catalog', $price->unit_price_catalog) }}" required />
                <flux:input name="unit_price_catalog_discount" :label="__('Catalog discount price')" type="number" step="0.01" min="0.01" value="{{ old('unit_price_catalog_discount', $price->unit_price_catalog_discount) }}" required />
                <flux:input name="unit_price_own" :label="__('Custom image unit price')" type="number" step="0.01" min="0.01" value="{{ old('unit_price_own', $price->unit_price_own) }}" required />
                <flux:input name="unit_price_own_discount" :label="__('Custom image discount price')" type="number" step="0.01" min="0.01" value="{{ old('unit_price_own_discount', $price->unit_price_own_discount) }}" required />
                <flux:input name="qty_discount" :label="__('Discount quantity')" type="number" min="1" max="999" value="{{ old('qty_discount', $price->qty_discount) }}" required />
            </div>

            <div class="mt-6 flex justify-end">
                <flux:button type="submit" variant="primary">{{ __('Save prices') }}</flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
