{{-- resources/views/profile/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-purple-800 leading-tight">
                    {{ __('Profile') }}
                </h2>
                <p class="text-sm text-gray-500">{{ __('Manage your personal information and security.') }}</p>
            </div>
        </div>
    </x-slot>

    {{-- Hide pre-hydration content so all three forms don’t flash at once --}}
    <style>[x-cloak]{ display:none !important; }</style>

    <div
        class="py-10 bg-gradient-to-b from-purple-50/70 to-white"
        x-data="{
            tab: (new URLSearchParams(window.location.search)).get('tab') || 'profile'
        }"
        x-init="$watch('tab', t => history.replaceState(null, '', `?tab=${t}`))"
    >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur border border-purple-100/70 shadow-sm sm:rounded-xl">

                {{-- Tab list --}}
                <div class="px-4 sm:px-6 pt-5">
                    <div role="tablist"
                         class="inline-flex rounded-xl border border-purple-100 bg-purple-50/70 p-1">
                        <button
                            role="tab"
                            @click="tab='profile'"
                            :aria-selected="tab==='profile'"
                            :class="tab==='profile'
                                ? 'bg-white text-purple-700 shadow-sm'
                                : 'text-gray-600 hover:text-gray-900'"
                            class="px-4 py-2 rounded-lg font-semibold focus:outline-none focus:ring-2 focus:ring-purple-500">
                            {{ __('Profile') }}
                        </button>

                        <button
                            role="tab"
                            @click="tab='password'"
                            :aria-selected="tab==='password'"
                            :class="tab==='password'
                                ? 'bg-white text-purple-700 shadow-sm'
                                : 'text-gray-600 hover:text-gray-900'"
                            class="px-4 py-2 rounded-lg font-semibold focus:outline-none focus:ring-2 focus:ring-purple-500">
                            {{ __('Password') }}
                        </button>

                        <button
                            role="tab"
                            @click="tab='danger'"
                            :aria-selected="tab==='danger'"
                            :class="tab==='danger'
                                ? 'bg-white text-red-700 shadow-sm'
                                : 'text-gray-600 hover:text-gray-900'"
                            class="px-4 py-2 rounded-lg font-semibold focus:outline-none focus:ring-2 focus:ring-red-500">
                            {{ __('Danger Zone') }}
                        </button>
                    </div>
                </div>

                {{-- Panels --}}
                <div class="p-4 sm:p-8">
                    <div x-show="tab==='profile'" x-cloak>
                        <div class="max-w-2xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div x-show="tab==='password'" x-cloak>
                        <div class="max-w-2xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <div x-show="tab==='danger'" x-cloak>
                        <div class="max-w-2xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>