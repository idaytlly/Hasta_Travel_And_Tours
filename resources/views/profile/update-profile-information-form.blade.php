<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        {{-- Profile Photo --}}
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
        <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
            <input type="file" class="hidden" wire:model="photo" x-ref="photo"
                x-on:change="
                    photoName = $refs.photo.files[0].name;
                    const reader = new FileReader();
                    reader.onload = (e) => { photoPreview = e.target.result; };
                    reader.readAsDataURL($refs.photo.files[0]);
                " />

            <x-label for="photo" value="{{ __('Photo') }}" />

            <div class="mt-2" x-show="! photoPreview">
                <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full h-20 w-20 object-cover">
            </div>

            <div class="mt-2" x-show="photoPreview" style="display: none;">
                <span class="block rounded-full h-20 w-20 bg-cover bg-center" x-bind:style="'background-image: url(\'' + photoPreview + '\');'"></span>
            </div>

            <x-secondary-button type="button" class="mt-2 me-2" x-on:click.prevent="$refs.photo.click()">
                {{ __('Select A New Photo') }}
            </x-secondary-button>

            @if ($this->user->profile_photo_path)
                <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                    {{ __('Remove Photo') }}
                </x-secondary-button>
            @endif

            <x-input-error for="photo" class="mt-2" />
        </div>
        @endif

        {{-- Name --}}
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        {{-- Email --}}
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Your email address is unverified.') }}
                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        {{-- Phone --}}
        <div class="col-span-6 sm:col-span-4">
            <x-label for="phone" value="{{ __('Phone') }}" />
            <x-input id="phone" type="text" class="mt-1 block w-full" wire:model.defer="state.phone" required autocomplete="tel" />
            <x-input-error for="phone" class="mt-2" />
        </div>

        {{-- Customer-specific fields --}}
        @if($this->user->usertype === 'customer' || $this->user->usertype === 'user')
            <div class="col-span-6 sm:col-span-4">
                <x-label for="ic" value="{{ __('IC Number') }}" />
                <x-input id="ic" type="text" class="mt-1 block w-full" wire:model.defer="state.ic" maxlength="12" required />
                <x-input-error for="ic" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-label for="street" value="{{ __('Street') }}" />
                <x-input id="street" type="text" class="mt-1 block w-full" wire:model.defer="state.street" />
                <x-input-error for="street" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-2">
                <x-label for="city" value="{{ __('City') }}" />
                <x-input id="city" type="text" class="mt-1 block w-full" wire:model.defer="state.city" />
                <x-input-error for="city" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-2">
                <x-label for="state" value="{{ __('State') }}" />
                <x-input id="state" type="text" class="mt-1 block w-full" wire:model.defer="state.state" />
                <x-input-error for="state" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-2">
                <x-label for="postcode" value="{{ __('Postcode') }}" />
                <x-input id="postcode" type="text" class="mt-1 block w-full" wire:model.defer="state.postcode" />
                <x-input-error for="postcode" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-label for="license_no" value="{{ __('Driver License Number') }}" />
                <x-input id="license_no" type="text" class="mt-1 block w-full" wire:model.defer="state.license_no" />
                <x-input-error for="license_no" class="mt-2" />
            </div>
        @endif
    </x-slot>

    <x-button wire:loading.attr="disabled" wire:target="photo">
        {{ __('Save') }}
    </x-button>

    </x-slot>
</x-form-section>

@if(session('success'))
    <div class="mb-4 font-medium text-sm text-green-600">
        {{ session('success') }}
    </div>
@endif
