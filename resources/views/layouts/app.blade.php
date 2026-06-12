<x-layouts::app.sidebar :title="$title ?? null">
    <main class="shop-main">
        {{ $slot }}
    </main>
</x-layouts::app.sidebar>
