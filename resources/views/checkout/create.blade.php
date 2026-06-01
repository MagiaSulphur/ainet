<x-layouts::app :title="__('Checkout')">
    <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
        <form method="POST" action="{{ route('checkout.store') }}" class="flex flex-col gap-4">
            @csrf

            <div>
                <flux:heading size="xl">{{ __('Checkout') }}</flux:heading>
                <flux:text>{{ __('Confirm billing, delivery and payment details.') }}</flux:text>
            </div>

            <flux:input name="nif" :label="__('NIF')" value="{{ old('nif', $customer->nif) }}" maxlength="9" required />
            <flux:textarea name="address" :label="__('Delivery address')" required>{{ old('address', $customer->address) }}</flux:textarea>

            <flux:select name="payment_type" :label="__('Payment method')" required>
                @foreach (['Visa', 'PayPal', 'MB WAY'] as $type)
                    <option value="{{ $type }}" @selected(old('payment_type', $customer->default_payment_type) === $type)>{{ $type }}</option>
                @endforeach
            </flux:select>

            <flux:input name="payment_ref" :label="__('Payment reference')" value="{{ old('payment_ref', $customer->default_payment_ref) }}" required />
            <flux:textarea name="notes" :label="__('Notes')">{{ old('notes') }}</flux:textarea>

            <div class="flex gap-3">
                <flux:button type="submit" variant="primary">{{ __('Pay and create order') }}</flux:button>
                <flux:button :href="route('cart.index')" variant="filled" wire:navigate>{{ __('Back to cart') }}</flux:button>
            </div>
        </form>

        <aside class="h-fit rounded-xl border border-neutral-200 p-4 dark:border-neutral-700">
            <flux:heading>{{ __('Summary') }}</flux:heading>
            <div class="mt-4 flex flex-col gap-3">
                @foreach ($items as $item)
                    <div class="flex justify-between gap-3 text-sm">
                        <span>{{ $item['qty'] }}x {{ $item['image']->name }}</span>
                        <span>{{ number_format($item['sub_total'], 2) }} EUR</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 border-t border-neutral-200 pt-4 dark:border-neutral-700">
                <flux:heading>{{ number_format($total, 2) }} EUR</flux:heading>
            </div>
        </aside>
    </div>
</x-layouts::app>
