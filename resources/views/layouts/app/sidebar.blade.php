<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :href="route('home')" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group heading="Shop" class="grid">
                    <flux:sidebar.item icon="shopping-bag" :href="route('catalog.index')" :current="request()->routeIs('home', 'catalog.*')" wire:navigate>
                        {{ __('Catalog') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="shopping-cart" :href="route('cart.index')" :current="request()->routeIs('cart.*')" wire:navigate>
                        {{ __('Cart') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            @auth
                <flux:sidebar.nav>
                    <flux:sidebar.group heading="Orders" class="grid">
                        <flux:sidebar.item icon="shopping-bag" :href="route('orders.index')" :current="request()->routeIs('orders.*', 'dashboard')" wire:navigate>
                            {{ auth()->user()->isCustomer() ? __('My orders') : __('Orders') }}
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                </flux:sidebar.nav>

                @if(auth()->user()->isAdmin())

    <flux:sidebar.nav>

        <flux:sidebar.group heading="Administration">

            <flux:sidebar.item
                icon="users"
                :href="route('users.index')"
                :current="request()->routeIs('users.*')"
                wire:navigate>

                Users

            </flux:sidebar.item>

        </flux:sidebar.group>

    </flux:sidebar.nav>

@endif
            @endauth

            <flux:spacer />

            @auth
                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
            @else
                <flux:sidebar.item icon="user" :href="route('login')" :current="request()->routeIs('login')" wire:navigate>
                    {{ __('Login') }}
                </flux:sidebar.item>
            @endauth
        </flux:sidebar>

        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            @auth
                <flux:dropdown position="top" align="end">
                    <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />
                    <flux:menu>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer">
                                {{ __('Log out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            @else
                <flux:button :href="route('login')" variant="filled" wire:navigate>{{ __('Login') }}</flux:button>
            @endauth
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
