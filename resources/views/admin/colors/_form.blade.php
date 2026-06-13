@csrf

<div class="grid gap-4">
    @if (! $color->exists)
        <flux:input name="code" :label="__('CSS color code')" value="{{ old('code', $color->code) }}" placeholder="fafafa" minlength="6" maxlength="6" required />
    @else
        @php
            $cssColor = str_starts_with($color->code, '#') || ! preg_match('/^[A-Fa-f0-9]{3,8}$/', $color->code)
                ? $color->code
                : '#'.$color->code;
        @endphp
        <div>
            <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400 dark:text-neutral-400 dark:text-neutral-200">{{ __('CSS color code') }}</div>
            <div class="mt-2 flex items-center gap-3 rounded-lg border border-neutral-200 p-3 dark:border-neutral-700">
                <span class="size-8 rounded border border-neutral-300" style="background-color: {{ $cssColor }}"></span>
                <span class="font-mono text-sm">{{ $color->code }}</span>
            </div>
        </div>
    @endif

    <flux:input name="name" :label="__('Name')" value="{{ old('name', $color->name) }}" required />
    <flux:input name="base_image" :label="__('Base t-shirt image')" type="file" accept="image/jpeg,image/jpg" @required(! $color->exists) />
    <flux:text class="text-sm">
        {{ $color->exists
            ? __('Upload a JPEG file only when replacing the base t-shirt image.')
            : __('A JPEG base t-shirt image is required for every new color.') }}
    </flux:text>

    @if ($color->exists)
        <img
            src="{{ asset('storage/tshirt_base/'.$color->code.'.jpg') }}"
            alt="{{ $color->name }}"
            class="size-40 rounded-lg border border-neutral-200 bg-white object-contain p-2 dark:border-neutral-700"
        >
    @endif
</div>
