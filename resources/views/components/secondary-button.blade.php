{{-- resources/views/components/secondary-button.blade.php --}}
<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 rounded-lg border border-purple-300 bg-white px-4 py-2 text-purple-700 font-semibold shadow-sm hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition']) }}>
{{ $slot }}
</button>