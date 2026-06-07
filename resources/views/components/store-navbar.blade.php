@props([
    'brand' => 'FunShirt',
    'links' => null,
])

@php
    $links = $links ?? [
        [
            'label' => __('Catalog'),
            'href' => route('catalog.index'),
            'active' => request()->routeIs('home', 'catalog.*'),
        ],
    ];
@endphp

<nav class="shop-navbar">
    <div class="shop-navbar-inner">
        <a href="{{ route('home') }}" class="shop-navbar-brand" wire:navigate>
            <span class="shop-navbar-mark">FS</span>
            <span>{{ $brand }}</span>
        </a>

        <div class="shop-navbar-links">
            @foreach ($links as $link)
                <a
                    href="{{ $link['href'] }}"
                    class="shop-navbar-link {{ $link['active'] ? 'shop-navbar-link-active' : '' }}"
                    @if (str_starts_with($link['href'], url('/')) || str_starts_with($link['href'], '/')) wire:navigate @endif
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>

        @if ($slot->isNotEmpty())
            <div class="shop-navbar-actions">
                {{ $slot }}
            </div>
        @endif

        <details class="shop-navbar-mobile">
            <summary>{{ __('Menu') }}</summary>
            <div class="shop-navbar-mobile-panel">
                @foreach ($links as $link)
                    <a
                        href="{{ $link['href'] }}"
                        class="shop-navbar-mobile-link {{ $link['active'] ? 'shop-navbar-mobile-link-active' : '' }}"
                        @if (str_starts_with($link['href'], url('/')) || str_starts_with($link['href'], '/')) wire:navigate @endif
                    >
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
        </details>
    </div>
</nav>
