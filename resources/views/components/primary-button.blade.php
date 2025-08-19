{{-- resources/views/components/primary-button.blade.php --}}
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-white font-semibold shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition']) }}>
{{ $slot }}
</button>