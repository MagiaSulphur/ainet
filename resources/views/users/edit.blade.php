<x-layouts::app :title="__('Edit User')">

<div class="max-w-2xl">

    <flux:heading size="xl">
        Edit User
    </flux:heading>

    <form method="POST"
          action="{{ route('users.update', $user) }}"
          class="mt-6 space-y-4">

        @csrf
        @method('PATCH')

        <flux:input
            name="name"
            label="Name"
            value="{{ old('name', $user->name) }}"
            required
        />

        <flux:input
            name="email"
            type="email"
            label="Email"
            value="{{ old('email', $user->email) }}"
            required
        />

        <flux:select
            name="gender"
            label="Gender">

            <option value="M"
                @selected($user->gender === 'M')>
                Male
            </option>

            <option value="F"
                @selected($user->gender === 'F')>
                Female
            </option>

        </flux:select>

        <flux:select
            name="user_type"
            label="Type">

            <option value="A"
                @selected($user->user_type === 'A')>
                Administrator
            </option>

            <option value="F"
                @selected($user->user_type === 'F')>
                Employee
            </option>

            <option value="C"
                @selected($user->user_type === 'C')>
                Customer
            </option>

        </flux:select>

        <label class="flex gap-2 items-center">
            <input
                type="checkbox"
                name="blocked"
                value="1"
                @checked($user->blocked)
            >

            Blocked
        </label>

        <flux:button
            type="submit"
            variant="primary">

            Save

        </flux:button>

    </form>

</div>

</x-layouts::app>