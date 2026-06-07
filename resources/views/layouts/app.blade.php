<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main class="shop-main">
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
