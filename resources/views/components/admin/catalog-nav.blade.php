<nav class="flex flex-wrap gap-2" aria-label="{{ __('Catalog administration') }}">
    <a
        href="{{ route('admin.catalog-images.index') }}"
        class="shop-admin-nav-link {{ request()->routeIs('admin.catalog-images.*') ? 'shop-admin-nav-link-active' : '' }}"
        wire:navigate
    >
        {{ __('T-shirt images') }}
    </a>
    <a
        href="{{ route('admin.categories.index') }}"
        class="shop-admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'shop-admin-nav-link-active' : '' }}"
        wire:navigate
    >
        {{ __('Categories') }}
    </a>
    <a
        href="{{ route('admin.colors.index') }}"
        class="shop-admin-nav-link {{ request()->routeIs('admin.colors.*') ? 'shop-admin-nav-link-active' : '' }}"
        wire:navigate
    >
        {{ __('Colors') }}
    </a>
    <a
        href="{{ route('admin.prices.edit') }}"
        class="shop-admin-nav-link {{ request()->routeIs('admin.prices.*') ? 'shop-admin-nav-link-active' : '' }}"
        wire:navigate
    >
        {{ __('Prices') }}
    </a>
</nav>
