{{-- resources/views/components/modal.blade.php (Breeze-style) --}}
@props(['name', 'show' => false, 'maxWidth' => '2xl'])
@php
$maxWidthClass = match ($maxWidth) {
'sm' => 'sm:max-w-sm',
'md' => 'sm:max-w-md',
'lg' => 'sm:max-w-lg',
'xl' => 'sm:max-w-xl',
default => 'sm:max-w-2xl',
};
@endphp
<div
x-data="{ show: @js($show) }"
x-show="show"
x-on:open-modal.window="if ($event.detail === '{{ $name }}') show = true"
x-on:close.window="show = false"
x-on:keydown.escape.window="show = false"
class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
>
<div x-show="show" class="fixed inset-0 bg-black/60" x-transition.opacity></div>
<div x-show="show" class="mb-6 bg-white dark:bg-neutral-900 rounded-xl shadow-xl border border-purple-200/70 {{ $maxWidthClass }} sm:mx-auto" x-transition.scale.origin.bottom>
{{ $slot }}
</div>
</div>




