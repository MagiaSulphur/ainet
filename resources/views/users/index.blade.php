<x-layouts::app :title="__('Users')">

    <div class="flex flex-col gap-6">

        <div>
            <flux:heading size="xl">
                {{ __('Users') }}
            </flux:heading>

            <flux:text>
                {{ __('Manage platform users.') }}
            </flux:text>
            <flux:button
    :href="route('users.create')"
    wire:navigate>

    Create User

</flux:button>
        </div>

        <form method="GET"
      action="{{ route('users.index') }}"
      class="flex flex-col gap-3 md:flex-row">

    <flux:input
        name="search"
        placeholder="Name or Email"
        value="{{ request('search') }}"
    />

    <flux:select name="type">

        <option value="">
            All Types
        </option>

        <option value="A" @selected(request('type') === 'A')>
            Admin
        </option>

        <option value="F" @selected(request('type') === 'F')>
            Employee
        </option>

        <option value="C" @selected(request('type') === 'C')>
            Customer
        </option>

    </flux:select>

    <flux:select name="blocked">

        <option value="">
            All
        </option>

        <option value="1" @selected(request('blocked') === '1')>
            Blocked
        </option>

        <option value="0" @selected(request('blocked') === '0')>
            Active
        </option>

    </flux:select>

    <flux:button
        type="submit"
        variant="primary">

        Filter

    </flux:button>

</form>

        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">

            <table class="w-full text-left text-sm">

                <thead class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Blocked</th>
                        <th class="px-4 py-3">Block action</th>
                        <th class="px-4 py-3">Edit action</th>
                        <th class="px-4 py-3">Delete action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">

                    @foreach ($users as $user)

                        <tr>
                            <td class="px-4 py-3">
                                {{ $user->name }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $user->email }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $user->user_type }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $user->blocked ? 'Yes' : 'No' }}
                            </td>

                            <td class="px-4 py-3">

    @if($user->id !== auth()->id())

        <form method="POST"
            action="{{ route('users.toggle-block', $user) }}">

            @csrf
            @method('PATCH')

            <flux:button
                type="submit"
                variant="{{ $user->blocked ? 'primary' : 'danger' }}"
                size="sm">

                {{ $user->blocked ? 'Unblock' : 'Block' }}

            </flux:button>

        </form>

    @else

        <span class="text-neutral-500">
            Current User
        </span>

    @endif

</td>

<td class="px-4 py-3">

<a href="{{ route('users.edit', $user) }}">
    <flux:button
        variant="primary"
        size="sm">

        Edit

    </flux:button>
</a>

</td>

<td class="px-4 py-3">

    @if($user->id !== auth()->id())

        <form method="POST"
            action="{{ route('users.destroy', $user) }}"
            onsubmit="return confirm('Delete this user?')">

            @csrf
            @method('DELETE')

            <flux:button
                type="submit"
                variant="danger"
                size="sm">

                Delete

            </flux:button>

        </form>

    @else

        <span class="text-neutral-500">
            Current User
        </span>

    @endif

</td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        {{ $users->links() }}

    </div>

</x-layouts::app>