<button {{ $attributes->merge(['class' => 'px-8 py-1.5 border-transparent rounded-md text-sm relative focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-300 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
