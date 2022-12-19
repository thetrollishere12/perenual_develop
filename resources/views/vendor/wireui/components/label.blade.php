<label {{ $attributes->class([
        'block font-bold',
        'text-negative-600'  => $hasError,
        'opacity-60'         => $attributes->get('disabled'),
        'text-gray-600 dark:text-gray-400' => !$hasError,
    ]) }}>
    {{ $label ?? $slot }}
</label>
