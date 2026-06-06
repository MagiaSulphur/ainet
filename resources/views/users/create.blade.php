<x-layouts::app :title="__('Create User')">

<div class="max-w-2xl">

    <flux:heading size="xl">
        Create User
    </flux:heading>

    <form method="POST"
          action="{{ route('users.store') }}"
          class="mt-6 space-y-4">

        @csrf

        <flux:input
            name="name"
            label="Name"
            required
        />

        <flux:input
            name="email"
            type="email"
            label="Email"
            required
        />

        <flux:input
            name="password"
            type="password"
            label="Password"
            required
        />

        <flux:select
            name="gender"
            label="Gender">

            <option value="M">Male</option>
            <option value="F">Female</option>

        </flux:select>

        <flux:select
            name="user_type"
            label="Type">

            <option value="F">Employee</option>
            <option value="A">Administrator</option>

        </flux:select>

        <flux:button
            type="submit"
            variant="primary">

            Create

        </flux:button>

    </form>

</div>

</x-layouts::app>