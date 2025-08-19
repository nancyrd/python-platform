{{-- resources/views/components/text-input.blade.php --}}
@props(['disabled' => false])
<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block w-full rounded-md border-purple-200 focus:border-purple-500 focus:ring-purple-500 shadow-sm']) !!}>