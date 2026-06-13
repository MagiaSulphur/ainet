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
        [
            'label' => __('Cart'),
            'href' => route('cart.index'),
            'active' => request()->routeIs('cart.*', 'checkout.*'),
        ],
    ];

    if (auth()->check()) {
        $links[] = [
            'label' => auth()->user()->isCustomer() ? __('My orders') : __('Orders'),
            'href' => route('orders.index'),
            'active' => request()->routeIs('orders.*', 'dashboard'),
        ];
    }

    if (auth()->user()?->isAdmin()) {
        $links[] = [
            'label' => __('Users'),
            'href' => route('users.index'),
            'active' => request()->routeIs('users.*'),
        ];
        $links[] = [
            'label' => __('Manage catalog'),
            'href' => route('admin.catalog-images.index'),
            'active' => request()->routeIs(
                'admin.catalog-images.*',
                'admin.categories.*',
                'admin.colors.*',
                'admin.prices.*',
            ),
        ];
        $links[] = [
            'label' => __('Statistics'),
            'href' => route('admin.statistics.index'),
            'active' => request()->routeIs('admin.statistics.*'),
        ];
    }
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

        <div class="shop-navbar-actions">
            @auth
                <a href="{{ route('profile.edit') }}" class="shop-navbar-user" wire:navigate>
                    @if (auth()->user()->photo_url)
                        <img
                            src="{{ asset('storage/photos/'.auth()->user()->photo_url) }}"
                            alt=""
                            class="shop-navbar-avatar"
                        >
                    @else
                        <span class="shop-navbar-avatar shop-navbar-initials">{{ auth()->user()->initials() }}</span>
                    @endif
                    <span>{{ auth()->user()->name }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="shop-navbar-button">{{ __('Log out') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="shop-navbar-button" wire:navigate>{{ __('Login') }}</a>
            @endauth

            @if ($slot->isNotEmpty())
                {{ $slot }}
            @endif
        </div>

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

                @auth
                    <a href="{{ route('profile.edit') }}" class="shop-navbar-mobile-link" wire:navigate>
                        {{ __('Profile') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="shop-navbar-mobile-link shop-navbar-mobile-button">
                            {{ __('Log out') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="shop-navbar-mobile-link" wire:navigate>
                        {{ __('Login') }}
                    </a>
                @endauth
            </div>
        </details>
    </div>
</nav>
