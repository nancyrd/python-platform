{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
<section>
<header class="border-b border-purple-100/80 pb-4 mb-4">
<h2 class="text-lg font-semibold text-purple-900">{{ __('Profile Information') }}</h2>
<p class="mt-1 text-sm text-gray-600">{{ __("Update your account's profile information and email address.") }}</p>
</header>


<form id="send-verification" method="post" action="{{ route('verification.send') }}">
@csrf
</form>


<form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
@csrf
@method('patch')


<div>
<x-input-label for="name" :value="__('Name')" />
<x-text-input id="name" name="name" type="text" class="mt-1 block w-full border-purple-200 focus:border-purple-500 focus:ring-purple-500" :value="old('name', $user->name)" required autofocus autocomplete="name" />
<x-input-error class="mt-2" :messages="$errors->get('name')" />
</div>


<div>
<x-input-label for="email" :value="__('Email')" />
<x-text-input id="email" name="email" type="email" class="mt-1 block w-full border-purple-200 focus:border-purple-500 focus:ring-purple-500" :value="old('email', $user->email)" required autocomplete="username" />
<x-input-error class="mt-2" :messages="$errors->get('email')" />


@if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
<div>
<p class="text-sm mt-2 text-gray-800">
{{ __('Your email address is unverified.') }}
<button form="send-verification" class="underline text-sm text-purple-700 hover:text-purple-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
{{ __('Click here to re-send the verification email.') }}
</button>
</p>


@if (session('status') === 'verification-link-sent')
<p class="mt-2 font-medium text-sm text-green-600">
{{ __('A new verification link has been sent to your email address.') }}
</p>
@endif
</div>
@endif
</div>


<div class="flex items-center gap-4">
<x-primary-button class="bg-purple-600 hover:bg-purple-700 focus:ring-purple-500">{{ __('Save') }}</x-primary-button>


@if (session('status') === 'profile-updated')
<p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
@endif
</section>