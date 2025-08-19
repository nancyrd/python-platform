{{-- resources/views/components/input-label.blade.php --}}
@props(['value'])
<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-gray-800']) }}>
{{ $value ?? $slot }}
</label>