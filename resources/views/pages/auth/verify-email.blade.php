<x-layouts::auth :title="__('Verify Email')">

    <div class="flex flex-col gap-6">

        <h2>Verify Email</h2>

        <p>
            Please verify your email address before continuing.
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <flux:button type="submit">
                Resend Verification Email
            </flux:button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <flux:button type="submit" variant="ghost">
                Logout
            </flux:button>
        </form>

    </div>

</x-layouts::auth>