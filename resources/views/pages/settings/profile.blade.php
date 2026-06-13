<?php

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new #[Title('Profile settings')] class extends Component {
    use ProfileValidationRules;
    use WithFileUploads;

    public $photo = '';
    public string $name = '';
    public string $email = '';
    public string $gender = '';

    public ?string $nif = null;
    public ?string $address = null;

    #[Computed]
    public function canEdit(): bool
    {
        return Auth::user()->user_type !== 'F';
    }

    /**
     * Mount the component.
     */
public function mount(): void
{
    $user = Auth::user();

    $this->name = $user->name;
    $this->email = $user->email;
    $this->gender = $user->gender;

    if ($user->customer) {
        $this->nif = $user->customer->nif;
        $this->address = $user->customer->address;
    }
}

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {

    if (! $this->canEdit) {
        abort(403);
    }

        $user = Auth::user();

        $validated = $this->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email'],
    'gender' => ['required', 'in:M,F'],
    'nif' => ['nullable', 'string', 'max:9'],
    'address' => ['nullable', 'string'],
    'photo' => [
        'nullable',
        'image',
        'max:2048',
    ],
]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->gender = $validated['gender'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

if ($this->photo) {

    $filename = $this->photo->store(
        'photos',
        'public'
    );

    $user->update([
        'photo_url' => basename($filename),
    ]);
}

        if ($user->customer) {
            $user->customer->update([
        'nif' => $validated['nif'],
        'address' => $validated['address'],
    ]);
}

        Flux::toast(variant: 'success', text: __('Profile updated.'));
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Flux::toast(text: __('A new verification link has been sent to your email address.'));
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" :disabled="!$this->canEdit" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" :disabled="!$this->canEdit" />

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                    </div>
                @endif
            </div>


            @if(auth()->user()->photo_url)

    <img
        src="{{ asset('storage/photos/' . auth()->user()->photo_url) }}"
        class="h-20 w-20 rounded-full object-cover"
    >

@endif

            <flux:input
    wire:model="photo"
    type="file"
    label="Photo"
    :disabled="!$this->canEdit"
/>

<div>
    <label>Gender</label>
    <select wire:model="gender" :disabled="!$this->canEdit">
        <option value="M">Male</option>
        <option value="F">Female</option>
    </select>
</div>

<flux:input
    wire:model="nif"
    label="NIF"
    type="text"
    :disabled="!$this->canEdit"
/>

<flux:input
    wire:model="address"
    :label="__('Address')"
    type="text"
    :disabled="!$this->canEdit"
/>

            @if($this->canEdit)
<div class="flex items-center gap-4">
    <flux:button
        variant="primary"
        type="submit"
        data-test="update-profile-button"
    >
        {{ __('Save') }}
    </flux:button>
</div>
@endif
        </form>

        @if ($this->showDeleteUser && $this->canEdit)
            <livewire:pages::settings.delete-user-form />
        @endif
    </x-pages::settings.layout>
</section>
