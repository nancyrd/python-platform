@php
    $items = [
        ['label' => 'Dashboard', 'route' => 'dashboard'],
        ['label' => 'About Us',  'route' => 'about'],
        ['label' => 'Contact Us','route' => 'contact'],
    ];
@endphp

<nav x-data="{ open:false }" class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b border-purple-100">
    <div class="container-app h-16 flex items-center justify-between">

        <!-- Brand -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <span class="text-3xl">📚</span>
            <span class="font-bold text-lg tracking-tight text-purple-700">PyLearn</span>
        </a>

        <!-- Desktop Nav -->
        <div class="hidden sm:flex items-center gap-1">
            @foreach ($items as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="nav-pill {{ $active ? 'is-active' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        <!-- User dropdown -->
        <div class="hidden sm:flex sm:items-center sm:ms-6">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center gap-2 px-3 py-2 rounded-lg text-gray-700 hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <div class="font-medium">{{ Auth::user()->name }}</div>
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0L5.25 8.27a.75.75 0 01-.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        <!-- Mobile hamburger -->
        <button @click="open = !open" class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-500">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path :class="{'hidden': open, 'inline-flex': ! open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path :class="{'hidden': ! open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Mobile panel -->
    <div x-show="open" x-transition class="sm:hidden border-t border-purple-100 bg-white/90 backdrop-blur">
        <div class="container-app py-3 space-y-1">
            @foreach ($items as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="block rounded-lg px-3 py-2 {{ $active ? 'bg-purple-100 text-purple-800 font-semibold' : 'text-gray-700 hover:bg-purple-50' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach

            <div class="pt-3 border-t border-purple-100">
                <div class="px-3 text-sm text-gray-600">{{ Auth::user()->name }}</div>
                <div class="px-3 text-xs text-gray-400">{{ Auth::user()->email }}</div>
                <a href="{{ route('profile.edit') }}" class="block mt-2 rounded-lg px-3 py-2 text-gray-700 hover:bg-purple-50">
                    {{ __('Profile') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left mt-1 rounded-lg px-3 py-2 text-gray-700 hover:bg-purple-50">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
